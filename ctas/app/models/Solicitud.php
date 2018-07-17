<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model {
    protected $table = 'ctas_solicitudes';
    protected $primaryKey = 'id_solicitud';
    protected $fillable = [
        'id_valija',
        'fecha_solicitud_del',
        'id_lote',
        'delegacion',
        'subdelegacion',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'matricula',
        'curp',
        'usuario',
        'id_movimiento',
        'id_grupo_nuevo',
        'id_grupo_actual',
        'comentario',
        'archivo',
        'id_user'
    ];

    public function valija() {
        return $this->belongsTo('App\Models\Valija', 'id_valija', 'id_valija');
    }

    public function delegacion() {
        return $this->belongsTo('App\Models\Delegacion', 'delegacion', 'delegacion');
    }
}
