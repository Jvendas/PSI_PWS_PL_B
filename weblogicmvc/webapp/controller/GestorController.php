<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Post;

class GestorController extends BaseController
{
    private function utilizadorIsValid()
    {
        // verifica se o utilizador atual tem acesso às funções deste controlador

        if (!Session::has("Authentication")) {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do gestor de voo");
            Redirect::toRoute('home/login');
            return false;
        }

        $userAuth = Session::get('Authentication');

        if ($userAuth["perfil"] != 'gestor de voo') {
            MensagensHelper::setError("Nao tem permissoes para  aceder à area do gestor de voo");
            Redirect::toRoute('home/login');
            return false;
        }

        return true;
    }

    // =========================================================
    //                          VOOS
    // =========================================================

    public function indexVoos()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $flights = Flight::all();
        $flightToEdit = null;

        return View::make('gestor.voos', ['flights' => $flights, 'flightToEdit' => $flightToEdit]);
    }

    public function criarVoo()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $formData = Post::getALl();
        $flight = Flight::create($formData);

        if (is_null($flight)) {
            MensagensHelper::setError("Não foi possível criar o Voo");
        } else {
            MensagensHelper::setSuccess("Voo criado com sucesso");
        }

        Redirect::toRoute('gestor/voos');
    }

    public function editarVoo($idvoo)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $flightToEdit = Flight::obter($idvoo);

        if (is_null($flightToEdit)) {
            MensagensHelper::setError("Não foi possível obter o voo");
            Redirect::toRoute('gestor/voos');
            return;
        }

        $flights = Flight::all();
        $listaVoos = [];

        foreach ($flights as $flight) {
            $flightAirport = new FlightAirport();
            $flightAirport->idvoo = $flight->idvoo;
            $flightAirport->descricao = $flight->descricao;
            $flightAirport->preco = $flight->preco;

            array_push($listaVoos, $flightAirport);
        }

        return View::make('gestor.voos', ['flights' => $listaVoos, 'flightToEdit' => $flightToEdit]);
    }

    public function atualizarVoo($idvoo)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $flight = Flight::obter($idvoo);

        if (is_null($flight)) {
            MensagensHelper::setError("Não foi possível atualizar o voo");
        } else {

            $atualizou = $flight->update_attributes(Post::getAll());

            if ($atualizou) {
                MensagensHelper::setSuccess("Atualizado o voo com sucesso");
                $flight->save();
            } else {
                MensagensHelper::setError("Não foi possível atualizar o voo");
            }
        }

        Redirect::toRoute('gestor/voos');
    }

    public function eliminarVoo($idvoo)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $flight = Flight::obter($idvoo);

        if (is_null($flight)) {
            MensagensHelper::setError("Não foi possível eliminar o voo");
        } else {

            $apagou = $flight->delete();

            if ($apagou) {
                MensagensHelper::setSuccess("Voo eliminado com sucesso");
            } else {
                MensagensHelper::setError("Não foi possível eliminar o voo");
            }
        }

        Redirect::toRoute('gestor/voos');
    }

    // =========================================================
    //                          AVIÕES
    // =========================================================
    public function indexAvioes()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airplaneToEdit = null;
        $airplanes = Airplane::All();

        return View::make('gestor.avioes', ['airplanes' => $airplanes, 'airplaneToEdit' => $airplaneToEdit]);
    }

    public function criarAviao()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airplane = Post::getAll();

        $exist = Airplane::all(array('conditions' => array('referencia = ?', $airplane['referencia'])));
        if ($exist) {
            MensagensHelper::setError("Já existe um avião com a referència: " . $airplane['referencia']);
        } else {
            Airplane::create($airplane);
            MensagensHelper::setSuccess("Avião " . $airplane['referencia'] . " criado com sucesso");
        }

        Redirect::toRoute('gestor/avioes');
    }

    public function editarAviao($idaviao)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airplaneToEdit = Airplane::obter($idaviao);

        if (!is_null($airplaneToEdit)) {
            $airplanes = Airplane::all();
            return View::make('gestor.avioes', ['airplanes' => $airplanes, 'airplaneToEdit' => $airplaneToEdit]);
        }

        Redirect::toRoute('gestor/avioes');
    }

    public function atualizarAviao($idaviao)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airplane = Airplane::obter($idaviao);
        $airplane->update_attributes(Post::getAll());
        $airplane->save();

        MensagensHelper::setSuccess("Aviao atualizado com sucesso");

        Redirect::toRoute('gestor/avioes');
    }

    public function eliminarAviao($idaviao)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $airplane = Airplane::obter($idaviao);

        if (is_null($airplane)) {
            MensagensHelper::setError("Não foi possível eliminar o aviao");
        } else {

            $apagou = $airplane->delete();

            if ($apagou) {
                MensagensHelper::setSuccess("Aviao " . $airplane->referencia  . " eliminado com sucesso");
            } else {
                MensagensHelper::setError("Não foi possível eliminar o aviao");
            }
        }

        Redirect::toRoute('gestor/avioes');
    }

    private function getNomeAviao($airplane)
    {
        return "#" . $airplane->referencia . " - " . $airplane->tipoaviao . " (" . $airplane->lotacaototal . ")";
    }

    // =========================================================
    //                          ESCALAS
    // =========================================================

    public function indexEscalas()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $scales = Scale::all();
        $airports = Airport::All();
        $flights = Flight::All();
        $airplanes = Airplane::All();
        $listaEscalas = [];
        $scaleToEdit = null;
        $scaleAirplaneToEdit = null;

        foreach ($scales as $scale) {

            $aeroportoOrigem = Airport::obter($scale->idaeroportoorigem);
            $aeroportoDestino = Airport::obter($scale->idaeroportodestino);

            $scaleAirplane = ScaleAirplane::find(array('conditions' => array('idescala = ?', $scale->idescala)));
            $airplane = Airplane::find(array('conditions' => array('idaviao = ?', $scaleAirplane->idaviao)));
            $nomeAviao = $nomeAviao = $this->getNomeAviao($airplane);

            $scaleAirport = new ScaleAirport();
            $scaleAirport->idvoo = $scale->idvoo;
            $scaleAirport->idescala = $scale->idescala;
            $scaleAirport->idaeroportoOrigem = $scale->idaeroportoorigem;
            $scaleAirport->nomeAeroportoOrigem = $aeroportoOrigem->nome;
            $scaleAirport->paisAeroportoOrigem = $aeroportoOrigem->pais;
            $scaleAirport->idaeroportoDestino = $scale->idaeroportodestino;
            $scaleAirport->nomeAeroportoDestino = $aeroportoDestino->nome;
            $scaleAirport->paisAeroportoDestino = $aeroportoDestino->pais;
            $scaleAirport->horarioPartida = $scale->horarioorigem;
            $scaleAirport->horarioDestino = $scale->horariodestino;
            $scaleAirport->distancia = $scale->distancia;
            $scaleAirport->aviao = $nomeAviao;

            array_push($listaEscalas, $scaleAirport);
        }

        return View::make('gestor.escalas', ['scales' => $listaEscalas, 'flights' => $flights, 'scaleToEdit' => $scaleToEdit, 'airports' => $airports, 'airplanes' => $airplanes, 'scaleAirplaneToEdit' => $scaleAirplaneToEdit]);
    }

    public function criarEscala()
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        // registar a escala
        $formData = Post::getAll();
        $formData['horarioorigem'] = $formData['horarioorigem'] . ":00";
        $formData['horariodestino'] = $formData['horariodestino'] . ":00";

        $query = "INSERT INTO scales (idescala,idvoo,idaeroportoorigem,idaeroportodestino,horarioorigem,horariodestino,distancia) VALUES(default,'$formData[idvoo]','$formData[idaeroportoorigem]','$formData[idaeroportodestino]','$formData[horarioorigem]','$formData[horarioorigem]','$formData[distancia]')";
        Scale::query($query);

        $lastScale = Scale::last();
        $lastScaleId = $lastScale->idescala;

        if (is_null($lastScale)) {
            MensagensHelper::setError("Erro ao criar a escala");
            Redirect::toRoute('gestor/escalas');
            return;
        } else {
            MensagensHelper::setSuccess("Escala criada com sucesso");
        }

        // registar a escala associado ao aviao
        $scaleAirplane = new ScaleAirplane();
        $scaleAirplane->idaviao = $formData['aviao'];
        $scaleAirplane->idescala = $lastScaleId;
        $scaleAirplane->numpassageiros = 0;
        $scaleAirplane->save();

        Redirect::toRoute('gestor/escalas');
    }

    public function editarEscala($idescala)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $scaleToEdit = Scale::obter($idescala);

        if (!is_null($scaleToEdit)) {

            $scales = Scale::all();
            $airports = Airport::all();
            $flights = Flight::all();
            $airplanes = Airplane::All();
            $listaEscalas = [];

            foreach ($scales as $scale) {

                $aeroportoOrigem = Airport::obter($scale->idaeroportoorigem);
                $aeroportoDestino = Airport::obter($scale->idaeroportodestino);

                $scaleAirplane = ScaleAirplane::find(array('conditions' => array('idescala = ?', $scale->idescala)));
                $airplane = Airplane::find(array('conditions' => array('idaviao = ?', $scaleAirplane->idaviao)));
                $nomeAviao = $this->getNomeAviao($airplane);

                $scaleAirport = new ScaleAirport();
                $scaleAirport->idvoo = $scale->idvoo;
                $scaleAirport->idescala = $scale->idescala;
                $scaleAirport->idaeroportoOrigem = $scale->idaeroportoorigem;
                $scaleAirport->nomeAeroportoOrigem = $aeroportoOrigem->nome;
                $scaleAirport->paisAeroportoOrigem = $aeroportoOrigem->pais;
                $scaleAirport->idaeroportoDestino = $scale->idaeroportodestino;
                $scaleAirport->nomeAeroportoDestino = $aeroportoDestino->nome;
                $scaleAirport->paisAeroportoDestino = $aeroportoDestino->pais;
                $scaleAirport->horarioPartida = $scale->horarioorigem;
                $scaleAirport->horarioDestino = $scale->horariodestino;
                $scaleAirport->distancia = $scale->distancia;
                $scaleAirport->aviao = $nomeAviao;

                array_push($listaEscalas, $scaleAirport);
            }
        }

        $scaleAirplane  = ScaleAirplane::find(array('conditions' => array('idescala = ?', $scaleToEdit->idescala)));
        $scaleAirplaneToEdit = $scaleAirplane->idaviao;

        return View::make('gestor.escalas', ['scales' => $listaEscalas, 'flights' => $flights, 'scaleToEdit' => $scaleToEdit, 'airports' => $airports, 'scaleAirplaneToEdit' => $scaleAirplaneToEdit, 'airplanes' => $airplanes]);
    }

    public function atualizarEscala($idescala)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        $formData = Post::getAll();

        $formData['horarioorigem'] = $formData['horarioorigem'] . ":00";
        $formData['horariodestino'] = $formData['horariodestino'] . ":00";

        $query = "UPDATE scales SET idvoo='$formData[idvoo]',idaeroportoorigem='$formData[idaeroportoorigem]',idaeroportodestino='$formData[idaeroportodestino]',horarioorigem='$formData[horarioorigem]',horariodestino='$formData[horariodestino]',distancia='$formData[distancia]' WHERE idescala=$idescala";
        Scale::query($query);

        $scaleAirplane = ScaleAirplane::find(array('conditions' => array('idescala = ?', $idescala)));
        $scaleAirplane->idaviao = $formData['aviao'];
        $scaleAirplane->save();

        MensagensHelper::setSuccess("Escala atualizada com sucesso");

        Redirect::toRoute('gestor/escalas');
    }

    public function eliminarEscala($idescala)
    {
        if (!$this->utilizadorIsValid()) {
            return;
        }

        // antes de apagar a escala na tabela das escalas tem de eliminar as relações que exsitem na tabela "sacles_airplanes
        $scaleAirplane = ScaleAirplane::find(array('conditions' => array('idescala = ?', $idescala)));

        if (is_null($scaleAirplane)) {
            MensagensHelper::setError("Não foi possível apagar a escala");
            Redirect::toRoute('gestor/escalas');
            return;
        }

        $apagouScaleAirplane = $scaleAirplane->delete();

        if (!$apagouScaleAirplane) {
            MensagensHelper::setError("Não foi possível apagar a escala");
            Redirect::toRoute('gestor/escalas');
            return;
        }

        // se apagou a relação entao já pode apagar a escala
        $scale = Scale::obter($idescala);
        $apagouScale = $scale->delete();

        if (!$apagouScale) {
            MensagensHelper::setError("Não foi possível apagar a escala");
            Redirect::toRoute('gestor/escalas');
            return;
        }

        MensagensHelper::setSuccess("Escala eliminada com sucesso");
        Redirect::toRoute('gestor/escalas');
    }
}
