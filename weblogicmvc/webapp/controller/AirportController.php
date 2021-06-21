<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AirportController extends BaseController
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
    //                          AEROPORTOS
    // =========================================================

    public function criar()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        // obtem o aeroporto do POST do form
        $airport = Post::getAll();

        // guarda o aeroporto na base de dados se ainda não existir um nome e pais igual
        $exist = Airport::all(array('conditions' => array('nome = ? AND pais = ?', $airport['nome'], $airport['pais'])));

        if ($exist) {
            MensagensHelper::setError( "Já existe o aeroporto com o nome: " . $airport['nome']);
        } else {
            Airport::create($airport);
            MensagensHelper::setSuccess("Aeroporto " . $airport['nome'] . " criado com sucesso");
        }

        Redirect::toRoute('admin/aeroportos');
    }

    public function editar($idaeroporto)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airport = Airport::find([$idaeroporto]);

        if (!is_null($airport)) {
            $airports = Airport::all();
            return View::make('admin.aeroportos', ['airports' => $airports, 'airportToEdit' => $airport]);
        }
    }

    public function atualizar($idaeroporto)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airport = Airport::find([$idaeroporto]);
        $airport->update_attributes(Post::getAll());
        $airport->save();

        MensagensHelper::setSuccess("Aeroporto atualizado com sucesso");

        Redirect::toRoute('admin/aeroportos');
    }

    public function eliminar($idaeroporto)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airport = Airport::find([$idaeroporto]);

        if (is_null($airport)) {
            MensagensHelper::setError( "Não foi possível eliminar o Aeroporto com o id " . $idaeroporto);
        } else {
            $airport->delete();
            MensagensHelper::setSuccess("Aeroporto com o nome " . $airport->nome . " eliminado com sucesso");
        }

        Redirect::toRoute('admin/aeroportos');
    }
}
