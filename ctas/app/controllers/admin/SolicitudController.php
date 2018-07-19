<?php

namespace App\Controllers\Admin;

use App\Log;
use App\Controllers\BaseController;
use App\Models\Grupo;
use App\Models\Solicitud;
use App\Models\Solicitud2;
use App\Models\Subdelegacion;
use App\Models\Valija;
use Sirius\Validation\Validator;
use Sirius\Upload\Handler as UploadHandler;



class SolicitudController extends BaseController {

    public  function getIndex() {
        // admin/solicitudes/
        Log::logInfo('Solicitudes-INDEX. Usuario:' . $_SESSION['usuarioId'] );

        //$solicitudes = Solicitud2::orderBy('id_solicitud', 'desc')->get();
        //$solicitudes = Solicitud2::where('delegacion', $_SESSION['usuarioDel'])->take(10)->orderBy('id_solicitud', 'desc')->get();

        //$listado_usuarios = racf_det_userid::find(getenv('TMP_USER_ID_RACF'))->delegacion_id;

        //$solicitudes = Solicitud2::find(11852)->valija_id;
        //$users = DB::table('users')->count();
        //$solicitudes = Solicitud2::where('delegacion',30)->count();
        //var_dump($solicitudes );
        //$comment = App\Comment::find(1);

        //Obtener el num_oficio_ca en la solicitud con id_solicitud=16
        //$solicitudes = Solicitud2::find(16)->valija->num_oficio_ca;

        //Obtener los num_oficio_ca de todas las solicitudes de la del 30
        //$solicitudes = Solicitud2::where('delegacion',30)->get();
        //foreach ($solicitudes as $solicitud) {
            //var_dump($solicitud);
            //var_dump($solicitud->valija->num_oficio_ca);
        //}

        //Obtener todas las num_oficio_ca de todas las solicitudes
        $solicitudes = Solicitud2::where('delegacion',2)->orderBy('id_solicitud')->get();
        //foreach ($solicitudes as $solicitud) {
            //var_dump($solicitud);
            //var_dump($solicitud->valija->num_oficio_ca);
        //}

        return $this->render('admin/solicitudes.twig',
            [
                'solicitudes' => $solicitudes
            ]);
    }

    public function getCrear() {
        // admin/solicitudes/crear
        Log::logInfo('Crear Solicitud. Usuario:' . $_SESSION['usuarioId'] . 'Del:' .$_SESSION['usuarioDel'] );

        //$valijas = Valija::where('delegacion', $_SESSION['usuarioDel'])->take(10)->orderBy('id_valija', 'desc')->get();
        $subdelegaciones = Subdelegacion::where('delegacion', $_SESSION['usuarioDel'])->orderBy('subdelegacion')->get();
        $grupos = Grupo::all();

        return $this->render('admin/agregar-solicitud.twig', [
            //'valijas' => $valijas,
            'subdelegaciones' =>  $subdelegaciones,
            'grupos' => $grupos
        ]);
    }

    public function postCrear() {
        $errors = [];
        $errors2 = [];
        $result = false;

        $validator = new Validator();
        //$validator->add('id_valija:Núm de Oficio','required', null, '{label}: El campo es obligatorio');
        $validator->add('fecha_solicitud_del', 'required', null, 'Fecha de la Solicitud: La fecha es obligatoria', 'Fecha');
        //$validator->add('archivo:Archivo', 'required', null, '{label}: Es obligatorio adjuntar un archivo PDF menor a 5M');
        $validator->add('archivo:Archivo', 'File\Extension', ['allowed' => 'pdf'], '{label}: Es obligatorio adjuntar un archivo PDF');
        $validator->add('comentario:Comentario', 'maxlength(max=500)({label}: Debe tener menos de {max} caracteres)');

        if ($validator->validate($_POST)) {

            $uploadHandler = new UploadHandler('C:\xampp\htdocs\dspa_web\ctas\public\files');
            $uploadHandler->addRule('extension', ['allowed' => 'pdf'], '{label}: debe ser un .pdf válido', 'Archivo');
            $uploadHandler->addRule('size', 'size=5M', '{label}: solo puedes adjuntar PDF de tamaño menor a 5Mb', 'Archivo');
            $result2 = $uploadHandler->process($_FILES['archivo']);

            if ($result2->isValid()) {
                try {

                    $solicitud = new Solicitud([
                        'id_valija' => 3,
                        'id_lote' => 0,
                        'fecha_solicitud_del' => $_POST['fecha_solicitud_del'],
                        'id_movimiento' => $_POST['tipo_movimiento'],
                        'delegacion' => $_SESSION['usuarioDel'],
                        'subdelegacion' => $_POST['subdelegacion'],
                        'primer_apellido' => $_POST['primer_apellido'],
                        'segundo_apellido' => $_POST['segundo_apellido'],
                        'nombre' => $_POST['nombre'],
                        'matricula' => $_POST['matricula'],
                        'curp' => $_POST['curp'],
                        'usuario' => $_POST['usuario'],
                        'id_grupo_actual' => $_POST['gpo_actual'],
                        'id_grupo_nuevo' => $_POST['gpo_nuevo'],
                        'comentario' => $_POST['comentario'],
                        'archivo' => $result2->name,
                        'id_user' => $_SESSION['usuarioId'],
                    ]);

                    //var_dump($solicitud);
                    Log::logInfo('Solicitud creada. Usuario:' . $_SESSION['usuarioId'] . '|Solicitud:' );
                    $solicitud->save();

                    $result2->confirm();
                    $result = true;

                } catch (\Exception $e) {
                    $result2->clear();
                    throw $e;
                }
            } else {
                Log::logError('Crear Solicitud(uphandler). Usuario:' . $_SESSION['usuarioId'] );
                $errors2 = $result2->getMessages();
            }
        } else {
            Log::logError('Crear Solicitud(normal). Usuario:' . $_SESSION['usuarioId'] );
            $errors = $validator->getMessages();
        }

        //$valijas = Valija::where('delegacion', $_SESSION['usuarioDel'])->take(10)->orderBy('id_valija', 'desc')->get();
        $subdelegaciones = Subdelegacion::where('delegacion', $_SESSION['usuarioDel'])->orderBy('subdelegacion')->get();
        $grupos = Grupo::all();

        return $this->render('admin/agregar-solicitud.twig', [
            'fecha_solicitud_del' => $_POST['fecha_solicitud_del'],
            'tipo_movimiento' => $_POST['tipo_movimiento'],
            'subdelegacion' => $_POST['subdelegacion'],
            'primer_apellido' => $_POST['primer_apellido'],
            'segundo_apellido' => $_POST['segundo_apellido'],
            'nombre' => $_POST['nombre'],
            'matricula' => $_POST['matricula'],
            'curp' => $_POST['curp'],
            'usuario' => $_POST['usuario'],
            'gpo_actual' => $_POST['gpo_actual'],
            'gpo_nuevo' => $_POST['gpo_nuevo'],
            'comentario' => $_POST['comentario'],
            'subdelegaciones' =>$subdelegaciones,
            'grupos' => $grupos,
            'result' => $result,
            'errors' => $errors,
            'errors2' => $errors2
        ]);
    }
}