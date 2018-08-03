<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_ctas extends Model {
    protected $table = 'Detalle_ctas';

    public function inventario() {
        return $this->belongsTo('App\Models\Inventario');
    }

    public function gpo_owner() {
        return $this->belongsTo('App\Models\Grupo', 'gpo_owner_id', 'id_grupo');
    }

    public function area() {
        return $this->belongsTo('App\Models\Area');
    }

    public function ciz() {
        return $this->belongsTo('App\Models\Ciz', 'ciz_id', 'id_ciz');
    }
}