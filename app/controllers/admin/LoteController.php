<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Lote;

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
        $newLote = new Lote([
            'lote_anio' => $_POST['lote_anio'],
            'comentario' => $_POST['comentario']
        ]);
        $newLote->save();

        $result = true;

        return $this->render('admin/agregar-lote.twig', ['result' => $result]);
    }
}