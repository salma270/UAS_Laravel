<?php

// Controller for managing pairwise comparisons
namespace App\Http\Controllers;

use App\Jobs\PerhitunganAlternatifJob;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\PerhitunganAlternatif;
use App\Services\PerhitunganAlternatifService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PerhitunganAlternatifController extends Controller
{
    private $kriteria;
    private $alternatif;
    private $perhitunganAlternatif;
    private $perhitunganAlternatifService;

    public function __construct(PerhitunganAlternatifService $perhitunganAlternatifService) {
        $this->kriteria = Kriteria::orderBy('kode_kriteria', 'asc')->get();
        $this->alternatif = Alternatif::orderBy('kode_alternatif', 'asc')->get();
        $this->perhitunganAlternatif = PerhitunganAlternatif::with('alternatifPertama', 'alternatifKedua')->orderBy('alternatif_pertama', 'asc');
        $this->perhitunganAlternatifService = $perhitunganAlternatifService;
    }

    public function introduction() {
        $perhitunganAlternatif = $this->perhitunganAlternatif->get();
        return view('pages.perhitungan.perhitungan-alternatif.introduction', [
            'title' => 'Perbandingan Petshop',
            'perhitunganAlternatif' => $perhitunganAlternatif,
        ]);
    }

    public function index() {
        $perhitunganAlternatif = $this->perhitunganAlternatif->get();
        return view('pages.perhitungan.perhitungan-alternatif.index', [
            'title' => 'Perbandingan Petshop',
            'kriteria' => $this->kriteria,
            'alternatif' => $this->alternatif,
            'perhitunganAlternatif' => $perhitunganAlternatif,
        ]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate(['matriks' => 'required|array']);
        $matriks = $validatedData['matriks'];
        PerhitunganAlternatifJob::dispatch($matriks);
        return redirect()->route('perhitunganAlternatif.hasil')->with('success', 'Data perbandingan petshop berhasil disimpan');
    }

    public function hasil() {
        $result = Cache::remember('hasil_perhitungan_alternatif', 60, function () {
            $kriteria = $this->kriteria;
            $perhitunganAlternatif = $this->perhitunganAlternatif->get();
            $totalKolomAlternatif = $this->perhitunganAlternatifService->totalKolomAlternatif($perhitunganAlternatif);
            $normalisasiMatriks = $this->perhitunganAlternatifService->normalisasiMatriks($perhitunganAlternatif, $totalKolomAlternatif);
            $totalBarisNormalisasiMatriks = $this->perhitunganAlternatifService->totalBarisNormalisasiMatriks($normalisasiMatriks);
            $bobotPrioritasAlternatif = $this->perhitunganAlternatifService->bobotPrioritasAlternatif($totalBarisNormalisasiMatriks);
            return [
                'alternatif' => $this->alternatif,
                'kriteria' => $kriteria,
                'perhitunganAlternatif' => $perhitunganAlternatif,
                'totalKolomAlternatif' => $totalKolomAlternatif,
                'normalisasiMatriks' => $normalisasiMatriks,
                'bobotPrioritasAlternatif' => $bobotPrioritasAlternatif,
            ];
        });
        return view('pages.perhitungan.perhitungan-alternatif.hasil', array_merge(['title' => 'Hasil Perbandingan Petshop'], $result));
    }

    // Other methods...

    public function show(PerhitunganAlternatif $perhitunganAlternatif) { }

    public function edit(PerhitunganAlternatif $perhitunganAlternatif) { }

    public function update(Request $request, PerhitunganAlternatif $perhitunganAlternatif) { }

    public function destroy(PerhitunganAlternatif $perhitunganAlternatif) { }
}

