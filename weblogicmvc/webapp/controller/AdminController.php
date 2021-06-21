<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController
{
    private function utilizadorIsValid()
    {
        // verifica se o utilizador atual tem acesso às funções deste controlador

        if (!Session::has("Authentication")) {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do administrador");
            Redirect::toRoute('home/login');
            return false;
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'administrador') {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do administrador");
            Redirect::toRoute('home/login');
            return false;
        }

        return true;
    }

    // =========================================================
    //                          CONTAS
    // =========================================================

    public function contasIndex()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        // obter todos os dados da base de dados de perfis especificos       
        $users = User::all(array('conditions' => array('perfil = ? OR perfil = ?', "gestor de voo", "operador de checkin")));

        return View::make('admin.contas', ['users' => $users]);
    }

    public function editar($idutilizador)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        if (!Session::has('Authentication')) {
            return Redirect::toRoute("home/login");
        }

        // obtem o utilizador a editar
        $user = User::find([$idutilizador]);

        // so pode editar se o username for igual ao da sessao
        $authUser = null;
        if (Session::has('Authentication')) {
            $authUser = Session::get('Authentication');
        }

        if (strcmp($user->username, $authUser['user']) == 0 || $authUser['perfil'] == 'administrador') {
            return View::make('home.register', ['user' => $user, 'isAdmin' => true, 'authPerfil' => $authUser['perfil']]);
        }

        if ($user) {
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
    }


    public function eliminar($idutilizador)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $user = User::find([$idutilizador]);

        if (is_null($user)) {
            MensagensHelper::setError( "Não foi possível eliminar o utilizador");
        } else {
            $user->delete();
            MensagensHelper::setSuccess("utilizador com o username " . $user->username . " eliminado com sucesso!");
        }

        Redirect::toRoute('admin/contas');
    }

    // =========================================================
    //                          AEROPORTOS
    // =========================================================

    public function aeroportosIndex()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airports = Airport::all();

        if (is_null($airports) || count($airports) == 0) {
            $airports = [];
        }

        return View::make('admin.aeroportos', ['airports' => $airports]);
    }
}
