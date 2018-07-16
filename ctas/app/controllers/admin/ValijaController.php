<?php

namespace App\Controllers\Admin;

use App\Log;
use App\Controllers\BaseController;
use App\Models\Valija;
use Sirius\Validation\Validator;
use Sirius\Upload\Handler as UploadHandler;



class ValijaController extends BaseController {

    public  function getIndex() {
        // admin/valijas/
        Log::logInfo('Valijas-INDEX. Usuario:' . $_SESSION['usuarioId'] );
        $valijas = Valija::orderBy('id_valija', 'desc')->get();
        return $this->render('admin/valijas.twig', ['valijas' => $valijas]);
    }

    public function getCrear() {
        // admin/valijas/crear
        Log::logInfo('Crear Valija. Usuario:' . $_SESSION['usuarioId'] );
        return $this->render('admin/agregar-valija.twig');
    }

    public function postCrear() {
        $errors = [];
        $errors2 = [];
        $result = false;

        $validator = new Validator();
        $validator->add('num_oficio_del:Núm de Oficio','required', null, '{label}: El campo es obligatorio');
        $validator->add('fecha_valija_del', 'required', null, 'Fecha de Oficio: La fecha es obligatoria', 'Fecha');
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

                    $valija = new Valija([
                        'num_oficio_del' => $_POST['num_oficio_del'],
                        'fecha_valija_del' => $_POST['fecha_valija_del'],
                        'comentario' => $_POST['comentario'],
                        'archivo' => $result2->name
                    ]);

                    Log::logInfo('Valija creada. Usuario:' . $_SESSION['usuarioId'] . '|Valija:' );
                    $valija->save();

                    $result2->confirm();
                    $result = true;

                } catch (\Exception $e) {
                    $result2->clear();
                    throw $e;
                }
            } else {
                Log::logError('Crear Valija(uphandler). Usuario:' . $_SESSION['usuarioId'] );
                $errors2 = $result2->getMessages();
            }
        } else {
            Log::logError('Crear Valija(normal). Usuario:' . $_SESSION['usuarioId'] );
            $errors = $validator->getMessages();
        }

        return $this->render('admin/agregar-valija.twig', [
            'num_oficio_del' => $_POST['num_oficio_del'],
            'fecha_valija_del' => $_POST['fecha_valija_del'],
            'comentario' => $_POST['comentario'],
            'result' => $result,
            'errors' => $errors,
            'errors2' => $errors2
        ]);
    }
}