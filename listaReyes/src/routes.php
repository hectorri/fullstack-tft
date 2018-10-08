<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// index
$app->get('/', function($request, $response, $args){
	return $this->view->render($response, "index.twig");
})->setName('inicio');

// ruta para cargar el formulario para crear usuario
$app->get('/registro', function($request, $response, $args){
	return $this->view->render($response, 'formulario_registro.twig');
})->setName('usuario.crear');

// ruta para crear un nuevo usuario
$app->post("/registro", "ControladorUsuario:crea");
