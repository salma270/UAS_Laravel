<?php

namespace App\Jobs;

use App\Models\BobotPrioritasAlternatif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BobotPrioritasAlternatifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bobotPrioritasAlternatif;

    /**
     * Create a new job instance.
     */
    public function __construct($bobotPrioritasAlternatif)
    {
        $this->bobotPrioritasAlternatif = $bobotPrioritasAlternatif;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->storeData();
        } catch (\Throwable $th) {
            // Handle errors if they occur
            \Log::error('Failed to save bobot prioritas alternatif data: ' . $th->getMessage());

            // Throw an exception to indicate that an error occurred
            throw new \Exception('Failed to save bobot prioritas alternatif data');
        }
    }

    /**
     * Store the bobot prioritas alternatif data.
     */
    private function storeData(): void
    {
        foreach ($this->bobotPrioritasAlternatif as $kodeKriteria => $matriksKriteria) {
            foreach ($matriksKriteria as $dataAlternatif => $bobotPrioritas) {
                \Log::info("Saving Bobot Prioritas: Kriteria - $kodeKriteria, Alternatif - $dataAlternatif, Bobot - $bobotPrioritas");
                BobotPrioritasAlternatif::updateOrCreate(
                    [
                        'kode_kriteria' => $kodeKriteria,
                        'kode_alternatif' => $dataAlternatif,
                    ],
                    ['bobot_prioritas' => $bobotPrioritas]
                );
            }
        }
    }
}
