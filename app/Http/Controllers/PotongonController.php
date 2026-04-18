<?php

namespace App\Http\Controllers;
 
use App\Models\Potongan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
 
class PotongonController extends Controller
{
    public function index()
    {
        $potongans  = Potongan::with('karyawan')->orderBy('created_at', 'desc')->paginate(20);
        $karyawans  = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        return view('potongan.index', compact('potongans', 'karyawans'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'  => 'required|exists:karyawans,id',
            'nama_potongan'=> 'required|max:100',
            'nominal'      => 'required|numeric|min:0',
            'jenis'        => 'required|in:bpjs_kes,bpjs_tk,pph21,lainnya',
        ]);
        Potongan::create($request->only('karyawan_id', 'nama_potongan', 'nominal', 'jenis'));
        return back()->with('success', 'Potongan berhasil ditambahkan!');
    }
 
    public function destroy(Potongan $potongan)
    {
        $potongan->delete();
        return back()->with('success', 'Potongan berhasil dihapus!');
    }
}
 