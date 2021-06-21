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

// home
Router::get('/',                                'HomeController/index');
Router::get('home/',                            'HomeController/index');
Router::get('home/index',                       'HomeController/index');

// login 
Router::get('home/login',                       'UserController/loginIndex');
Router::post('home/login',                      'UserController/login');
Router::post('home/logout',                     'UserController/logout');
Router::get('home/logout',                      'UserController/logout');

// user
Router::get('user/register',                    'UserController/registarIndex');
Router::post('user/register',                   'UserController/registar');
Router::get('user/perfil',                      'UserController/perfilView');
Router::post('user/atualizar',                  'UserController/atualizar');

// admin contas
Router::get('admin/',                           'AdminController/contasIndex');
Router::get('admin/contas',                     'AdminController/contasIndex');
Router::post('user/eliminar',                   'AdminController/eliminar');
Router::post('user/editar',                     'AdminController/editar');

// admin aeroports
Router::get('admin/aeroportos',                'AdminController/aeroportosIndex');
Router::post('aeroporto/criar',                'AirportController/criar');
Router::post('aeroporto/eliminar',             'AirportController/eliminar');
Router::post('aeroporto/editar',               'AirportController/editar');
Router::post('aeroporto/atualizar',            'AirportController/atualizar');

//Passageiro
Router::get('passageiro/',                     'PassageiroController/index');
Router::get('passageiro/voos',                  'PassageiroController/index');
Router::post('passageiro/voos',                 'PassageiroController/consultar');
Router::post('passageiro/comprar',              'PassageiroController/comprarPassagens');
Router::get('passageiro/passagens',             'PassageiroController/passagensView');

// Gestor Voo
Router::get('gestor/',                           'GestorController/indexVoos');
Router::get('gestor/voos',                       'GestorController/indexVoos');
Router::get('gestor/escalas',                    'GestorController/escalas');
Router::post('voo/criar',                        'GestorController/criarVoo');
Router::post('voo/editar',                       'GestorController/editarVoo');
Router::post('voo/atualizar',                    'GestorController/atualizarVoo');
Router::post('voo/eliminar',                     'GestorController/eliminarVoo');

// Gestor Aviao
Router::get('gestor/avioes',                     'GestorController/indexAvioes');
Router::post('aviao/criar',                      'GestorController/criarAviao');
Router::post('aviao/editar',                     'GestorController/editarAviao');
Router::post('aviao/atualizar',                  'GestorController/atualizarAviao');
Router::post('aviao/eliminar',                   'GestorController/eliminarAviao');

// Gestor Escalas
Router::get('gestor/escalas',                   'GestorController/indexEscalas');
Router::post('escala/criar',                    'GestorController/criarEscala');
Router::post('escala/editar',                   'GestorController/editarEscala');
Router::post('escala/atualizar',                'GestorController/atualizarEscala');
Router::post('escala/eliminar',                 'GestorController/eliminarEscala');

// Operador de Checkin
Router::get('checkin/',                          'CheckinController/index');
Router::get('checkin/historico',                 'CheckinController/historico');
Router::post('checkin/',                         'CheckinController/checkin');

// tests
Router::get('test/index',  'TestController/index');







/************** End of URLEncoder Routing Rules ************************************/
