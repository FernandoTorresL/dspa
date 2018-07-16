<?php

namespace App\Controllers\Admin;

use App\Log;
use App\Controllers\BaseController;
use App\Models\Usuario;

class IndexController extends BaseController {

    public function getIndex() {
        if (isset($_SESSION['usuarioId'])) {
            Log::logInfo('Admin-INDEX(Panel principal). User:' . $_SESSION['usuarioId']);
            $usuarioId = $_SESSION['usuarioId'];
            $usuario = Usuario::where('id_user', $usuarioId)->first();

            if ($usuario) {
                return $this->render('admin/index.twig', [
                    'usuario' => $usuario
                ]);
            }
        }

        header('Location:' . BASE_URL . 'auth/login');
    }
}