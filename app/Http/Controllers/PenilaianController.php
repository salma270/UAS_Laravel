<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PenilaianIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.isi.penilaian.index', [
            'title' => 'Data Penilaian',
            'penilaian' => Penilaian::with(['alternatif'])->orderBy('alternatif', 'ASC')->filter(request(['search']))->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function welcome()
    {
        return view('pages.isi.penilaian.index', [
            'title' => 'Penilaian',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alternatifPenilaianArray = Alternatif::orderBy('nama_alternatif', 'ASC')->get()->toArray();

        return view('pages.isi.penilaian.create', [
            'title' => 'Tambah Penilaian',
            'alternatif' => Alternatif::orderBy('nama_alternatif', 'ASC')->get(),
            'alternatifPenilaianArray' => $alternatifPenilaianArray,
            'kriteria' => Kriteria::with(['subkriteria', 'subkriteria.indikatorSubkriteria.skalaIndikator.skalaIndikatorDetail'])->orderBy('kode_kriteria', 'ASC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'alternatif' => 'required',
            'id_skala_indikator_detail' => 'required|array',
        ], [
            'alternatif.required' => 'Nama petshop harus dipilih',
            'id_skala_indikator_detail.required' => 'Skala harus diisi',
        ]);

        DB::beginTransaction();

        try {
            // Check if the evaluation for the selected pet shop already exists
            $penilaian = Penilaian::where('alternatif', $validatedData['alternatif'])->first();

            if ($penilaian) {
                $notif = notify()->warning('Penilaian untuk petshop ini sudah dilakukan. Silakan pilih petshop lain');
                return back()->withInput()->with('notif', $notif);
            }

            // Create new evaluation record
            $penilaian = Penilaian::create([
                'alternatif' => $validatedData['alternatif'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert indicator evaluations
            $penilaianIndikator = [];
            foreach ($validatedData['id_skala_indikator_detail'] as $value) {
                $penilaianIndikator[] = [
                    'id_penilaian' => $penilaian->id_penilaian,
                    'id_skala_indikator_detail' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PenilaianIndikator::insert($penilaianIndikator);

            DB::commit();

            $notif = notify()->success('Data penilaian berhasil disimpan');
            return back()->withInput()->with('notif', $notif);
        } catch (\Throwable $th) {
            DB::rollback();

            Log::error('Error saving data: ' . $th->getMessage());
            $notif = notify()->error('Terjadi kesalahan saat menyimpan data penilaian');
            return back()->withInput()->with('notif', $notif);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('pages.isi.penilaian.show', [
            'title' => 'Detail Data Penilaian',
            'kriteria' => Kriteria::with(['subkriteria', 'subkriteria.indikatorSubkriteria'])->orderBy('kode_kriteria', 'ASC')->get(),
            'penilaian' => Penilaian::with(['alternatif', 'penilaianIndikator', 'penilaianIndikator.skalaIndikatorDetail'])->where('id_penilaian', $id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
