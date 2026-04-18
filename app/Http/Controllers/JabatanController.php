<?php

namespace App\Http\Controllers;
 
use App\Models\Jabatan;
use Illuminate\Http\Request;
 
class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::withCount('karyawans')->orderBy('nama_jabatan')->get();
        return view('jabatan.index', compact('jabatans'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|max:100|unique:jabatans,nama_jabatan',
            'gaji_pokok'   => 'required|numeric|min:0',
        ]);
        Jabatan::create($request->only('nama_jabatan', 'gaji_pokok'));
        return back()->with('success', 'Jabatan berhasil ditambahkan!');
    }
 
    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|max:100|unique:jabatans,nama_jabatan,' . $jabatan->id,
            'gaji_pokok'   => 'required|numeric|min:0',
        ]);
        $jabatan->update($request->only('nama_jabatan', 'gaji_pokok'));
        return back()->with('success', 'Jabatan berhasil diperbarui!');
    }
 
    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->karyawans()->count() > 0) {
            return back()->with('error', 'Jabatan tidak bisa dihapus karena masih dipakai karyawan!');
        }
        $jabatan->delete();
        return back()->with('success', 'Jabatan berhasil dihapus!');
    }
}
