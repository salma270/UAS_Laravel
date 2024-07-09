<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Ranking extends Model
{
    use HasFactory, Sortable;

    protected $table = 'ranking';
    protected $guarded = ['id_ranking'];
    protected $primaryKey = 'id_ranking';

    public $sortable = [
        'kode_alternatif',
        'nilai',
        'rank',
    ];

}
