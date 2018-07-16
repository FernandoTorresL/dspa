<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valija extends Model {
    protected $table = 'ctas_valijas';
    protected $primaryKey = 'id_valija';
    protected $fillable = ['num_oficio_ca', 'fecha_recepcion_ca', 'delegacion', 'num_oficio_del', 'fecha_valija_del', 'comentario', 'archivo'];
}