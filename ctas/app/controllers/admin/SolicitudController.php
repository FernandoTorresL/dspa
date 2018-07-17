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
        $solicitudes = Solicitud::where('delegacion', $_SESSION['usuarioDel'])->take(10)->orderBy('id_solicitud', 'desc')->get();
        return $this->render('admin/solicitudes.twig', ['solicitudes' => $solicitudes]);
    }

}