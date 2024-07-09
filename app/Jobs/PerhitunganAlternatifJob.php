<?php

namespace App\Jobs;

use App\Models\PerhitunganAlternatif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PerhitunganAlternatifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $matriks;

    public function __construct(array $matriks) {
        $this->matriks = $matriks;
    }

    public function handle() {
        foreach ($this->matriks as $kodeKriteria => $dataKriteria) {
            foreach ($dataKriteria as $alternatifPertama => $dataAlternatif) {
                foreach ($dataAlternatif as $alternatifKedua => $nilai) {
                    PerhitunganAlternatif::updateOrCreate(
                        [
                            'kode_kriteria' => $kodeKriteria,
                            'alternatif_pertama' => $alternatifPertama,
                            'alternatif_kedua' => $alternatifKedua,
                        ],
                        [
                            'nilai_alternatif' => $nilai,
                        ]
                    );
                }
            }
        }
    }
}
