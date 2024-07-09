<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganAlternatif extends Model
{
    use HasFactory;

    protected $table = 'perhitungan_alternatif';
    protected $primaryKey = 'id_perhitungan_alternatif';
    protected $fillable = [
        'kode_kriteria', 
        'alternatif_pertama', 
        'alternatif_kedua', 
        'nilai_alternatif', 
        'created_at', 
        'updated_at'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Alternatif::class, 'kode_kriteria', 'kode_kriteria');
    }
    public function alternatifPertama()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_pertama', 'kode_alternatif');
    }
    public function alternatifKedua()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_kedua', 'kode_alternatif');
    }
}
