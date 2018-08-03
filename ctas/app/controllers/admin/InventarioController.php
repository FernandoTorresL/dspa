<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Detalle_ctas;
use App\Log;

//use App\Models\Inventario;
//use App\Models\InvRacf_userid;
//use App\Models\racf_userid;
//use App\Models\racf_det_userid;
//use Sirius\Validation\Validator;

class InventarioController extends BaseController {

    public function getIndex() {

        Log::logInfo('Inventario. User:' . $_SESSION['usuarioId'] . '|Del:' . $_SESSION['usuarioDel']);

        //$listado_usuarios = Inventario::select('select * from racf_userid where delegacion=14', array(1));
        //$listado_usuarios = Inventario::all();
        //$listado_usuarios = Inventario::where('delegacion', $_SESSION['usuarioDel'])->take(100)->get();
        //$listado_usuarios = InvRacf_userid::find(getenv('TMP_USER_ID_RACF'))->cizs;
        //$listado_usuarios = racf_det_userid::where('delegacion', $_SESSION['usuarioDel'])->take(10)->get();
        //$listado_usuarios = racf_det_userid::find(getenv('TMP_USER_ID_RACF'))->delegacion_id;

        //$listado_usuarios = racf_userid::find(getenv('TMP_USER_ID_RACF'))->user;
        //$listado_usuarios = racf_det_userid::where('userid_racf', getenv('TMP_USER_ID_RACF'))->take(10)->get();
        //$listado_usuarios = racf_userid::where('delegacion', $_SESSION['usuarioDel'])->get();
        //$listado_usuarios_ciz = racf_userid::find(getenv('TMP_USER_ID_RACF'))->cizs;

//        $listado_usuarios = racf_inventario::where('Del', $_SESSION['usuarioDel'])->orderBy('Tipo_Cuenta', 'desc')->orderBy('Usuario')->get();
//        $bd_mainframe = racf_bd_mainframe::find(1);

        $listado_detalle_ctas = Detalle_ctas::where('delegacion_id', $_SESSION['usuarioDel'])->orderBy('area_id', 'desc')->orderBy('cuenta')->orderBy('ciz_id')->get();
        $detalle_cta = Detalle_ctas::find(1);

        return $this->render('admin/inventario.twig', [
            'listado_detalle_ctas' => $listado_detalle_ctas,
            'inventario' => $detalle_cta->inventario,
            'inventario_nombre' => $detalle_cta->inventario->nombre
        ]);
    }
}