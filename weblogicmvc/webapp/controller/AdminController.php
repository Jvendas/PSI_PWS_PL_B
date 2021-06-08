<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController
{

    public function contas()
    {   //verificar se nao existe autenticação na secção
        if (!Session::has("Authentication")) {
            return Redirect::toRoute('home/login');
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'administrador') {
            return Redirect::toRoute('home/login');
        }

        // obter todos os dados da base de dados
        $users = User::all();
        return View::make('admin.contas', ['users' => $users]);
    }

    public function aeroportos()
    {
        if (!Session::has("Authentication")) {
            return Redirect::toRoute('home/login');
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'administrador') {
            return Redirect::toRoute('home/login');
        }

        $airports = Airport::all();
        $error = "";
        $success = "";

        if (is_null($airports) || count($airports) == 0) {
            $airports = [];
        }

        // mensagem de error
        if (Session::has("error-message")) {
            $error = Session::get("error-message");
            Session::remove("error-message");
        }

        // mensagem de sucesso
        if (Session::has("success-message")) {
            $success = Session::get("success-message");
            Session::remove("success-message");
        }

        return View::make('admin.aeroportos', ['airports' => $airports, 'errorMessage' => $error, 'successMessage' => $success]);
    }
}
