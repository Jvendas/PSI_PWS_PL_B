<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use Carbon\Carbon;
use ArmoredCore\WebObjects\Debug;
use ArmoredCore\WebObjects\Post;

class PassageiroController extends BaseController
{

    //Função mostrar vista do passageiro
    public function index()
    {

        $airports = Airport::all();
        $escalas = [];

        return View::make('passageiro.voos', ['airports' => $airports, 'escalas' => $escalas]);
    }

    public function consultar()
    {

        // obtem do POST do form
        $formData = Post::getAll();
        $airports = Airport::all();
        $listaVoos = [];

        // $escalas = Scale::all(array('conditions' => array("idaeroportoorigem = ? AND idaeroportodestino = ? AND horarioorigem LIKE '%?%' AND horariodestino LIKE '%?%'", $formData['origem'], $formData['destino'], $formData['dataPartida'], $formData['dataRegresso'])));
        $escalas = Scale::all(array('conditions' => array("idaeroportoorigem = ? AND idaeroportodestino = ? AND horarioorigem LIKE '%" . $formData['dataPartida'] . "%' AND horariodestino LIKE '%" . $formData['dataRegresso'] . "%'", $formData['origem'], $formData['destino'])));

        // Usando o modelo criado "DadosVoo" vai ser preenchido de acordo com os dados das escalas da base de dados
        foreach ($escalas as $escala) {

            $voo = new Dadosvoo();
            $flight = Flight::find([$escala->idvoo]);

            $voo->horarioPartida = $escala->horarioorigem->format('Y-m-d h:i');
            $voo->horarioChegada = $escala->horariodestino->format('Y-m-d h:i');
            $voo->distancia = $escala->distancia;
            $voo->preco = $flight->preco;

            array_push($listaVoos, $voo);
        }


        return View::make('passageiro.voos', ['airports' => $airports, 'escalas' => $listaVoos]);
    }

    //retorna a vista das passagens
    public function passagensView()
    {

        //TODO 

        return View::make('passageiro.passagens');
    }

    //retorna a vista do perfil do passageiro
    public function perfilView()
    {
        $username = null;
       
        if (Session::has('Authentication')) {
            $username = Session::get('Authentication')["user"];
        }

        $user = User::find(array('conditions' => array('username = ?', $username)));


        return View::make('home.register', ['user' => $user]);
    }


    // public function perfilUpdate($idutilizador)
    // {
    //     $user = User::find([$idutilizador]);
    //     $user->update_attributes(Post::getAll());

    //     if ($user->is_valid()) {
    //         $user->save();
    //         Redirect::toRoute('passageiro/voos');
    //     } else {
    //         // TODO: show errors

    //     }
    // }
}
