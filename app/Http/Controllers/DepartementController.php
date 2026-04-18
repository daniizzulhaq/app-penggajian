<?php

namespace App\Http\Controllers;
 
use App\Models\Departement;
use Illuminate\Http\Request;
 
class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::withCount('karyawans')->orderBy('nama_departement')->get();
        return view('departement.index', compact('departements'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nama_departement' => 'required|max:100',
            'kode_departement' => 'required|max:10|unique:departements,kode_departement',
        ]);
        Departement::create($request->only('nama_departement', 'kode_departement'));
        return back()->with('success', 'Departemen berhasil ditambahkan!');
    }
 
    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'nama_departement' => 'required|max:100',
            'kode_departement' => 'required|max:10|unique:departements,kode_departement,' . $departement->id,
        ]);
        $departement->update($request->only('nama_departement', 'kode_departement'));
        return back()->with('success', 'Departemen berhasil diperbarui!');
    }
 
    public function destroy(Departement $departement)
    {
        if ($departement->karyawans()->count() > 0) {
            return back()->with('error', 'Departemen masih digunakan oleh karyawan!');
        }
        $departement->delete();
        return back()->with('success', 'Departemen berhasil dihapus!');
    }
}
 
