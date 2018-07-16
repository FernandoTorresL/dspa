<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\racf_det_userid;

class racf_userid extends Model {
    protected $table = 'racf_userid';
    protected $primaryKey = 'userid_racf';
    public $incrementing = false;

    public function user() {
        return $this->belongsToMany('App\Models\racf_det_userid', 'racf_userid', 'userid_racf', 'userid_racf');
    }

    public function cizs() {
        return $this->hasMany('App\Models\racf_userid_ciz', 'userid_racf');
    }
}