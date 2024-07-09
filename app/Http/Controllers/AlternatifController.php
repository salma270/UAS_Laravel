<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlternatifController extends Controller
{
    /*
     * Constructor
     */

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.isi.alternatif.index', [
            'title' => 'Petshop',
            'alternatif' => Alternatif::orderBy('id_alternatif', 'DESC')->filter(request(['search']))->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alternatif = Alternatif::get();

        // ambil kode alternatif terakhir
        $lastKodeAlaternatif = Alternatif::orderBy('id_alternatif', 'DESC')->first();
        $newKodeAlternatif = $lastKodeAlaternatif ? ++$lastKodeAlaternatif->kode_alternatif : 'A1';

        return view('pages.isi.alternatif.create', [
            'title' => 'Tambah Petshop',
            'pluckAlternatif' => $alternatif->pluck('nama_alternatif')->toArray(),
            'newKodeAlternatif' => $newKodeAlternatif,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_alternatif' => 'required|unique:alternatif,kode_alternatif|max:3',
            'nama_alternatif' => 'required|unique:alternatif,nama_alternatif',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'alamat' => 'required',
            'rating' => 'required',
            'deskripsi' => 'required',
        ], [
            'kode_alternatif.required' => 'Kode alternatif harus diisi',
            'kode_alternatif.unique' => 'Kode alternatif sudah ada',
            'kode_alternatif.max' => 'Kode alternatif maksimal 3 karakter',
            'nama_alternatif.required' => 'Nama Petshop harus diisi',
            'nama_alternatif.unique' => 'Nama Petshop sudah ada',
            'jam_buka.required' => 'Jam buka harus diisi',
            'jam_tutup.required' => 'Jam tutup harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'rating.required' => 'Rating harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
            
        ]);

        try {
            Alternatif::create($validatedData);

            $notif = notify()->success('Data Petshop berhasil ditambahkan');
            return redirect()->route('alternatif.index')->withInput()->with('notif', $notif);
        } catch (\Throwable $th) {
            \Log::error('Error saving Petshop data: ' . $th->getMessage());
            $notif = notify()->error('Terjadi kesalahan saat menyimpan data Petshop');
            return back()->withInput()->with('notif', $notif);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('pages.isi.alternatif.show', [
            'title' => 'Detail Petshop',
            'alternatif' => Alternatif::where('id_alternatif', $id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pages.isi.alternatif.edit', [
            'title' => 'Ubah Petshop',
            'alternatif' => Alternatif::where('id_alternatif', $id)->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kode_alternatif' => 'required|max:3',
            'nama_alternatif' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'alamat' => 'required',
            'rating' => 'required',
            'deskripsi' => 'required',
        ], [
            'kode_alternatif.required' => 'Kode alternatif harus diisi',
            'kode_alternatif.max' => 'Kode alternatif maksimal 3 karakter',
            'nama_alternatif.required' => 'Nama Petshop harus diisi',
            'jam_buka.required' => 'Jam buka harus diisi',
            'jam_tutup.required' => 'Jam tutup harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'rating.required' => 'Rating harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
        ]);

        try {
            Alternatif::where('id_alternatif', $id)->update($validatedData);
            DB::commit();

            $notif = notify()->success('Data Petshop berhasil diubah');
            return redirect()->route('alternatif.index')->withInput()->with('notif', $notif);
        } catch (\Throwable $th) {
            $notif = notify()->error('Terjadi kesalahan saat mengubah data petshop');
            return back()->withInput()->with('notif', $notif);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Alternatif::where('id_alternatif', $id)->delete();

        $notif = notify()->success('Data Petshop berhasil dihapus');
        return back()->with('notif', $notif);
    }
}
