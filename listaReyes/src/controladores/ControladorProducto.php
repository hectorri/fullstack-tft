<?php

namespace App\Controladores;

use App\Modelos\ModeloProducto as Producto; // para usar el modelo de producto
use Slim\Views\Twig; // Las vistas de la aplicación
use Slim\Router; // Las rutas de la aplicación
use Respect\Validation\Validator as v; // para usar el validador de Respect


/**
 * Clase de controlador para producto
 */
class ControladorProducto {

    // objeto de la clase Twig
    protected $view;
	
	// objeto de la clase Router
	protected $router;

    /**
     * Constructor de la clase Controller
     * @param type Slim\Views\Twig $view - Vista
	 * @param type Slim\Router $router - Ruta
     */
    public function __construct(Twig $view, Router $router){
		$this->view = $view;
		$this->router = $router;
    }
	
    /**
     * Verifica que los parametros que recibe el controlador sean correctos
     * @param type array $args - los argumentos a evaluar
     */
    public function validaArgs($args){
        $valid = [		
            // TODO
        ];
        
		return $valid;
    }
	
	/**
	* Verifica la correctud de un conjunto de validaciones
	* @param type array $validaciones - el conjunto de validaciones a evaluar
	* @throws \Exception cuando las validaciones no están en un arreglo
	*/
	public static function verifica($validaciones){
		if(!is_array($validaciones)){
			throw new \Exception('Las validaciones deben estar en un arreglo');
		} else {
			foreach($validaciones as $v){
				if ($v == false) {
					return false; // todas las validaciones deben cumplirse para que sea correcto
				}
			}
			return true;
		}
	}

	/*-- Funciones del CRUD --*/

	/**
    * Función para crear una lista
    * @param type Slim\Http\Request $request - solicitud http
    * @param type Slim\Http\Response $response - respuesta http
    */
    public function nuevo($request, $response, $args) {
		//TODO
	}

	/**
    * Función para eliminar una lista
    * @param type Slim\Http\Request $request - solicitud http
    * @param type Slim\Http\Response $response - respuesta http
    */
    public function elimina($request, $response, $args) {
		//TODO
	}

	/**
     * Obtiene todos los productos de una lista
	 * @param type Slim\Http\Request $request - solicitud http
	 * @param type Slim\Http\Response $response - respuesta http
     */
    public function listarProductos($request, $response, $args){
		return $this->view->render($response, 
		'detalle_lista.twig', 
		['productos' => Producto::where('ID_LISTA', $args['idLista'])->get(),
		 'idLista' => $args['idLista'],
		 'nombreLista' => $args['nombreLista']]);
	}
}