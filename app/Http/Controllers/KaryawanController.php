<?php


// ================================================================
// FILE: app/Http/Controllers/KaryawanController.php
// ================================================================
namespace App\Http\Controllers;
 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Departement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with(['jabatan', 'departement'])
                             ->orderBy('nama_lengkap')->paginate(15);
        return view('karyawan.index', compact('karyawans'));
    }
 
    public function create()
    {
        $jabatans    = Jabatan::orderBy('nama_jabatan')->get();
        $departements = Departement::orderBy('nama_departement')->get();
        return view('karyawan.create', compact('jabatans', 'departements'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nik'             => 'required|unique:karyawans,nik|max:20',
            'nama_lengkap'    => 'required|max:100',
            'email'           => 'required|email|unique:karyawans,email',
            'jabatan_id'      => 'required|exists:jabatans,id',
            'departement_id'  => 'required|exists:departements,id',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_masuk'   => 'required|date',
            'gaji_pokok'      => 'required|numeric|min:0',
            'password'        => 'required|min:6',
        ]);
 
        DB::transaction(function () use ($request) {
            // Buat akun user
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'karyawan',
            ]);
 
            // Buat data karyawan
            Karyawan::create([
                'user_id'        => $user->id,
                'jabatan_id'     => $request->jabatan_id,
                'departement_id' => $request->departement_id,
                'nik'            => $request->nik,
                'nama_lengkap'   => $request->nama_lengkap,
                'email'          => $request->email,
                'no_telp'        => $request->no_telp,
                'alamat'         => $request->alamat,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'tanggal_masuk'  => $request->tanggal_masuk,
                'gaji_pokok'     => $request->gaji_pokok,
                'status'         => 'aktif',
            ]);
        });
 
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }
 
    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['jabatan', 'departement', 'tunjangens', 'potongans']);
        return view('karyawan.show', compact('karyawan'));
    }
 
    public function edit(Karyawan $karyawan)
    {
        $jabatans     = Jabatan::orderBy('nama_jabatan')->get();
        $departements = Departement::orderBy('nama_departement')->get();
        return view('karyawan.edit', compact('karyawan', 'jabatans', 'departements'));
    }
 
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nik'             => 'required|max:20|unique:karyawans,nik,' . $karyawan->id,
            'nama_lengkap'    => 'required|max:100',
            'email'           => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'jabatan_id'      => 'required|exists:jabatans,id',
            'departement_id'  => 'required|exists:departements,id',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_masuk'   => 'required|date',
            'gaji_pokok'      => 'required|numeric|min:0',
            'status'          => 'required|in:aktif,nonaktif',
        ]);
 
        DB::transaction(function () use ($request, $karyawan) {
            $karyawan->update($request->except(['_token', '_method', 'password']));
 
            if ($request->filled('password') && $karyawan->user) {
                $karyawan->user->update(['password' => Hash::make($request->password)]);
            }
 
            if ($karyawan->user) {
                $karyawan->user->update([
                    'name'      => $request->nama_lengkap,
                    'is_active' => $request->status === 'aktif',
                ]);
            }
        });
 
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }
 
    public function destroy(Karyawan $karyawan)
    {
        DB::transaction(function () use ($karyawan) {
            if ($karyawan->user) {
                $karyawan->user->delete();
            }
            $karyawan->delete();
        });
 
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}
 