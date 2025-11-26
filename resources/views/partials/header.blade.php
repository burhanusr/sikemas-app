<header class="flex h-20 items-center justify-between border-b border-green-100 bg-white px-6 shadow-sm">
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleMobileMenu()"
            class="text-gray-600 transition-colors hover:text-green-600 focus:outline-none md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Sidebar Toggle (Desktop) -->
        <button onclick="toggleSidebar()"
            class="hidden text-gray-600 transition-colors hover:text-green-600 focus:outline-none md:block">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800">
            {{ Auth::user()->organization }}
        </h1>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Discussion Button -->
        @if (Auth::user()->role == 'admin')
            <button onclick="openDiscussionModal()"
                class="relative text-gray-600 transition-colors hover:text-green-600 focus:outline-none">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span id="discussionBadge"
                    class="absolute -right-1 -top-1 hidden h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white shadow-md">
                    0
                </span>
            </button>
        @endif


        <!-- User Dropdown -->
        <div class="group relative">
            <button class="flex items-center space-x-3 focus:outline-none">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-emerald-600 shadow-md">
                    <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </div>

                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
                class="invisible absolute right-0 z-50 mt-2 w-56 rounded-lg border border-green-100 bg-white opacity-0 shadow-xl transition-all duration-200 group-hover:visible group-hover:opacity-100">
                <div class="p-2">
                    <!-- User Info -->
                    <div class="border-b border-gray-100 px-4 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="mt-0.5 text-xs capitalize text-gray-500">{{ Auth::user()->role }}</p>
                    </div>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center rounded-md px-4 py-2 text-sm text-red-600 transition-colors hover:bg-red-50">
                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Header Discussion Modal -->
