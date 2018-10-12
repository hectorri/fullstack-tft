<?php

namespace App\Controladores;

use App\Modelos\ModeloLista as Lista; // para usar el modelo de lista
use Slim\Views\Twig; // Las vistas de la aplicación
use Slim\Router; // Las rutas de la aplicación
use Respect\Validation\Validator as v; // para usar el validador de Respect


/**
 * Clase de controlador para producto
 */
class ControladorLista {

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
            v::stringType()->length(2)->validate($args['nombre']),
        ];
        
		return $valid;
    }
	
	/**
	* Verifica un conjunto de validaciones
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
	* @param type array $args - argumentos para la función
    */
    public function nueva($request, $response, $args) {
		$param = $request->getParsedBody();
		$validaciones = $this->validaArgs($param);
		if($this->verifica($validaciones)){
			//crea una nueva Lista a partir del modelo
			$lista = new Lista;

			$lista->nombre = $param['nombre'];
			$lista->email = $_SESSION['email'];
			$lista->save(); //guarda la lista

			return $response->withRedirect('misListas', 301);
		}
	}

	/**
    * Función para eliminar una lista
    * @param type Slim\Http\Request $request - solicitud http
	* @param type Slim\Http\Response $response - respuesta http
	* @param type array $args - argumentos para la función
    */
    public function eliminar($request, $response, $args) {
		$param = $request->getParsedBody();
		Lista::where('ID', $param['id'])->delete();
		return $this->view->render($response, 
		'listado_listas.twig', 
		['listas' => Lista::where('email', $_SESSION['email'])->get()]);
	}

	/**
     * Obtiene todas las listas de la tabla listas y los envía a la vista
	 * @param type Slim\Http\Request $request - solicitud http
	 * @param type Slim\Http\Response $response - respuesta http
	 * @param type array $args - argumentos para la función
     */
    public function misListas($request, $response, $args){
		return $this->view->render($response, 
		'listado_listas.twig', 
		['listas' => Lista::where('email', $_SESSION['email'])->get()]);
	}

	/**
     * Se encarga de realizar la notificación por correo de la lista compartida
	 * @param type Slim\Http\Request $request - solicitud http
	 * @param type Slim\Http\Response $response - respuesta http
	 * @param type array $args - argumentos para la función
     */
    public function compartir($request, $response, $args){
		$param = $request->getParsedBody();
		$para      = $param['email'];
		$titulo    = $_SESSION['nombre'].' ha compartido una lista contigo';
		$mensaje   = 'Puede visitar la lista compartida en el siguiente enlace: ';
		$cabeceras = 'From: listaRegalos@listaRegalos.com' . "\r\n" .
			'Reply-To: '.$_SESSION['email'] . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		ini_set("SMTP","smtp.gmail.com");
		//mail($para, $titulo, $mensaje, $cabeceras);

		return $this->view->render($response, 
					'plantilla_mensaje.twig', 
					['mensaje' => 'Nota desarrollo: El destinatario habría recibido la lista por correo electrónico, 
						es necesario establecer los parámetros de acceso al servidor SMTP en ControladorLista.php:compartir()',
					 'destino' => './misListas',
					 'textoDestino' => 'Volver a mis listas']);
	}
}