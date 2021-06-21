<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Post;

class CheckinController extends BaseController
{
    private function utilizadorIsValid()
    {
        // verifica se o utilizador atual tem acesso às funções deste controlador

        if (!Session::has("Authentication")) {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do operador de checkin");
            Redirect::toRoute('home/login');
            return false;
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'operador de checkin') {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do operador de checkin");
            Redirect::toRoute('home/login');
            return false;
        }

        return true;
    }

    // =========================================================
    //                          CHECKIN
    // =========================================================

    public function index()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        return View::make('checkin.checkin');
    }

    public function historico()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $tickets = Ticket::All();
        $checkinsOk = [];
        $checkinsEmFalta = [];

        foreach ($tickets as $ticket) {

            $scales = Scale::all(array('conditions' => array('idvoo = ?', $ticket->idvooida)));
            $lastScale = end($scales);
            $firstScale = array_values($scales)[0];

            $user = User::obter($ticket->idutilizador);

            $aeroportoOrigem = Airport::obter($firstScale->idaeroportoorigem);
            $aeroportoDestino = Airport::obter($lastScale->idaeroportodestino);

            $ticketUser = new TicketUser();
            $ticketUser->idpassagem = $ticket->idpassagem;
            $ticketUser->nomeUser = $user->nome;
            $ticketUser->telefoneUser = $user->telefone;
            $ticketUser->nomeAeroportoOrigem = $aeroportoOrigem->nome;
            $ticketUser->paisAeroportoOrigem = $aeroportoOrigem->pais;
            $ticketUser->nomeAeroportoDestino = $aeroportoDestino->nome;
            $ticketUser->paisAeroportoDestino = $aeroportoDestino->pais;
            $ticketUser->dataCompra = $ticket->datacompra;
            $ticketUser->checkin = $ticket->checkin;

            if ($ticket->checkin == 1) {
                array_push($checkinsOk, $ticketUser);
            } else {
                array_push($checkinsEmFalta, $ticketUser);
            }
        }

        return View::make('checkin.historico', ['checkinsOk' => $checkinsOk, 'checkinsEmFalta' => $checkinsEmFalta]);
    }

    public function checkin()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $formData = Post::getAll();

        $passageiro = User::find(array('conditions' => array('telefone = ?', $formData['telefone'])));
        $passagem = Ticket::find(array('conditions' => array('idpassagem = ?', $formData['passagem'])));

        if (is_null($passageiro) || is_null($passagem)) {
            MensagensHelper::setError("Dados de checkin invalidos");
        } else {
            $passagem->checkin = '1';
            $passagem->save();
            MensagensHelper::setSuccess("Checkin com sucesso");
        }

        Redirect::toRoute('checkin/');
    }
}
