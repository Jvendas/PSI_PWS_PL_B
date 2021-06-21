<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class UserController extends BaseController
{
    // =========================================================
    //                          LOGIN
    // =========================================================

    public function loginIndex()
    {
        return View::make('home.login');
    }

    public function login()
    {
        $formData = Post::getAll();
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
                    return Redirect::toRoute('gestor/');
                    break;

                case 'operador de checkin':
                    return Redirect::toRoute('checkin/');
                    break;

                default:
                    Redirect::toRoute('home/login');
                    return;
            }
        }

        MensagensHelper::setError("Credenciais invalidas");

        Redirect::toRoute('home/login');
    }

    // =========================================================
    //                          REGISTO
    // =========================================================

    public function registarIndex()
    {
        $authPerfil = null;
        if (Session::has('Authentication')) {
            $authPerfil = Session::get('Authentication')['perfil'];
        }
        return View::make('home.register', ['isAdmin' => $this->isAdmin(), 'authPerfil' => $authPerfil]);
    }

    public function registar()
    {
        $formData = Post::getAll();
        if (empty($formData) || empty($formData['username']) || empty($formData['nome']) || empty($formData['email']) || empty($formData['password']) || empty($formData['telefone']) || empty($formData['datanascimento'])) {
            MensagensHelper::setError("Os campos são todos obrigatórios");
            Redirect::toRoute('user/register');
            return;
        }

        //guarda o utilizador na base de dados se ainda não existir um nome ou email igual
        $exist = User::all(array('conditions' => array('username = ? OR email = ?', $formData['username'], $formData['email'])));

        if ($exist) {
            MensagensHelper::setError("Já existe o utilizador com o username: " . $formData['username']);
            Redirect::toRoute('user/register');
        } else {
            User::create($formData);
            MensagensHelper::setSuccess("Utilizador " . $formData['username'] . " criado com sucesso!");

            if ($this->isAdmin()) {
                Redirect::toRoute('admin/contas');
                return;
            }

            Redirect::toRoute('home/login');
        }
    }

    // =========================================================
    //                          EDITAR CONTA
    // =========================================================


    public function perfilView()
    {
        $username = null;

        if (Session::has('Authentication')) {
            $username = Session::get('Authentication')["user"];
        }

        $user = User::find(array('conditions' => array('username = ?', $username)));

        return View::make('home.register', ['user' => $user, 'authPerfil' => $user->perfil]);
    }

    public function atualizar($idutilizador)
    {
        if (!Session::has('Authentication')) {
            return Redirect::toRoute("home/login");
        }

        $user = User::find([$idutilizador]);
        $user->update_attributes(Post::getAll());

        if ($user->is_valid()) {
            $user->save();
        }

        $userAuthPerfil = Session::get('Authentication')['perfil'];
        MensagensHelper::setSuccess("Dados atualizados com sucesso");

        switch ($userAuthPerfil) {

            case 'administrador':
                return Redirect::toRoute('admin/contas');

            default:
                Redirect::toRoute('user/perfil');
                return;
        }
    }

    // =========================================================
    //                          LOGOUT
    // =========================================================

    public function logout()
    {
        Session::destroy();
        Redirect::toRoute('home/login');
    }

    // =========================================================
    //                          OUTRAS FUNCOES
    // =========================================================

    private function isAdmin()
    {
        if (!Session::has('Authentication')) {
            return false;
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'administrador') {
            return false;
        }

        return true;
    }
}
