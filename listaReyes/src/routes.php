<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// index
$app->get('/inicio', function($request, $response, $args){
	return $this->view->render($response, "index.twig");
})->setName('inicio');

// ruta para cargar el formulario para crear usuario
$app->get('/registro', function($request, $response, $args){
	return $this->view->render($response, 'formulario_registro.twig');
})->setName('usuario.registro');

// ruta para crear un nuevo usuario
$app->post('/registro', 'ControladorUsuario:registro');

// ruta para cargar el formulario para crear usuario
$app->get('/acceso', function($request, $response, $args){
	return $this->view->render($response, 'formulario_acceso.twig');
})->setName('usuario.acceso');

// ruta para cargar el formulario para crear usuario
$app->get('/registrado', function($request, $response, $args){
	return $this->view->render($response, 'usuario_registrado.twig');
})->setName('usuario.registrado');

// ruta para crear un nuevo usuario
$app->post('/acceso', 'ControladorUsuario:login');