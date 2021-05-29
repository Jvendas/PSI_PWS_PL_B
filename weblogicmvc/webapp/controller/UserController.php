<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class UserController extends BaseController
{

    public function index()
    {
        $error = "";

        if (Session::has("user-register-error")) {
            $error = Session::get("user-register-error");
            Session::remove("user-register-error");
        }

        return View::make('home.register', ['errorMessage' => $error]);
    }

    public function registar()
    {
        //obtem os dados do post do form
        $user = Post::getAll();

        //guarda o utilizador na base de dados se ainda não existir um nome e pais igual
        $exist = User::all(array('conditions' => array('username = ? OR email = ?', $user['username'], $user['email'])));

        if ($exist) {
            Session::set("error-message", "Já existe o utilizador com o username: " . $user['username']);
            Redirect::toRoute('user/register');
        } else {
            User::create($user);
            Session::set("success-message", "Utilizador " . $user['username'] . " criado com sucesso!");
            Redirect::toRoute('home/login');
        }
    }


    public function eliminar($idutilizador)
    {
        $user = User::find([$idutilizador]);

        if (is_null($user)) {
            Session::set("error-message", "Não foi possível eliminar o utilizador com o id " . $idutilizador);
        } else {
            $user->delete();
            Session::set("success-message", "utilizador com o id " . $idutilizador . " eliminado com sucesso!");
        }

        Redirect::toRoute('admin/contas');
    }

    public function editar($idutilizador)
    {
        $user = User::find([$idutilizador]);

        if (!is_null($user)) {

            return View::make('home.register', ['user' => $user]);
        }
    }

    public function atualizar($idutilizador)
    {
        $user = User::find([$idutilizador]);
        $user->update_attributes(Post::getAll());

        if ($user->is_valid()) {
            $user->save();
            Redirect::toRoute('admin/contas');
        } else {
            // TODO: show errors
            //Redirect::flashToRoute('aeroporto/editar', ['airport' => $airport]);
        }
    }
}
