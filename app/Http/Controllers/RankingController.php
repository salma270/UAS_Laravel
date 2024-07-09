<?php

namespace App\Http\Controllers;

use App\Models\BobotPrioritasAlternatif;
use App\Models\BobotPrioritasSubkriteria;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Ranking;
use App\Services\RankingService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    private $kriteria;
    private $bobotPrioritasSubkriteria;
    private $alternatifPenilaian;
    private $bobotAlternatif;
    private $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->kriteria = Kriteria::orderBy('kode_kriteria', 'asc')->get();
        $this->bobotAlternatif = BobotPrioritasAlternatif::orderBy('kode_kriteria', 'asc')->get();
        $this->bobotPrioritasSubkriteria = BobotPrioritasSubkriteria::with(['kriteria'])->get();
        $this->rankingService = $rankingService;
    }

    public function index()
    {
        // Get all evaluations regardless of status
        $alternatifPenilaian = Penilaian::with([
            'penilaianIndikator',
            'penilaianIndikator.skalaIndikatorDetail.skalaIndikator.indikatorSubkriteria',
            'penilaianIndikator.skalaIndikatorDetail.nilaiSkala',
            'alternatifKedua',
            'alternatifKedua.alternatifPertama'
        ])->get(); // Removed the where condition for status

        $kriteria = $this->kriteria;
        $bobotAlternatif = $this->bobotAlternatif;
        $bobotPrioritasSubkriteria = $this->bobotPrioritasSubkriteria;

        $uniqueAlternatifPenilaian = $alternatifPenilaian->unique('alternatif_kedua');

        // Calculate totalBobotKriteria
        $totalBobotKriteria = $this->rankingService->totalBobotKriteria($uniqueAlternatifPenilaian, $kriteria, $bobotPrioritasSubkriteria, $bobotAlternatif);
        
        // Debugging: Output the totalBobotKriteria
        \Log::info('Total Bobot Kriteria: ', $totalBobotKriteria);

        $bobotKriteria = [];
        foreach ($kriteria as $kriteriaItem) {
            $bobotKriteria[$kriteriaItem->kode_kriteria] = $kriteriaItem->bobot_kriteria;
        }

        $getCountSubkriteria = Kriteria::with(['subkriteria'])->get();
        $countSubkriteria = [];
        foreach ($getCountSubkriteria as $kriteriaItem) {
            $countSubkriteria[$kriteriaItem->kode_kriteria] = $kriteriaItem->subkriteria->count();
        }

        $getCountIndikator = Kriteria::with(['subkriteria', 'subkriteria.indikatorSubkriteria'])->get();
        $countIndikator = [];
        foreach ($getCountIndikator as $kodeKriteria => $kriteriaItem) {
            $kodeKriteria = $kriteriaItem->kode_kriteria;
            foreach ($kriteriaItem->subkriteria as $subkriteriaItem) {
                $countIndikator[$kodeKriteria][$subkriteriaItem->kode_subkriteria] = $subkriteriaItem->indikatorSubkriteria->count();
            }
        }

        $bobotSubkriteria = [];
        foreach ($getCountIndikator as $kodeKriteria => $kriteriaItem) {
            $kodeKriteria = $kriteriaItem->kode_kriteria;
            $bobotKriteriaItem = $bobotKriteria[$kodeKriteria];
            foreach ($kriteriaItem->subkriteria as $subkriteriaItem) {
                $bobotSubkriteria[$kodeKriteria][$subkriteriaItem->kode_subkriteria] = $subkriteriaItem->bobot_subkriteria / 100;
            }
        }

        $totalBobotSubkriteriaIndikator = [];
        foreach ($bobotSubkriteria as $kodeKriteria => $kriteriaItem) {
            foreach ($kriteriaItem as $kodeSubkriteria => $subkriteriaItem) {
                $totalBobotSubkriteriaIndikator[$kodeKriteria][$kodeSubkriteria] = $subkriteriaItem / $countIndikator[$kodeKriteria][$kodeSubkriteria];
            }
        }

        $alternatifPenilaianData = [];
        foreach ($alternatifPenilaian as $alternatifPenilaianItem) {
            if ($alternatifPenilaianItem->alternatifKedua && $alternatifPenilaianItem->alternatifKedua->alternatifPertama) {
                $alternatifKedua = $alternatifPenilaianItem->alternatifKedua->alternatifPertama;
                $penilaianIndikator = $alternatifPenilaianItem->penilaianIndikator;
                if ($alternatifKedua) {
                    $alternatifPenilaianData[$alternatifKedua->kode_alternatif] = $penilaianIndikator;
                }
            }
        }

        $nilaiSkala = [];
        foreach ($alternatifPenilaianData as $kodeAlternatif => $penilaianIndikatorItem) {
            foreach ($penilaianIndikatorItem as $penilaianIndikatorSubkriteriaItem) {
                $nilaiSkala[$kodeAlternatif][$penilaianIndikatorSubkriteriaItem->skalaIndikatorDetail->skalaIndikator->indikatorSubkriteria->subkriteria->kode_kriteria][$penilaianIndikatorSubkriteriaItem->skalaIndikatorDetail->skalaIndikator->indikatorSubkriteria->kode_subkriteria][] = $penilaianIndikatorSubkriteriaItem->skalaIndikatorDetail->nilaiSkala->nilai_skala;
            }
        }

        $totalBobotIndikator = [];
        foreach ($nilaiSkala as $kodeAlternatif => $alternatifPenilaianItem) {
            foreach ($alternatifPenilaianItem as $kodeKriteria => $kriteriaItem) {
                foreach ($kriteriaItem as $kodeSubkriteria => $subkriteriaItem) {
                    foreach ($subkriteriaItem as $kodeIndikator => $nilaiSkalaItem) {
                        $totalBobotIndikator[$kodeAlternatif][$kodeKriteria][$kodeSubkriteria][$kodeIndikator] = array_map(function ($value) use ($totalBobotSubkriteriaIndikator, $kodeKriteria, $kodeSubkriteria) {
                            return $totalBobotSubkriteriaIndikator[$kodeKriteria][$kodeSubkriteria] * $value;
                        }, $nilaiSkalaItem);
                    }
                }
            }
        }

        $sumNilaiSubkriteria = [];
        foreach ($totalBobotIndikator as $kodeAlternatif => $alternatifPenilaianItem) {
            foreach ($alternatifPenilaianItem as $kodeKriteria => $kriteriaItem) {
                foreach ($kriteriaItem as $kodeSubkriteria => $subkriteriaItem) {
                    $sumNilaiSubkriteria[$kodeAlternatif][$kodeKriteria][$kodeSubkriteria] = array_sum($subkriteriaItem);
                }
            }
        }

        $totalNilaiSubkriteria = [];
        foreach ($sumNilaiSubkriteria as $kodeAlternatif => $alternatifPenilaianItem) {
            foreach ($alternatifPenilaianItem as $kodeKriteria => $kriteriaItem) {
                foreach ($kriteriaItem as $kodeSubkriteria => $subkriteriaItem) {
                    $totalNilaiSubkriteria[$kodeAlternatif][$kodeKriteria][$kodeSubkriteria] = array_sum($subkriteriaItem);
                }
            }
        }

        $countAlternatif = [];
        foreach ($totalNilaiSubkriteria as $kodeAlternatif => $alternatifPenilaianItem) {
            foreach ($alternatifPenilaianItem as $kodeKriteria => $kriteriaItem) {
                if (!isset($countAlternatif[$kodeKriteria])) {
                    $countAlternatif[$kodeKriteria] = 0;
                }
                $countAlternatif[$kodeKriteria]++;
            }
        }

        $avgNilaiKriteria = [];
        foreach ($totalNilaiSubkriteria as $kodeAlternatif => $nilaiKriteria) {
            foreach ($nilaiKriteria as $kodeSubkriteria => $nilai) {
                if (!isset($avgNilaiKriteria[$kodeAlternatif][$kodeSubkriteria])) {
                    $avgNilaiKriteria[$kodeAlternatif][$kodeSubkriteria] = 0;
                }
                foreach ($nilai as $nilaiItem) {
                    $avgNilaiKriteria[$kodeAlternatif][$kodeSubkriteria] += $nilaiItem / $countAlternatif[$kodeAlternatif];
                }
            }
        }

        $totalNilaiKriteriaAlternatif = [];
        foreach ($avgNilaiKriteria as $kodeAlternatif => $nilaiKriteriaItem) {
            $totalNilaiKriteriaAlternatif[$kodeAlternatif] = array_sum($nilaiKriteriaItem);
        }

        $totalNilaiAlternatif = [];
        foreach ($totalNilaiKriteriaAlternatif as $kodeAlternatif => $nilai) {
            if (isset($totalBobotKriteria[$kodeAlternatif])) {
                $totalNilaiAlternatif[$kodeAlternatif] = $nilai + $totalBobotKriteria[$kodeAlternatif];
            }
        }

        arsort($totalNilaiAlternatif);

        $rank = 0;
        $prevTotal = 0;
        $rankAlternatif = [];
        foreach ($totalNilaiAlternatif as $kodeAlternatif => $totalNilaiAlternatifItem) {
            if ($totalNilaiAlternatifItem != $prevTotal) {
                $rank++;
            }
            $rankAlternatif[$kodeAlternatif] = $rank;
            $prevTotal = $totalNilaiAlternatifItem;
        }

        $totalNilaiAlternatifAndRankAlternatif = [];
        foreach ($totalNilaiAlternatif as $kodeAlternatif => $totalNilaiAlternatifItem) {
            $totalNilaiAlternatifAndRankAlternatif[$kodeAlternatif] = [
                'nilai' => $totalNilaiAlternatifItem,
                'rank' => $rankAlternatif[$kodeAlternatif],
            ];
        }

        // Debugging: Output avgNilaiKriteria, totalNilaiAlternatif, and rankAlternatif
        \Log::info('Avg Nilai Kriteria: ', $avgNilaiKriteria);
        \Log::info('Total Nilai Alternatif: ', $totalNilaiAlternatif);
        \Log::info('Rank Alternatif: ', $rankAlternatif);

        try {
            foreach ($totalNilaiAlternatifAndRankAlternatif as $kodeAlternatif => $totalNilaiAlternatifAndRankAlternatifItem) {
                Ranking::updateOrCreate(
                    [
                        'kode_alternatif' => $kodeAlternatif,
                    ],
                    [
                        'nilai' => $totalNilaiAlternatifAndRankAlternatifItem['nilai'],
                        'rank' => $totalNilaiAlternatifAndRankAlternatifItem['rank'],
                    ]
                );
            }
        } catch (\Throwable $th) {
            $notif = notify()->error('Terjadi kesalahan saat menyimpan data perankingan');
            return back()->with('notif', $notif);
        }

        return view('pages.perhitungan.ranking.index', [
            'title' => 'Perankingan',
            'alternatifPenilaian' => $uniqueAlternatifPenilaian,
            'kriteria' => $kriteria,
            'bobotAlternatif' => $bobotAlternatif,
            'bobotPrioritasSubkriteria' => $bobotPrioritasSubkriteria,
            'totalBobotKriteria' => $totalBobotKriteria,
            'avgNilaiKriteria' => $avgNilaiKriteria,
            'nilaiAlternatif' => $totalNilaiAlternatif,
            'rankAlternatif' => $rankAlternatif,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
