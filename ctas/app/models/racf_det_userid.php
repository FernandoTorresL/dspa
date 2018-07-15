<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class racf_det_userid extends Model {
    protected $table = 'racf_det_userid';
    protected $primaryKey = 'userid_racf';
    public $incrementing = false;

    public function delegacion_id() {
        return $this->hasMany('App\Models\racf_userid', 'userid_racf');
    }
}