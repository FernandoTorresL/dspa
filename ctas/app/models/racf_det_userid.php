<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvRacf_userid_ciz;

class InvRacf_userid extends Model {
    protected $table = 'racf_userid';
    protected $primaryKey = 'userid_racf';
    public $incrementing = false;

    public function cizs() {
        return $this->hasMany('App\Models\InvRacf_userid_ciz', 'userid_racf');
    }
}