<?php

use App\Modelos\ModeloProducto as Producto;
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

//ruta para eliminar una lista
$app->post('/eliminarLista', 'ControladorLista:eliminar')->setName('lista.eliminar');

// ruta para cargar el formulario de compartir lista
$app->get('/compartirLista/{idLista}/{nombreLista}', function($request, $response, $args){
	return $this->view->render($response, 'formulario_compartir.twig', array(
		'idLista' => $args['idLista'],
		'nombreLista' => $args['nombreLista']));
})->setName('lista.compartir');

// ruta para compartir lista
$app->post('/compartirLista/{idLista}/{nombreLista}', 'ControladorLista:compartir');

// ruta para cargar el formulario para crear producto
$app->get('/nuevoProducto/{idLista}/{nombreLista}', function($request, $response, $args){
	return $this->view->render($response, 'formulario_producto.twig', array(
		'idLista' => $args['idLista'],
		'nombreLista' => $args['nombreLista']));
})->setName('producto.nuevo');

// ruta para crear un producto
$app->post('/nuevoProducto/{idLista}/{nombreLista}', 'ControladorProducto:nuevo');

//ruta para cambiar estado de un producto
$app->post('/cambiarEstado', 'ControladorProducto:cambiarEstado')->setName('producto.cambiarEstado');

//ruta para eliminar un producto
$app->post('/eliminarProducto', 'ControladorProducto:eliminar')->setName('producto.eliminar');

// rutas para editar un producto
$app->get('/editarProducto/{id}/{idLista}/{nombreLista}', function($request, $response, $args){
	$producto = Producto::where('ID', $args['id'])->get()->first();
	return $this->view->render($response, 'formulario_producto_editar.twig', array(
		'id' => $args['id'],
		'nombre' => $producto['NOMBRE_PRODUCTO'],
		'imagen' => $producto['IMAGEN'],
		'enlace_compra' => $producto['ENLACE_COMPRA'],
		'idLista' => $args['idLista'],
		'nombreLista' => $args['nombreLista']));
})->setName('usuario.editar');

$app->post('/editarProducto/{id}/{idLista}/{nombreLista}', 'ControladorProducto:editar')->setName('producto.editar');