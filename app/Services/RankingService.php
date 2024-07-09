<?php

namespace App\Services;

use Illuminate\Http\Request;

class RankingService
{
    public function totalBobotKriteria($uniqueAlternatifPenilaian, $kriteria, $bobotPrioritasSubkriteria, $bobotAlternatif)
    {
        $totalBobotKriteria = [];
        foreach ($uniqueAlternatifPenilaian as $alternatifItem) {
            $total = 0;

            foreach ($kriteria as $kriteriaItem) {
                $bobotPrioritasSubkriteriaItem = $bobotPrioritasSubkriteria->where('kriteria.kode_kriteria', $kriteriaItem->kode_kriteria)->first();

                if ($alternatifItem->alternatifKedua && $alternatifItem->alternatifKedua->alternatifPertama) {
                    $bobotPrioritasAlternatifItem = $bobotAlternatif->where('kode_kriteria', $kriteriaItem->kode_kriteria)->where('kode_alternatif', $alternatifItem->alternatifKedua->alternatifPertama->kode_alternatif)->first();

                    if ($bobotPrioritasSubkriteriaItem && $bobotPrioritasAlternatifItem) {
                        $total += ($bobotPrioritasSubkriteriaItem->bobot_prioritas * $bobotPrioritasAlternatifItem->bobot_prioritas);
                    }

                    $totalBobotKriteria[$alternatifItem->alternatifKedua->alternatifPertama->kode_alternatif] = substr($total, 0, 6);
                }
            }
        }

        return $totalBobotKriteria;
    }
}
