<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model {
    protected $table = 'racf_userid';
    protected $primaryKey = 'userid_racf';
    public $incrementing = false;
}