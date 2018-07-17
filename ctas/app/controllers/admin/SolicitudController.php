<?php

namespace App\Controllers\Admin;

use App\Log;
use App\Controllers\BaseController;
use App\Models\Solicitud;
use Sirius\Validation\Validator;
use Sirius\Upload\Handler as UploadHandler;



class SolicitudController extends BaseController {

    public  function getIndex() {
        // admin/solicitudes/
        Log::logInfo('Solicitudes-INDEX. Usuario:' . $_SESSION['usuarioId'] );

        //$solicitudes = Solicitud::orderBy('id_solicitud', 'desc')->get();
        //$solicitudes = Solicitud::where('delegacion', $_SESSION['usuarioDel'])->take(10)->orderBy('id_solicitud', 'desc')->get();

        //$listado_usuarios = racf_det_userid::find(getenv('TMP_USER_ID_RACF'))->delegacion_id;

        //$solicitudes = Solicitud::find(11852)->valija_id;
        //$users = DB::table('users')->count();
        //$solicitudes = Solicitud::where('delegacion',30)->count();
        //var_dump($solicitudes );
        //$comment = App\Comment::find(1);

        //Obtener el num_oficio_ca en la solicitud con id_solicitud=16
        //$solicitudes = Solicitud::find(16)->valija->num_oficio_ca;

        //Obtener los num_oficio_ca de todas las solicitudes de la del 30
        //$solicitudes = Solicitud::where('delegacion',30)->get();
        //foreach ($solicitudes as $solicitud) {
            //var_dump($solicitud);
            //var_dump($solicitud->valija->num_oficio_ca);
        //}

        //Obtener todas las num_oficio_ca de todas las solicitudes
        $solicitudes = Solicitud::where('delegacion',2)->orderBy('id_solicitud')->get();
        //foreach ($solicitudes as $solicitud) {
            //var_dump($solicitud);
            //var_dump($solicitud->valija->num_oficio_ca);
        //}

        return $this->render('admin/solicitudes.twig',
            [
                'solicitudes' => $solicitudes
            ]);
    }

}