<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User;
use App\Models\Usuario;

class IndexController extends BaseController {

    public function getIndex() {
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $user = User::find($userId);

            if ($user) {
                return $this->render('admin/index.twig', [
                    'user' => $user
                ]);
            }
        } else {

            if (isset($_SESSION['usuarioId'])) {
                $usuarioId = $_SESSION['usuarioId'];
                //$usuarioDel = $_SESSION['usuarioDel'];
                $usuario = Usuario::where('id_user', $usuarioId)->first();
                //DB::table('dspa_users')->where('name', 'John')->first();

                if ($usuario) {
                    return $this->render('admin/index.twig', [
                        'usuario' => $usuario
                    ]);
                }
            }

        }

        header('Location:' . BASE_URL . 'auth/login');
    }
}