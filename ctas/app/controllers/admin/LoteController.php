<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Lote;
use Sirius\Validation\Validator;

class LoteController extends BaseController {

    public function getIndex() {
        // admin/lotes or admin/lotes/index
        // $listaLotes = Lote::all();
        $listaLotes = Lote::orderBy('id_lote', 'desc')->get();
        return $this->render('admin/lotes.twig', ['listaLotes' => $listaLotes]);
    }

    public function getCrear() {
        // admin/lotes/crear
        return $this->render('admin/agregar-lote.twig');
    }

    public function postCrear() {
        // admin/lotes/crear
        $errors = [];
        $result = false;

        $validator = new Validator();

        /*$validator->add('lote_anio', 'required');*/
        /*$validator->add('lote_anio', 'required', array('min' => 6), '{label}: Nuevo Lote: debe tener al menos {min} characters', 'Title');*/
        $validator->add('lote_anio', 'required', array('min' => 6), 'Nuevo Lote: El campo es obligatorio', 'Title');

        if ($validator->validate($_POST)) {
            $newLote = new Lote([
                'lote_anio' => $_POST['lote_anio'],
                'comentario' => $_POST['comentario']
            ]);
            $newLote->save();

            $result = true;
        } else {

            $errors = $validator->getMessages();
        }

        return $this->render('admin/agregar-lote.twig', [
            'lote_anio' => $_POST['lote_anio'],
            'comentario' => $_POST['comentario'],
            'result' => $result,
            'errors' => $errors
        ]);
    }
}