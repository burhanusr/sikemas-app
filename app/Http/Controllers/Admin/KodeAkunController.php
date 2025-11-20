<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KodeAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KodeAkunController extends Controller
{
    public function index()
    {
        $kodeAkun = KodeAkun::where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('admin.kode-akun', compact('kodeAkun'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|string|max:50|unique:kode_akun,kode_akun,NULL,id,user_id,' . Auth::id(),
            'nama_akun' => 'required|string|max:50',
            'kategori_akun' => 'required|string|max:50',
        ], [
            'kode_akun.required' => 'Kode akun harus diisi',
            'kode_akun.unique' => 'Kode akun sudah digunakan',
            'nama_akun.required' => 'Nama akun harus diisi',
            'kategori_akun.required' => 'Kategori akun harus dipilih',
        ]);

        KodeAkun::create([
            'user_id' => Auth::id(),
            'kode_akun' => $validated['kode_akun'],
            'nama_akun' => $validated['nama_akun'],
            'kategori_akun' => $validated['kategori_akun'],
        ]);

        return redirect()
            ->route('kode-akun.index')
            ->with('success', 'Kode akun berhasil ditambahkan');
    }

    public function update(Request $request, KodeAkun $kodeAkun)
    {
        // Check if user owns this record
        if ($kodeAkun->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'kode_akun' => 'required|string|max:50|unique:kode_akun,kode_akun,' . $kodeAkun->id . ',id,user_id,' . Auth::id(),
            'nama_akun' => 'required|string|max:50',
            'kategori_akun' => 'required|string|max:50',
        ], [
            'kode_akun.required' => 'Kode akun harus diisi',
            'kode_akun.unique' => 'Kode akun sudah digunakan',
            'nama_akun.required' => 'Nama akun harus diisi',
            'kategori_akun.required' => 'Kategori akun harus dipilih',
        ]);

        $kodeAkun->update($validated);

        return redirect()
            ->route('kode-akun.index')
            ->with('success', 'Kode akun berhasil diperbarui');
    }

    public function destroy(KodeAkun $kodeAkun)
    {
        // Check if user owns this record
        if ($kodeAkun->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kodeAkun->delete();

        return redirect()
            ->route('kode-akun.index')
            ->with('success', 'Kode akun berhasil dihapus');
    }
}
