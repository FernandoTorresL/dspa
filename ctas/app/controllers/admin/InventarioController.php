<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Detalle_ctas;
use App\Log;

class InventarioController extends BaseController {

    public function getIndex() {

        Log::logInfo('Inventario. User:' . $_SESSION['usuarioId'] . '|Del:' . $_SESSION['usuarioDel']);

        $listado_detalle_ctas =
            Detalle_ctas::select('cuenta', 'nombre', 'gpo_owner_id', 'area_id', 'install_data')->where('delegacion_id', $_SESSION['usuarioDel'])->orderBy('area_id')->orderBy('cuenta')->distinct()->get();

        $total_detalle_ctas = $listado_detalle_ctas->count();

        $detalle_cta = Detalle_ctas::find(1);

        return $this->render('admin/inventario.twig', [
            'listado_detalle_ctas' => $listado_detalle_ctas,
            'inventario' => $detalle_cta->inventario,
            'total_detalle_ctas' => $total_detalle_ctas
        ]);
    }
}