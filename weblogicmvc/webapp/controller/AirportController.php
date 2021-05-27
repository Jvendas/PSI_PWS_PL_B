<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class AirportController extends BaseController
{


    public function criar()
    {
        // obtem o aeroporto do POST do form
        $airport = Post::getAll();

        // guarda o aeroporto na base de dados se ainda não existir um nome e pais igual
        $exist = Airport::all(array('conditions' => array('nome = ? AND pais = ?', $airport['nome'], $airport['pais'])));

        if ($exist) {
            Session::set("admin-aeroportos-error", "Já existe o aeroporto com o nome: " . $airport['nome']);
        } else {
            Airport::create($airport);
            Session::set("admin-aeroportos-success", "Aeroporto " . $airport['nome'] . " criado com sucesso!");
        }

        Redirect::toRoute('admin/aeroportos');
    }

    public function editar($idaeroporto)
    {
        $airport = Airport::find([$idaeroporto]);

        if (!is_null($airport)) {
            $airports = Airport::all();
            return View::make('admin.aeroportos', ['airports' => $airports, 'airportToEdit' => $airport]);
        }
    }

    public function atualizar($idaeroporto)
    {
        $airport = Airport::find([$idaeroporto]);
        $airport->update_attributes(Post::getAll());

        if ($airport->is_valid()) {
            $airport->save();
            Redirect::toRoute('admin/aeroportos');
        } else {
            // TODO: show errors
            //Redirect::flashToRoute('aeroporto/editar', ['airport' => $airport]);
        }
    }

    public function eliminar($idaeroporto)
    {
        $airport = Airport::find([$idaeroporto]);

        if (is_null($airport)) {
            Session::set("admin-aeroportos-error", "Não foi possível eliminar o Aeroporto com o id " . $idaeroporto);
        } else {
            $airport->delete();
            Session::set("admin-aeroportos-success", "Aeroporto com o id " . $idaeroporto . " eliminado com sucesso!");
        }

        Redirect::toRoute('admin/aeroportos');
    }
}
