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
        if (Session::has("admin-aeroportos-error")) {
            $error = Session::get("admin-aeroportos-error");
            Session::remove("admin-aeroportos-error");
        }

        // mensagem de sucesso
        if (Session::has("admin-aeroportos-success")) {
            $success = Session::get("admin-aeroportos-success");
            Session::remove("admin-aeroportos-success");
        }

        return View::make('admin.aeroportos', ['airports' => $airports, 'errorMessage' => $error, 'successMessage' => $success]);
    }
}
