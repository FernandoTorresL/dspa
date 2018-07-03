<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Usuario;
use Sirius\Validation\Validator;


class UsuarioController extends BaseController {

    public function getIndex() {
        $usuarios = Usuario::orderBy('id_user', 'desc')->get();
        return $this->render('admin/usuarios.twig', [
            'usuarios' => $usuarios
        ]);
    }

    public function getSignup() {
        // admin/usuarios/signup
        return $this->render('admin/sign-up.twig');
    }

    public function postSignup() {
        $errors = [];
        $result = false;

        $validator = new Validator();
        $validator->add('curp', 'required', array('max' => 18), 'CURP: El campo es obligatorio', 'Title');
        $validator->add('email', 'required', null, 'Email: El campo es obligatorio');
        $validator->add('email', 'email', null, 'Email: El valor no es un correo válido', 'etiq');
        $validator->add('password', 'required', null, 'Contraseña: El campo es obligatorio');

        if ($validator->validate($_POST)) {
            $usuario = new Usuario();
            $usuario->username = $_POST['curp'];
            $usuario->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

//            $usuario->delegacion = $_POST['delegacion'];
//            $usuario->subdelegacion = $_POST['subdelegacion'];
//            $usuario->puesto = $_POST['puesto'];
            $usuario->delegacion = 9;
            $usuario->subdelegacion = 0;
            $usuario->id_puesto = 0;

            $usuario->email = $_POST['email'];

            $usuario->save();
            $result = true;
        } else {
            $errors = $validator->getMessages();
        }

        return $this->render('admin/sign-up.twig', [
            'curp' => $_POST['curp'],
            'email' => $_POST['email'],
            'result' => $result,
            'errors' => $errors
        ]);
    }
}