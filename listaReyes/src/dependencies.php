<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// twig
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer']; //nos indica el directorio donde están las plantillas
    $view = new Slim\Views\Twig($settings['template_path'], [
        'cache' => false,]); // puede ser false o el directorio donde se guardará la cache
		
    // instancia y añade la extensión especifica de slim
    $basePath =  rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $view->getEnvironment()->addGlobal('session', $_SESSION);
    return $view;
};

// db
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    return $capsule;
};

// controller:usuarios
$container['ControladorUsuario'] = function($container){
	return new App\Controladores\ControladorUsuario($container['view'], $container['router']);
};

// controller:listas
$container['ControladorLista'] = function($container){
	return new App\Controladores\ControladorLista($container['view'], $container['router']);
};

// controller:productos
$container['ControladorProducto'] = function($container){
	return new App\Controladores\ControladorProducto($container['view'], $container['router']);
};