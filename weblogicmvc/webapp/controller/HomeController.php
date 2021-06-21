<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use Carbon\Carbon;
use ArmoredCore\WebObjects\Debug;

class HomeController extends BaseController
{
    public function index()
    {
        return View::make('home.index');
    }

    // public function setsession()
    // {
    //     $dataObject = MetaArmCoreModel::getComponents();
    //     Session::set('object', $dataObject);

    //     Redirect::toRoute('home/worksheet');
    // }

    // public function showsession()
    // {
    //     $res = Session::get('object');
    //     var_dump($res);
    // }

    // public function destroysession()
    // {
    //     Session::destroy();
    //     Redirect::toRoute('home/worksheet');
    // }
}
