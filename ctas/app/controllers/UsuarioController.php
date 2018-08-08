<?php

namespace App\Controllers;

use App\Log;
use App\Models\Delegacion;
use App\Models\Usuario;
use Sirius\Validation\Validator;


class UsuarioController extends BaseController {

    public function getIndex() {
        $usuarios = Usuario::orderBy('id_user', 'desc')->get();
        return $this->render('usuarios.twig', [
            'usuarios' => $usuarios
        ]);
    }

    public function getSignup() {
        Log::logInfo('Crear Usuario:');

        $delegaciones = Delegacion::where('activo', '1')->orderBy('delegacion')->get();

        return $this->render(
            'sign-up.twig', [
            'delegaciones' => $delegaciones
        ]);
    }

    public function postSignup() {
        $errors = [];
        $result = false;

        $validator = new Validator();

        $validator->add('delegacion:Delegación', 'GreaterThan',['min' => 0], '{label}: Es campo obligatorio.');
        $validator->add('curp:CURP','required', null, '{label}: es un campo obligatorio.');
        $validator->add('curp:CURP','minlength(min=18)({label}: Debe tener {min} caracteres)');
        $validator->add('curp:CURP','maxlength(max=18)({label}: Debe tener {max} caracteres)');

        $validator->add('email', 'required', null, 'Email: El campo es obligatorio');
        $validator->add('email', 'email', null, 'Email: El valor no es un correo válido', 'etiq');

        $validator->add('password', 'required', null, 'Contraseña: El campo es obligatorio');

        if ($validator->validate($_POST)) {

            $usuario_ya_existe = Usuario::where('username', $_POST['curp'])->get();

            if ($usuario_ya_existe) {

                Log::logError('Usuario ya existente. CURP:' . $_POST['curp']);
                $validator->addMessage('curp', 'No se puede registrar de nuevo. ' . $_POST['curp'] . ' ya tiene registro previo. Inicie sesión');
                $errors = $validator->getMessages();
                $result = false;

                $delegaciones = Delegacion::where('activo', '1')->orderBy('delegacion')->get();

                return $this->render('sign-up.twig', [
                    'delegaciones' => $delegaciones,
                    'curp' => $_POST['curp'],
                    'delegacion' => $_POST['delegacion'],
                    'puesto' => $_POST['puesto'],
                    'email' => $_POST['email'],
                    'result' => $result,
                    'errors' => $errors
                ]);
            }

            $usuario = new Usuario();

            $usuario->username = $_POST['curp'];
            $usuario->delegacion = $_POST['delegacion'];
            $usuario->subdelegacion = 0;
            $usuario->id_puesto = $_POST['puesto'];
            $usuario->email = $_POST['email'];
            $usuario->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $usuario->id_estatus = 0;

            $usuario->save();
            $result = true;
        } else {
            Log::logError('Intento de creación de usuario. CURP:' . $_POST['curp']);

            $delegaciones = Delegacion::where('activo', '1')->orderBy('delegacion')->get();
            $errors = $validator->getMessages();
        }

        return $this->render('sign-up.twig', [
            'curp' => $_POST['curp'],
            'delegaciones' => $delegaciones,
            'delegacion' => $_POST['delegacion'],
            'puesto' => $_POST['puesto'],
            'email' => $_POST['email'],
            'result' => $result,
            'errors' => $errors
        ]);
    }
}
