<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';
    protected $guarded = ['id_penilaian'];
    protected $primaryKey = 'id_penilaian';

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->whereHas('alternatif', function ($query) use ($search) {
                $query->where('nama_alternatif', 'like', '%' . $search . '%');
            });
        });
    }

    public function penilaianIndikator()
    {
        return $this->hasMany(PenilaianIndikator::class, 'id_penilaian', 'id_penilaian');
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif', 'kode_alternatif');
    }

    public function alternatifKedua()
    {
        return $this->belongsTo(Alternatif::class, 'alte', 'kode_alternatif');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
    public function perhitunganAlternatif()
    {
        return $this->hasMany(PerhitunganAlternatif::class, 'kode_kriteria', 'kode_kriteria');
    }
}
