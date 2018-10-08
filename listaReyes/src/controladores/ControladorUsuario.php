<?php

namespace App\Controladores;

use App\Modelos\ModeloUsuario as Usuario; // para usar el modelo de usuario
use Slim\Views\Twig; // Las vistas de la aplicación
use Slim\Router; // Las rutas de la aplicación
use Respect\Validation\Validator as v; // para usar el validador de Respect


/**
 * Clase de controlador para el usuario de la aplicación
 */
class ControladorUsuario {

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
            // verifica que se reciba una cadena de al menos longitud 2
            v::stringType()->length(2)->validate($args['asd']),
            
			// verifica que se reciba una cadena de al menos longitud 2
            v::stringType()->length(2)->validate($args['apellidos']),

			// verifica que se reciba un correo
            v::email()->validate($args['email']),
            
			// verifica que no esté en blanco la contraseña
            v::notBlank()->validate($args['contrasena'])
        ];
                                                 
    }
	
	/**
	* Verifica la correctud de un conjunto de validaciones
	* @param type array $validaciones - el conjunto de validaciones a evaluar
	* @throws \Exception cuando las validaciones no están en un arreglo
	*/
	public static function verifica($validaciones){
		if(!is_array($validaciones){
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
}