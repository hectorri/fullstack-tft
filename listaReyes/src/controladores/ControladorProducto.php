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
			if((int)$_FILES['imagen']['size'] == 0){
				$nombreArchivo = '';
			}else{
				$archivo = $request->getUploadedFiles();
				$imagen = $archivo['imagen'];
				$nombreArchivo = $this->guardarImagen($imagen);
			}
			//crea un nuevo Producto a partir del modelo
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
			$param = $request->getParsedBody();
			$producto = Producto::where('ID', $param['id'])->get()->first();

			if((int)$_FILES['imagen']['size'] == 0){
				$nombreArchivo = $producto['IMAGEN'];
			}else{
				$archivo = $request->getUploadedFiles();
				$imagen = $archivo['imagen'];
				$nombreArchivo = $this->guardarImagen($imagen);
			}

			Producto::where('ID', $param['id'])->update(
				['nombre_producto' => $param['nombre_producto'],
				'imagen' => $nombreArchivo]);

			$url = $this->router->pathFor('producto.lista', 
			['idLista' => $param['idLista'], 'nombreLista' => $param['nombreLista']]);
			return $response->withStatus(301)->withHeader('Location', $url);
		}
	}