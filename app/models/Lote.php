<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model {
    protected $table = 'ctas_lotes';
    protected $fillable = ['lote_anio', 'comentario'];
}