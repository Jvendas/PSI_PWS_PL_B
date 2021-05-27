<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AccountsController extends BaseController
{


    
    public function eliminar($idutilizador)
    {
        $user = User::find([$idutilizador]);

        if (is_null($user)) {
           // Session::set("admin-aeroportos-error", "Não foi possível eliminar o utilizador com o id " . $$idutilizador);
        } else {
            $user->delete();
            //Session::set("admin-aeroportos-success", "utilizador com o id " . $idutilizador . " eliminado com sucesso!");
        }

        Redirect::toRoute('admin/contas');
    }

}