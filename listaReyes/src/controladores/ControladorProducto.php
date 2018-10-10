<?php

namespace App\Controladores;

use App\Modelos\ModeloProducto as Producto; // para usar el modelo de producto
use Slim\Views\Twig; // Las vistas de la aplicación
use Slim\Router; // Las rutas de la aplicación
use Respect\Validation\Validator as v; // para usar el validador de Respect
use Slim\Http\UploadedFile;

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
    * Función para crear un producto
    * @param type Slim\Http\Request $request - solicitud http
    * @param type Slim\Http\Response $response - respuesta http
    */
    public function nuevo($request, $response, $args) {
		
		$param = $request->getParsedBody();
		$validaciones = $this->validaArgs($param); // hace las validaciones
		if($this->verifica($validaciones)){
			$archivo = $request->getUploadedFiles();
			$imagen = $archivo['imagen'];
			$nombreArchivo = $this->guardarImagen($imagen);

			//crea un nuev Producto a partir del modelo
			$producto = new Producto;

			$producto->id_lista = $param['id_lista'];
			$producto->nombre_producto = $param['nombre_producto'];
			$producto->imagen = $nombreArchivo;
			$producto->enlace_compra = $param['enlace_compra'];						
			$producto->comprado = 0;
			$producto->save(); //guarda el producto

			$url = $this->router->pathFor('producto.lista', ['idLista' => $param['id_lista'], 'nombreLista' => $param['nombreLista']]);
			return $response->withStatus(301)->withHeader('Location', $url);
		}
	}

	public static function guardarImagen(UploadedFile $imagen){
		$extension = pathinfo($imagen->getClientFilename(), PATHINFO_EXTENSION);
		$basename = bin2hex(random_bytes(8));
		$nombreArchivo = sprintf('%s.%0.8s', $basename, $extension);
		$imagen->moveTo("../public/imagenes" . DIRECTORY_SEPARATOR . $nombreArchivo);
		return $nombreArchivo;
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

	/**
	 * Cambia el estado a comprado de un producto
     * @param type Slim\Http\Request $request - la solicitud http
     * @param type Slim\Http\Response $response - la respuesta http
     * @param type array $args - argumentos para la función
	*/
	public function cambiarEstado($request, $response, $args) {
		$param = $request->getParsedBody();
		Producto::where('ID', $param['id'])->update(['comprado' => $param['estado']]);
		$url = $this->router->pathFor('producto.lista', ['idLista' => $param['id_lista'], 'nombreLista' => $param['nombreLista']]);
		return $response->withStatus(301)->withHeader('Location', $url);
	}

	/**
	 * Editar un producto
     * @param type Slim\Http\Request $request - la solicitud http
     * @param type Slim\Http\Response $response - la respuesta http
     * @param type array $args - argumentos para la función
	*/
	public function editar($request, $response, $args) {
		// busca un usuario la id del arreglo de parametros en la tabla usuarios
		$usuario = Usuario::find((int)$args['id']);
		
		if(!$usuario){
			/*
			Si no hay un usuario con la id de los parametros, entonces obtiene la uri de la solicitud,
			redirecciona a la lista de usuarios y regresa una respuesta con la uri y un estado 404 (not found)
			*/
            $status = 404; 
			$uri = $request->getUri()->withQuery('')->withPath($this->router->pathFor('listaUsuarios'));
            return $response->withRedirect((string)$uri, $status);
		} else{
			$data = $request->getParsedBody(); // guarda los argumentos de la solicitud en un arreglo
			$validaciones = $this->valida($data); // valida los datos
			if (verifica($validaciones)){
				$usuario->update($data); // Eloquent actualiza la información en la tabla 
				
				// regresa una respuesta con la uri y redirecciona a la vista especifica del usuario
				$uri = $request->getUri()->withQuery('')->withPath($this->router->pathFor('usuario', ['id' => $usuario->id]));
                return $response->withRedirect((string)$uri);
			}
		}
	}
}