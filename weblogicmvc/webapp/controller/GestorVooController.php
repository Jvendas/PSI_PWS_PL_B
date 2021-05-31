<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController
{

    public function contas()
    {
        $users = User::all();
        return View::make('admin.contas' , ['users'=>$users]);
    }

    public function aeroportos()
    {
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
