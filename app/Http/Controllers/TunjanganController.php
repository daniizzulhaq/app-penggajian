<?php

namespace App\Http\Controllers;
 
use App\Models\Tunjangan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
 
class TunjanganController extends Controller
{
    public function index()
    {
        $tunjangens = Tunjangan::with('karyawan')->orderBy('created_at', 'desc')->paginate(20);
        $karyawans  = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        return view('tunjangan.index', compact('tunjangens', 'karyawans'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'   => 'required|exists:karyawans,id',
            'nama_tunjangan'=> 'required|max:100',
            'nominal'       => 'required|numeric|min:0',
            'jenis'         => 'required|in:tetap,tidak_tetap',
        ]);
        Tunjangan::create($request->only('karyawan_id', 'nama_tunjangan', 'nominal', 'jenis'));
        return back()->with('success', 'Tunjangan berhasil ditambahkan!');
    }
 
    public function destroy(Tunjangan $tunjangan)
    {
        $tunjangan->delete();
        return back()->with('success', 'Tunjangan berhasil dihapus!');
    }
}
