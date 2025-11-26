<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller
{
    // Get all discussions for a user
    public function index(Request $request)
    {
        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        $query = Discussion::with(['admin', 'latestMessage.user'])
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId)->where('is_read', false);
            }]);

        if ($isSuperAdmin) {
            // SuperAdmin can see all discussions or filter by admin
            if ($request->has('admin_id')) {
                $query->where('admin_id', $request->admin_id);
            }
        } else {
            // Admin can only see their own discussions
            $query->where('admin_id', $userId);
        }

        $discussions = $query->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($discussions);
    }


    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'admin_id' => 'nullable|exists:users,id'
        ]);

        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        DB::beginTransaction();
        try {
            $discussion = Discussion::create([
                'admin_id' => $isSuperAdmin ? $request->admin_id : $userId,
                'superadmin_id' => $isSuperAdmin ? $userId : null,
                'subject' => $request->subject,
                'status' => 'open',
                'last_message_at' => now()
            ]);

            $message = DiscussionMessage::create([
                'discussion_id' => $discussion->id,
                'user_id' => $userId,
                'message' => $request->message,
                'is_read' => false
            ]);

            DB::commit();

            $discussion->load(['admin', 'latestMessage.user']);
            return response()->json($discussion, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create discussion'], 500);
        }
    }

    // Get single discussion with messages
    public function show($id)
    {
        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        $discussion = Discussion::with(['admin', 'messages.user']);

        if (!$isSuperAdmin) {
            $discussion->where('admin_id', $userId);
        }

        $discussion = $discussion->findOrFail($id);

        // Mark messages as read
        DiscussionMessage::where('discussion_id', $id)
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($discussion);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        $discussion = Discussion::findOrFail($id);

        // Check permission
        if (!$isSuperAdmin && $discussion->admin_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = DiscussionMessage::create([
            'discussion_id' => $id,
            'user_id' => $userId,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Update discussion last message time
        $discussion->update([
            'last_message_at' => now(),
            'superadmin_id' => $isSuperAdmin ? $userId : $discussion->superadmin_id
        ]);

        $message->load('user');
        return response()->json($message, 201);
    }

    // Get new messages (for polling)
    public function getNewMessages($id, Request $request)
    {
        $userId = Auth::id();
        $lastMessageId = $request->query('last_message_id', 0);

        $messages = DiscussionMessage::where('discussion_id', $id)
            ->where('id', '>', $lastMessageId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        DiscussionMessage::where('discussion_id', $id)
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function close($id)
    {
        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        $discussion = Discussion::findOrFail($id);

        if (!$isSuperAdmin && $discussion->admin_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $discussion->update(['status' => 'closed']);

        return response()->json(['message' => 'Discussion closed successfully']);
    }


    public function reopen($id)
    {
        $userId = Auth::id();
        $isSuperAdmin = Auth::user()->role === 'superadmin';

        $discussion = Discussion::findOrFail($id);

        if (!$isSuperAdmin && $discussion->admin_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $discussion->update(['status' => 'open']);

        return response()->json(['message' => 'Discussion reopened successfully']);
    }
}
