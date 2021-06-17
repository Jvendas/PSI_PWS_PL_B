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
        // TODO: remover este if na entrega do projeto - serve so de debug
        if (Session::has("error-message")) {
            $error = Session::get("error-message");
            Session::remove("error-message");
        }

        return View::make('home.register', ['isAdmin' => $this->isAdmin(), 'errorMessage' => $error]);
    }

    public function registar()
    {
        //obtem os dados do post do form
        $user = Post::getAll();

        //guarda o utilizador na base de dados se ainda não existir um nome ou email igual
        $exist = User::all(array('conditions' => array('username = ? OR email = ?', $user['username'], $user['email'])));

        if ($exist) {
            Session::set("error-message", "Já existe o utilizador com o username: " . $user['username']);
            Redirect::toRoute('user/register');
        } else {
            User::create($user);
            Session::set("success-message", "Utilizador " . $user['username'] . " criado com sucesso!");

            if ($this->isAdmin()) {
                Redirect::toRoute('admin/contas');
                return;
            }

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
        $user = User::find([$idutilizador]); // $user to edit

        if (!is_null($user)) {
            return View::make('home.register', ['user' => $user, 'isAdmin' => $this->isAdmin()]);
        }
    }

    public function atualizar($idutilizador)
    {
        $user = User::find([$idutilizador]);
        $user->update_attributes(Post::getAll());

        if ($user->is_valid()) {
            $user->save();
        }
        switch ($user->perfil) {

            case 'administrador':

                return Redirect::toRoute('admin/contas');
                break;

            case 'passageiro':
                Session::set("success-message", "Dados alterados com sucesso");
                return Redirect::toRoute('passageiro/perfil');
                break;
        }
    }

    public function loginView()
    {
        if (Session::has('Authentication')) {
            var_dump(Session::get('Authentication'));
        }

        return View::make('home.login');
    }

    public function login()
    {
        $formData = Post::getAll();
        //    $user = User::find_by_perfil('passageiro');
        $user = User::find(array('conditions' => array('username = ? AND password = ?', $formData['username'], $formData['password'])));

        if ($user) {
            Session::destroy();
            Session::set("Authentication", ['user' => $user->username, 'perfil' => $user->perfil]);

            switch ($user->perfil) {
                case 'administrador':
                    return Redirect::toRoute('admin/contas');

                case 'passageiro':
                    return Redirect::toRoute('passageiro/voos');
                    break;

                case 'gestor de voo':
                 
                    break;

                case 'operador de checkin':
                    // TODO: return Redirect::toRoute...
                    break;

                default:
                    Redirect::toRoute('home/login');
                    return;
            }
        }

        Redirect::toRoute('user/register');
    }

    public function isAdmin()
    {
        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'administrador') {

            return false;
        }

        return true;
    }


    public function logout()
    {
        Session::destroy();
        Redirect::toRoute(('home/login'));
    }
}
