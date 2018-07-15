<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Inventario;
use App\Models\InvRacf_userid;
use App\Models\racf_userid;
use App\Models\racf_det_userid;
use Sirius\Validation\Validator;



class InventarioController extends BaseController {

    public function getIndex() {
        //$listado_usuarios = Inventario::select('select * from racf_userid where delegacion=14', array(1));
        //$listado_usuarios = Inventario::all();
        //$listado_usuarios = Inventario::where('delegacion', $_SESSION['usuarioDel'])->take(10)->get();
        //$listado_usuarios = InvRacf_userid::find(getenv('TMP_USER_ID_RACF'))->cizs;
        //$listado_usuarios = racf_det_userid::where('delegacion', $_SESSION['usuarioDel'])->take(10)->get();
        //$listado_usuarios = racf_det_userid::find(getenv('TMP_USER_ID_RACF'))->delegacion_id;

        $listado_usuarios = racf_userid::find(getenv('TMP_USER_ID_RACF'))->user;
        //$listado_usuarios = racf_det_userid::where('userid_racf', getenv('TMP_USER_ID_RACF'))->take(10)->get();
        return $this->render('admin/inventario.twig', [
            'listado_usuarios' => $listado_usuarios
        ]);
    }
}