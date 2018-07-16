<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Valija;
use Sirius\Validation\Validator;
use Sirius\Upload\Handler;
use Sirius\Upload\HandlerAggregate;


class ValijaController extends BaseController {

    public  function getIndex() {
        $valijas = Valija::orderBy('id_valija', 'desc')->get();
        return $this->render('admin/valijas.twig', ['valijas' => $valijas]);
    }

    public function getCrear() {
        // admin/posts/create
        return $this->render('admin/agregar-valija.twig');
    }

    public function postCrear() {
        $errors = [];
        $errors2 = [];
        $result = false;

        $result3 = false;

        $validator = new Validator();
        $validator2 = new Handler(getenv('FILES_PATH'));

        $validator->add('num_oficio_del:Núm de Oficio','required', null, '{label}: El campo es obligatorio');
        $validator->add('fecha_valija_del', 'required', null, 'Fecha de Oficio: La fecha es obligatoria', 'Fecha');
        //$validator->add('archivo:Archivo', 'File\Extension', ['allowed' => ['pdf']], '{label}: Es obligatorio adjuntar un archivo PDF');
        $validator->add('comentario:Comentario', 'maxlength(max=5)({label}: Debe tener menos de {max} caracteres)');

        $validator2->addRule('extension', ['allowed' => ['pdf']], '{label}: debe ser un PDF válido', 'Archivo');
        $result2 = $validator2->process($_FILES['archivo']);

        if ($result2->isValid()) {
            try {
                $result2->confirm();
                $result3 = true;
            } catch (\Exception $e) {
                $result2->clear();
                throw $e;
            }
        } else {
            $errors2 = $result2->getMessages();

            $result3 = false;
        }

        if ($validator->validate($_POST) and ($result3) ) {

                $valija = new Valija([
                    'num_oficio_del' => $_POST['num_oficio_del'],
                    'fecha_valija_del' => $_POST['fecha_valija_del'],
                    'comentario' => $_POST['comentario'],
                    'archivo' => $result2->name
                ]);
                Log::logInfo('Agregar Valija. Usuario:' . $usuario->id_user . '|Valija:' );
                $valija->save();

                $result = true;
        } else {
            $errors = $validator->getMessages();
        }

        return $this->render('admin/agregar-valija.twig', [
            'num_oficio_del' => $_POST['num_oficio_del'],
            'fecha_valija_del' => $_POST['fecha_valija_del'],
            'comentario' => $_POST['comentario'],
            'archivo' => $_FILES['archivo']['name'],
            'result' => $result,
            'errors' => $errors,
            'errors2' => $errors2
        ]);
    }
}