<div id="headerDiscussionModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
    <div class="flex h-[90vh] w-full max-w-4xl transform flex-col rounded-xl bg-white shadow-2xl"
        onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div
            class="flex items-center justify-between rounded-t-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
            <div>
                <h3 class="text-xl font-bold text-white">Diskusi dengan Super Admin</h3>
                <p class="mt-1 text-sm text-green-50">Tanyakan atau diskusikan sesuatu</p>
            </div>
            <button onclick="closeHeaderDiscussionModal()" class="text-white transition-colors hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Discussion List -->
            <div class="w-1/3 rounded-bl-xl border-r border-gray-200 bg-gray-50">
                <div class="border-b border-gray-200 p-4">
                    <button onclick="showHeaderNewDiscussionForm()"
                        class="w-full rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-600">
                        <svg class="mr-2 inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Diskusi Baru
                    </button>
                </div>
                <div id="headerDiscussionList" class="overflow-y-auto" style="height: calc(90vh - 170px);">
                    <div class="p-4 text-center text-sm text-gray-500">
                        Memuat diskusi...
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex flex-1 flex-col">
                <!-- New Discussion Form (Hidden by default) -->
                <div id="headerNewDiscussionForm" class="hidden flex-1 flex-col p-6">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">Buat Diskusi Baru</h4>
                    <input type="text" id="headerDiscussionSubject" placeholder="Subjek diskusi..."
                        class="mb-4 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                    <textarea id="headerDiscussionMessage" style="resize: none;" placeholder="Tulis pesan Anda..."
                        class="mb-4 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"></textarea>
                    <div class="flex gap-2">
                        <button onclick="createHeaderDiscussion()"
                            class="rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-600">
                            Kirim Diskusi
                        </button>
                        <button onclick="hideHeaderNewDiscussionForm()"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- Chat View (Hidden by default) -->
                <div id="headerChatView" class="hidden flex-1 flex-col">
                    <div class="border-b border-gray-200 p-4">
                        <h4 id="headerChatSubject" class="font-semibold text-gray-800"></h4>
                    </div>
                    <div id="headerChatMessages" class="flex-1 overflow-y-auto p-4"
                        style="height: calc(90vh - 220px);">
                    </div>
                    <div class="border-t border-gray-200 p-4">
                        <div class="flex gap-2">
                            <textarea id="headerMessageInput" rows="1" placeholder="Ketik pesan..." style="resize: none;"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                                onkeypress="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); sendHeaderMessage(); }"></textarea>
                            <button onclick="sendHeaderMessage()"
                                class="rounded-lg bg-green-500 px-4 text-white transition-colors hover:bg-green-600">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="headerEmptyState" class="flex flex-1 items-center justify-center p-6">
                    <div class="text-center">
                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-500">Pilih diskusi atau mulai yang baru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        discussionManager.init({{ Auth::id() }}, '{{ csrf_token() }}');


        discussionManager.onUnreadCountsUpdated = (unreadByAdmin, totalUnread) => {
            updateHeaderBadge(totalUnread);
        };

        discussionManager.onDiscussionsUpdated = (discussions) => {
            const headerModal = document.getElementById('headerDiscussionModal');
            const isHeaderOpen = headerModal && !headerModal.classList.contains('hidden');

            if (isHeaderOpen && !discussionManager.getCurrentAdmin()) {
                renderHeaderDiscussions(discussions);
            }
        };

        discussionManager.onNewMessageReceived = (newMessages) => {
            const headerModal = document.getElementById('headerDiscussionModal');
            const isHeaderOpen = headerModal && !headerModal.classList.contains('hidden');
            const chatView = document.getElementById('headerChatView');
            const isChatOpen = chatView && !chatView.classList.contains('hidden');

            if (isHeaderOpen && isChatOpen) {
                appendHeaderMessages(newMessages);
            }
        };
    });

    // ============================================
    // MODAL CONTROLS
    // ============================================

    function openDiscussionModal() {
        const modal = document.getElementById('headerDiscussionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        discussionManager.currentAdminId = null;

        discussionManager.updateAll();
    }

    function closeHeaderDiscussionModal() {
        const modal = document.getElementById('headerDiscussionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';

        // Clear discussion state (but keep polling for badges)
        discussionManager.currentDiscussionId = null;
        discussionManager.lastMessageId = 0;
    }

    // ============================================
    // DISCUSSIONS LIST
    // ============================================

    function renderHeaderDiscussions(discussions) {
        const listHtml = discussions.map(d => `
        <div onclick="openHeaderChat(${d.id})"
            class="cursor-pointer border-b border-gray-200 p-4 transition-colors hover:bg-gray-100 ${discussionManager.getCurrentDiscussion() === d.id ? 'bg-green-50' : ''}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h5 class="font-medium text-gray-800">${d.subject}</h5>
                    <p class="mt-1 text-xs text-gray-500">
                        ${d.latest_message ? d.latest_message.message.substring(0, 50) + '...' : 'Belum ada pesan'}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        ${discussionManager.formatDate(d.last_message_at)}
                    </p>
                </div>
                ${d.unread_count > 0 ? `
                    <span class="ml-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white">
                        ${d.unread_count}
                    </span>
                ` : ''}
            </div>
        </div>
    `).join('');

        document.getElementById('headerDiscussionList').innerHTML = listHtml ||
            '<div class="p-4 text-center text-sm text-gray-500">Belum ada diskusi</div>';
    }

    // ============================================
    // BADGE UPDATE
    // ============================================

    function updateHeaderBadge(count) {
        const badge = document.getElementById('discussionBadge');
        if (!badge) return;

        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            badge.classList.add('flex');
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('flex');
        }
    }

    // ============================================
    // NEW DISCUSSION FORM
    // ============================================

    function showHeaderNewDiscussionForm() {
        document.getElementById('headerEmptyState').classList.add('hidden');
        document.getElementById('headerChatView').classList.add('hidden');
        document.getElementById('headerNewDiscussionForm').classList.remove('hidden');
    }

    function hideHeaderNewDiscussionForm() {
        document.getElementById('headerNewDiscussionForm').classList.add('hidden');
        document.getElementById('headerEmptyState').classList.remove('hidden');
        document.getElementById('headerDiscussionSubject').value = '';
        document.getElementById('headerDiscussionMessage').value = '';
    }

    async function createHeaderDiscussion() {
        const subject = document.getElementById('headerDiscussionSubject').value.trim();
        const message = document.getElementById('headerDiscussionMessage').value.trim();

        if (!subject || !message) {
            alert('Mohon isi subjek dan pesan');
            return;
        }

        const discussion = await discussionManager.createDiscussion(null, subject, message);

        if (discussion) {
            document.getElementById('headerDiscussionSubject').value = '';
            document.getElementById('headerDiscussionMessage').value = '';
            hideHeaderNewDiscussionForm();
            openHeaderChat(discussion.id);
        } else {
            alert('Gagal membuat diskusi');
        }
    }

    // ============================================
    // CHAT VIEW
    // ============================================

    async function openHeaderChat(discussionId) {
        discussionManager.setCurrentDiscussion(discussionId);

        document.getElementById('headerEmptyState').classList.add('hidden');
        document.getElementById('headerNewDiscussionForm').classList.add('hidden');
        document.getElementById('headerChatView').classList.remove('hidden');

        const discussion = await discussionManager.fetchDiscussionById(discussionId);

        if (discussion) {
            document.getElementById('headerChatSubject').textContent = discussion.subject;
            renderHeaderMessages(discussion.messages);

            if (discussion.messages.length > 0) {
                discussionManager.lastMessageId = discussion.messages[discussion.messages.length - 1].id;
            }
        }
    }

    function renderHeaderMessages(messages) {
        const messagesHtml = messages.map(m => `
        <div class="mb-4 flex ${m.user_id === discussionManager.userId ? 'justify-end' : 'justify-start'}">
            <div class="max-w-[70%]">
                <div class="mb-1 flex items-center gap-2 ${m.user_id === discussionManager.userId ? 'flex-row-reverse' : ''}">
                    <span class="text-xs font-semibold text-gray-700">${m.user.name}</span>
                    <span class="text-xs text-gray-400">${discussionManager.formatTime(m.created_at)}</span>
                </div>
                <div class="rounded-lg px-4 py-2 ${m.user_id === discussionManager.userId ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-800'}">
                    ${discussionManager.escapeHtml(m.message)}
                </div>
            </div>
        </div>
    `).join('');

        document.getElementById('headerChatMessages').innerHTML = messagesHtml;
        scrollHeaderToBottom();
    }

    function appendHeaderMessages(newMessages) {
        const container = document.getElementById('headerChatMessages');
        if (!container) return;

        const messagesHtml = newMessages.map(m => `
        <div class="mb-4 flex ${m.user_id === discussionManager.userId ? 'justify-end' : 'justify-start'}">
            <div class="max-w-[70%]">
                <div class="mb-1 flex items-center gap-2 ${m.user_id === discussionManager.userId ? 'flex-row-reverse' : ''}">
                    <span class="text-xs font-semibold text-gray-700">${m.user.name}</span>
                    <span class="text-xs text-gray-400">${discussionManager.formatTime(m.created_at)}</span>
                </div>
                <div class="rounded-lg px-4 py-2 ${m.user_id === discussionManager.userId ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-800'}">
                    ${discussionManager.escapeHtml(m.message)}
                </div>
            </div>
        </div>
    `).join('');

        container.insertAdjacentHTML('beforeend', messagesHtml);
        scrollHeaderToBottom();
    }

    async function sendHeaderMessage() {
        const messageInput = document.getElementById('headerMessageInput');
        const message = messageInput.value.trim();

        if (!message || !discussionManager.getCurrentDiscussion()) return;

        const result = await discussionManager.sendMessage(
            discussionManager.getCurrentDiscussion(),
            message
        );

        if (result) {
            messageInput.value = '';
        }
    }

    function scrollHeaderToBottom() {
        const chatMessages = document.getElementById('headerChatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // ============================================
    // EVENT LISTENERS
    // ============================================

    // Close on outside click
    document.getElementById('headerDiscussionModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeHeaderDiscussionModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('headerDiscussionModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeHeaderDiscussionModal();
            }
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        discussionManager.cleanup();
    });
</script>
