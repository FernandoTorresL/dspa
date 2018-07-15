<?php

namespace App\Controllers;

use App\Log;
use App\Models\Usuario;
use Sirius\Validation\Validator;

class AuthController extends BaseController {

    public function getLogin() {
        Log::logInfo('Visitando CTAS-Login');
        return $this->render('login.twig');
    }

    public function postLogin() {
        $validator = new Validator();
        $validator->add('curp', 'required', array('max' => 18), 'CURP: El campo es obligatorio', 'Title');
        $validator->add('password', 'required', null, 'Contraseña: El campo es obligatorio');

        if ($validator->validate($_POST)) {
            $usuario = Usuario::where('username', $_POST['curp'])->first();
            if ($usuario) {
                if (password_verify($_POST['password'], $usuario->password)) {
                    // Usuario OK
                    $_SESSION['usuarioId'] = $usuario->id_user;
                    $_SESSION['usuarioDel'] = $usuario->delegacion;
                    Log::logInfo('Iniciando sesión:' . $usuario->id_user . '|Deleg:' . $usuario->delegacion);
                    header('Location:' . BASE_URL . 'admin');
                    return null;
                }
            }

            // Usuario NOT OK
            Log::logError('Intento de inicio sesión CURP:' . $_POST['curp']);
            $validator->addMessage('email', 'CURP y/o contraseña no coinciden');
        }

        $errors = $validator->getMessages();

        return $this->render('login.twig', [
            'errors' => $errors
        ]);
    }

    public function getLogout() {
        Log::logInfo('Cerrando sesión:' . $_SESSION['usuarioId']);
        unset($_SESSION['usuarioId']);
        unset($_SESSION['usuarioDel']);
        header('Location:' . BASE_URL . 'auth/login');
    }

}
