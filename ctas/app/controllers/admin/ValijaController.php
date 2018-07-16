<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Valija;
use Sirius\Validation\Validator;


class ValijaController extends BaseController {

    public  function getIndex() {
        $valijas = Valija::orderBy('id_valija', 'desc')->get();
        return $this->render('admin/valijas.twig', ['valijas' => $valijas]);
    }

    
}