<?php

/**
 * Created by PhpStorm.
 * User: smendes
 * Date: 02-05-2016
 * Time: 11:18
 */

use ArmoredCore\Facades\Router;

/****************************************************************************
 *  URLEncoder/HTTPRouter Routing Rules
 *  Use convention: controllerName/methodActionName
 ****************************************************************************/

Router::get('/',                                'HomeController/index');
Router::get('home/',                            'HomeController/index');
Router::get('home/index',                       'HomeController/index');
Router::get('home/start',                       'HomeController/start');
Router::get('home/admin',                       'AdminController/index');

// admin
Router::get('admin/',                           'AdminController/contas');

// admin contas
Router::get('admin/contas',                     'AdminController/contas');
Router::post('conta/eliminar',                 'AccountsController/eliminar');
Router::post('conta/editar',                   'AccountsController/editar');

// admin aeroports
Router::get('admin/aeroportos',                'AdminController/aeroportos');
Router::post('aeroporto/criar',                'AirportController/criar');
Router::post('aeroporto/eliminar',             'AirportController/eliminar');
Router::post('aeroporto/editar',               'AirportController/editar');
Router::post('aeroporto/atualizar',            'AirportController/atualizar');






// tests
Router::get('test/index',  'TestController/index');







/************** End of URLEncoder Routing Rules ************************************/
