<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class racf_inventario extends Model {
    protected $table = 'racf_inventario';

    public function fecha_corte() {
        return $this->hasOne('App\Models\racf_bd_mainframe');
    }
}