<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LoteController extends BaseController {

    public function getIndex() {
        // admin/lotes or admin/lotes/index
        global $pdo;

        $query = $pdo->prepare('SELECT * FROM ctas_lotes ORDER BY id_lote DESC LIMIT 3');
        $query->execute();
        $listaLotes = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render('admin/lotes.twig', ['listaLotes' => $listaLotes]);
    }

    public function getCrear() {
        // admin/lotes/crear
        return $this->render('admin/agregar-lote.twig');
    }

    public function postCrear() {
        // admin/lotes/crear
        global $pdo;

        $sql = 'INSERT INTO ctas_lotes ( lote_anio, fecha_creacion, fecha_modificacion, comentario, id_user, num_oficio_ca, fecha_oficio_ca, num_ticket_mesa, fecha_atendido ) VALUES (:lote, NOW(), NOW(), :comentario, 2, "PENDIENTE", NULL, "PENDIENTE", NULL)';
        $query = $pdo->prepare($sql);
        $result = $query->execute([
            'lote' => $_POST['lote'],
            'comentario' => $_POST['comentario']
        ]);

        return $this->render('admin/agregar-lote.twig', ['result' => $result]);
    }
}