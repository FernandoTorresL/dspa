<?php

namespace App\Controllers\Admin;

use App\Log;
use App\Controllers\BaseController;
use App\Models\Delegacion;
use App\Models\Grupo;
use App\Models\Movimiento;
use App\Models\Solicitud;
use App\Models\Solicitud2;
use App\Models\Subdelegacion;

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

        $delegacion = Delegacion::where('delegacion', $_SESSION['usuarioDel'])->first();
        $subdelegaciones = Subdelegacion::where('delegacion', $_SESSION['usuarioDel'])->orderBy('subdelegacion')->get();
        $grupos0 = Grupo::where('active', '<>', null)->orderBy('descripcion')->get();
        $grupos1 = Grupo::where('active', '=', 1)->orderBy('descripcion')->get();
        $movimientos = Movimiento::where('id_movimiento', '<>', 4)->get();

        return $this->render('admin/agregar-solicitud.twig', [
            //'valijas' => $valijas,
            'movimientos' => $movimientos,
            'delegacion' => $delegacion,
            'subdelegaciones' =>  $subdelegaciones,
            'grupos0' => $grupos0,
            'grupos1' => $grupos1
        ]);
    }

    public function postCrear() {
        $errors = [];
        $errors2 = [];
        $result = false;

        $validator = new Validator();

        //Reglas
        $validator->add('fecha_solicitud_del:Fecha de la Solicitud', 'required', null, '{label}: Es campo obligatorio.');
        $validator->add('tipo_movimiento:Tipo de movimiento', 'Between', '1,3','{label}: Es valor obligatorio.');

//        Subdelegación mayor o igual a 0
        $validator->add('subdelegacion:Subdelegación', 'GreaterThan', array('min' => 0), '{label}: Es campo obligatorio.');

//        $validator->add('primer_apellido:Primer apellido',
//            'required | minlength({"min":2}) ({label}: Debe tener mas de {min} caracteres) | maxlength({"max":3}) ({label}: Debe tener menos de {max} caracteres)');
        $validator->add('primer_apellido:Primer apellido', 'required', null, '{label}: Es un campo obligatorio');
        $validator->add('primer_apellido:Primer apellido', 'maxlength', array('max' => 50), '{label}: Debe tener menos de {max} caracteres');

        $validator->add('segundo_apellido:Segundo apellido', 'maxlength', array('max' => 50), '{label}: Debe tener menos de {max} caracteres');

        $validator->add('nombre:Nombre(s)','required', null, '{label}: Es un campo obligatorio');
        $validator->add('nombre:Nombre(s)', 'maxlength', array('max' => 3), '{label}: Debe tener menos de {max} caracteres');

        $validator->add('matricula:Matrícula','required', null, '{label}: Es un campo obligatorio. Matrícula|TTD');
        $validator->add('matricula:Matrícula', 'maxlength', array('max' => 10), '{label}: Debe tener menos de {max} caracteres');

        $validator->add('curp:CURP','required', null, '{label}: es un campo obligatorio.');
//        $validator->add('curp:CURP', 'length', '18, 18','{label}: Debe tener caracteres');
//        $validator->add('curp:CURP','Regex', array('pattern' => '^[A-Z]{4}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1}^'), '{label}: Parece que no es una CURP valida');

        $validator->add('usuario:USER-ID','required', null, '{label}: es un campo obligatorio.');

        //RequiredWith Rules
        //"sale_price" => "required_if:list_type,==,selling"
//        $validator->add('gpo_actual:Grupo actual', 'RequiredWith',array('item' => 'matricula'), '{label}: Es campo obligatorio.');
        //$validator->add('gpo_actual:Grupo actual', 'requiredWith','tipo_movimiento,==,1' , '{label}: Es campo obligatorio.');

        //Reglas específicas
        $validator->add('comentario:Comentario', 'maxlength(max=500)({label}: Debe tener menos de {max} caracteres)');
        //$validator->add('nombre:Nombre(s)','required | length(2,4) | fullname', null, '{label}: ');

        if ($validator->validate($_POST)) {

            $uploadHandler = new UploadHandler(getenv('FILES_PATH'));
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
                        'primer_apellido' => strtoupper(trim($_POST['primer_apellido'])),
                        'segundo_apellido' => strtoupper(trim($_POST['segundo_apellido'])),
                        'nombre' => strtoupper( trim($_POST['nombre'])),
                        'matricula' => strtoupper( trim($_POST['matricula'])),
                        'curp' => strtoupper( trim($_POST['curp'])),
                        'usuario' => strtoupper( trim($_POST['usuario'])),
                        'id_grupo_actual' => $_POST['gpo_actual'],
                        'id_grupo_nuevo' => $_POST['gpo_nuevo'],
                        'comentario' => $_POST['comentario'],

                        'archivo' => $result2->name,
                        'id_user' => $_SESSION['usuarioId'],
                    ]);


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

        $movimientos = Movimiento::where('id_movimiento', '<>', 4)->get();
        $subdelegaciones = Subdelegacion::where('delegacion', $_SESSION['usuarioDel'])->orderBy('subdelegacion')->get();
        $grupos0 = Grupo::where('active', '<>', null)->orderBy('descripcion')->get();
        $grupos1 = Grupo::where('active', '=', 1)->orderBy('descripcion')->get();

        //var_dump($_POST['fecha_solicitud_del']);
        return $this->render('admin/agregar-solicitud.twig', [
            'fecha_solicitud_del' => $_POST['fecha_solicitud_del'],
            'tipo_movimiento' => $_POST['tipo_movimiento'],
            'delegacion' => $_SESSION['usuarioDel'],
            'subdelegacion' => $_POST['subdelegacion'],
            'primer_apellido' => trim($_POST['primer_apellido']),
            'segundo_apellido' => trim($_POST['segundo_apellido']),
            'nombre' => trim($_POST['nombre']),
            'matricula' => trim($_POST['matricula']),
            'curp' => trim($_POST['curp']),
            'usuario' => trim($_POST['usuario']),
            'gpo_actual' => $_POST['gpo_actual'],
            'gpo_nuevo' => $_POST['gpo_nuevo'],
            'comentario' => trim($_POST['comentario']),

            'movimientos' => $movimientos,
            'subdelegaciones' =>$subdelegaciones,
            'grupos0' => $grupos0,
            'grupos1' => $grupos1,

            'result' => $result,
            'errors' => $errors,
            'errors2' => $errors2
        ]);
    }
}