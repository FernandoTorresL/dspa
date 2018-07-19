<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud2 extends Model {
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

    public function del() {
        return $this->belongsTo('App\Models\Delegacion', 'delegacion', 'delegacion');
    }

    public function subdel() {
        return $this->belongsTo('App\Models\Subdelegacion', 'delegacion','subddelegacion');
    }

    public function movimiento() {
        return $this->belongsTo('App\Models\Movimiento', 'id_movimiento','id_movimiento');
    }

    public function gponuevo() {
        return $this->belongsTo('App\Models\Grupo', 'id_grupo_nuevo','id_grupo');
    }

    public function gpoactual() {
        return $this->belongsTo('App\Models\Grupo', 'id_grupo_actual','id_grupo');
    }
}
