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
    private function utilizadorIsValid()
    {
        // verifica se o utilizador atual tem acesso às funções deste controlador

        if (!Session::has("Authentication")) {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do passageiro");
            Redirect::toRoute('home/login');
            return false;
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'passageiro') {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do passageiro");
            Redirect::toRoute('home/login');
            return false;
        }

        return true;
    }

    public function index()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airports = Airport::all();
        $idas = [];
        $voltas = [];

        return View::make('passageiro.voos', ['airports' => $airports, 'idas' => $idas, 'voltas' => $voltas]);
    }

    public function consultar()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        // obtem do POST do form
        $formData = Post::getAll();
        $airports = Airport::all();
        $listaIdas = [];
        $listaVoltas = [];

        $formDataInvalido = false;

        if (empty($formData["origem"]) || empty($formData["destino"]) || empty($formData["dataPartida"])) {
            $formDataInvalido = true;
        }

        if (array_key_exists('idaCheck', (array)$formData) && !empty($formData["dataRegresso"])) {
            $formDataInvalido = true;
        }

        if (!array_key_exists('idaCheck', (array)$formData) && empty($formData["dataRegresso"])) {
            $formDataInvalido = true;
        }

        if ($formDataInvalido) {
            MensagensHelper::setError("Consulta invalida");
            return Redirect::toRoute("passageiro/voos");
        }

        // obter idas
        $escalasIdas = Scale::all(array('conditions' => array("idaeroportoorigem = ? AND idaeroportodestino = ? AND horarioorigem LIKE '%" . $formData['dataPartida'] . "%'", $formData['origem'], $formData['destino'])));
        $escalasVoltas = [];

        // obter voltas
        if (!empty($formData["dataRegresso"])) {
            $escalasVoltas = Scale::all(array('conditions' => array("idaeroportoorigem = ? AND idaeroportodestino = ? AND horarioorigem LIKE '%" . $formData['dataRegresso'] . "%'", $formData['destino'], $formData['origem'])));
        }

        if (empty($escalasIdas) && empty($escalasVoltas)) {
            MensagensHelper::setWarning("Nao foi possivel obter voos para a consulta indicada");
            return Redirect::toRoute("passageiro/voos");
        }

        $warningMessage = null;
        if (!empty($formData["dataRegresso"]) && empty($escalasVoltas)) {
            $warningMessage = "Nao foi possivel obter voltas para a data de regresso indicada";
        }

        foreach ($escalasIdas as $escala) {

            $flight = Flight::find([$escala->idvoo]);
            $aeroportoOrigem = Airport::obter($escala->idaeroportoorigem);
            $aeroportoDestino = Airport::obter($escala->idaeroportodestino);

            $voo = new Dadosvoo();
            $voo->idvoo = $escala->idvoo;
            $voo->nomeAeroportoOrigem =  $aeroportoOrigem->nome;
            $voo->paisAeroportoOrigem =  $aeroportoOrigem->pais;
            $voo->nomeAeroportoDestino =  $aeroportoDestino->nome;
            $voo->paisAeroportoDestino =  $aeroportoDestino->pais;
            $voo->horarioPartida = $escala->horarioorigem->format('Y-m-d h:i');
            $voo->horarioChegada = $escala->horariodestino->format('Y-m-d h:i');
            $voo->distancia = $escala->distancia;
            $voo->preco = $flight->preco;
            $voo->descricao = $flight->descricao;

            array_push($listaIdas, $voo);
        }

        foreach ($escalasVoltas as $escala) {

            $flight = Flight::find([$escala->idvoo]);
            $aeroportoOrigem = Airport::obter($escala->idaeroportoorigem);
            $aeroportoDestino = Airport::obter($escala->idaeroportodestino);

            $voo = new Dadosvoo();
            $voo->idvoo = $escala->idvoo;
            $voo->nomeAeroportoOrigem =  $aeroportoOrigem->nome;
            $voo->paisAeroportoOrigem =  $aeroportoOrigem->pais;
            $voo->nomeAeroportoDestino =  $aeroportoDestino->nome;
            $voo->paisAeroportoDestino =  $aeroportoDestino->pais;
            $voo->horarioPartida = $escala->horarioorigem->format('Y-m-d h:i');
            $voo->horarioChegada = $escala->horariodestino->format('Y-m-d h:i');
            $voo->distancia = $escala->distancia;
            $voo->preco = $flight->preco;
            $voo->descricao = $flight->descricao;

            array_push($listaVoltas, $voo);
        }

        return View::make('passageiro.voos', ['airports' => $airports, 'idas' => $listaIdas, 'voltas' => $listaVoltas, 'formData' => $formData, 'warningMessage' => $warningMessage]);
    }

    public function comprarPassagens()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $username = Session::get('Authentication')["user"];
        $user = User::find(array('conditions' => array('username = ?', $username)));
        $idutilizador = $user->idutilizador;

        $formData = Post::getAll();
        $idVooIda = $formData['idvooida'];
        $idVooVolta = $formData['idvoovolta'];

        $vooIda = Flight::find(array('conditions' => array('idvoo = ?', $idVooIda)));
        $totalPassagem = $vooIda->preco;

        if (!empty($idVooVolta)) {
            $vooVolta = Flight::find(array('conditions' => array('idvoo = ?', $idVooVolta)));
            $totalPassagem = $totalPassagem +  $vooVolta->preco;
        } else {
            $idVooVolta = $idVooIda;
        }

        $datetimeNow = (new DateTime())->format('Y-m-d h:i:s');

        $query = "INSERT INTO tickets (idpassagem,idvooida,idvoovolta,datacompra,preco,checkin,idutilizador) VALUES(default,'$idVooIda','$idVooVolta','$datetimeNow','$totalPassagem', 0,'$idutilizador')";
        Ticket::query($query);

        Redirect::toRoute('passageiro/passagens');
    }

    public function passagensView()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $tickets = Ticket::All();
        $listaPassagens = [];

        foreach ($tickets as $ticket) {

            $escalasIda = Scale::all(array('conditions' => array('idvoo = ?', $ticket->idvooida)));
            $escalasVolta = Scale::all(array('conditions' => array('idvoo = ?', $ticket->idvoovolta)));

            if (count($escalasIda) == 0 || count($escalasVolta) == 0) {
                continue;
            }

            // === IDAS ===
            // obter as origens e destinos das idas e voltas
            $escalaIdaOrigem = array_values($escalasIda)[0]; // obter a primeira posição
            $escalaIdaDestino = end($escalasIda); // obter a ultima posiçao
            // obter os ids dos aeroportos origem e destinos das idas e voltas
            $idAeroportoIdaOrigem = $escalaIdaOrigem->idaeroportoorigem;
            $idAeroportoIdaDestino = $escalaIdaDestino->idaeroportodestino;
            // obter os aeroportos origem e destinos das idas e voltas
            $aeroportoIdaOrigem = Airport::obter($idAeroportoIdaOrigem);
            $aeroportoIdaDestino = Airport::obter($idAeroportoIdaDestino);

            // === VOLTAS ===
            $escalaVoltaOrigem  = null;
            $escalaVoltaDestino  = null;
            $idAeroportoVoltaOrigem  = null;
            $idAeroportoVoltaDestino  = null;
            $aeroportoVoltaOrigem  = null;
            $aeroportoVoltaDestino  = null;

            if (!is_null($escalasVolta)) {
                $escalaVoltaOrigem = array_values($escalasVolta)[0];
                $escalaVoltaDestino = end($escalasVolta);
                $idAeroportoVoltaOrigem = $escalaVoltaOrigem->idaeroportoorigem;
                $idAeroportoVoltaDestino = $escalaVoltaDestino->idaeroportodestino;
                $aeroportoVoltaOrigem = Airport::obter($idAeroportoVoltaOrigem);
                $aeroportoVoltaDestino = Airport::obter($idAeroportoVoltaDestino);
            }

            // dados para a vista
            $ticketScale = new TicketScale();
            $ticketScale->idpassagem = $ticket->idpassagem;
            $ticketScale->ida = $this->getNomeViagem($aeroportoIdaOrigem, $aeroportoIdaDestino);
            if (!is_null($escalasVolta)) {
                $ticketScale->volta = $this->getNomeViagem($aeroportoVoltaOrigem, $aeroportoVoltaDestino);
            }
            $ticketScale->dataCompra = $ticket->datacompra;
            $ticketScale->preco = $ticket->preco;
            $ticketScale->checkin = $ticket->checkin;

            array_push($listaPassagens, $ticketScale);
        }

        return View::make('passageiro.passagens', ['tickets' => $listaPassagens]);
    }

    private function getNomeViagem($aeroportoOrigem, $aeroportoDestino)
    {
        return $aeroportoOrigem->nome . " (" . $aeroportoOrigem->pais . ")" . " > " . $aeroportoDestino->nome . " (" . $aeroportoDestino->pais . ")";
    }
}
