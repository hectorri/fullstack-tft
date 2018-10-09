<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// index
$app->get('/', function($request, $response, $args){
	return $this->view->render($response, "index.twig");
})->setName('inicio');
$app->get('/inicio', function($request, $response, $args){
	return $this->view->render($response, "index.twig");
});

// ruta para cargar el formulario para crear usuario
$app->get('/registro', function($request, $response, $args){
	return $this->view->render($response, 'formulario_registro.twig');
})->setName('usuario.registro');

// ruta para crear un nuevo usuario
$app->post('/registro', 'ControladorUsuario:registro');

// ruta para cargar el formulario para crear usuario
$app->get('/acceso', function($request, $response, $args){
	return $this->view->render($response, 'formulario_acceso.twig');
})->setName('usuario.login');

// ruta para cargar el formulario para crear usuario
$app->get('/registrado', function($request, $response, $args){
	return $this->view->render($response, 'usuario_registrado.twig');
})->setName('usuario.registrado');

$app->post('/acceso', 'ControladorUsuario:login');

$app->get('/salir', 'ControladorUsuario:logout')->setName('usuario.logout');

// ruta para cargar el formulario para crear una lista
$app->get('/nuevaLista', function($request, $response, $args){
	return $this->view->render($response, 'formulario_lista.twig');
})->setName('lista.nueva');

// ruta para crear una nueva lista
$app->post('/nuevaLista', 'ControladorLista:nueva');

// ruta para cargar las listas de un usuario
$app->get('/misListas', 'ControladorLista:misListas')->setName('lista.misListas');

// ruta para cargar el detalle de una lista
$app->get('/lista/{idLista}/{nombreLista}', 'ControladorProducto:listarProductos')->setName('producto.lista');

// ruta para cargar el formulario para crear producto
$app->get('/nuevoProducto/{idLista}', function($request, $response, $args){
	return $this->view->render($response, 'formulario_producto.twig', array(
        'idLista' => $args['idLista']));
})->setName('producto.nuevo');

// ruta para crear un producto
$app->post('/nuevoProducto', 'ControladorProducto:nuevo');