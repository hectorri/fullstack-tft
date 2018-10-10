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
            v::stringType()->length(2)->validate($args['nombre']),
            
			// verifica que se reciba una cadena de al menos longitud 2
            v::stringType()->length(2)->validate($args['apellidos']),

			// verifica que se reciba un correo
            v::email()->validate($args['email']),
            
			// verifica que no esté en blanco la contraseña
            v::notBlank()->validate($args['contrasena'])
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
    * Función para crear un usuario
    * @param type Slim\Http\Request $request - solicitud http
    * @param type Slim\Http\Response $response - respuesta http
    */
    public function registro($request, $response, $args) {
		/*
		 getParsedBody() toma los parametros del cuerpo de $request que estén
		 como json o xml y lo parsea de un modo que PHP lo  entienda 
		*/
		$param = $request->getParsedBody(); 
        
		$validaciones = $this->validaArgs($param); // hace las validaciones
		if($this->verifica($validaciones)){
		
			// evalua si el correo ya existe en la base de datos
            $correo_existente = Usuario::where('email', $param['email'])->get()->first();
        
			// si el correo ya existe manda un error 403
            if($correo_existente){
                return $this->view->render($response, 
					'plantilla_mensaje.twig', 
					['mensaje' => 'Dirección de correo ya registrada',
					 'destino' => './acceso',
					 'textoDestino' => 'Iniciar sesión']);
            } else {
            
				//crea un nuevo usuario a partir del modelo
                $usuario = new Usuario;

                // asigna cada elemento del arreglo $param con su columna en la tabla usuarios
                $usuario->nombre = $param['nombre'];
				$usuario->apellidos = $param['apellidos'];
				$usuario->email = $param['email'];
				$hashOpts = [
					'cost' => 12,
				];
                $usuario->contrasena = password_hash($param['contrasena'], PASSWORD_BCRYPT, $hashOpts);

                $usuario->save(); //guarda el usuario

                // crea una ruta para el usuario con su id
                $path =  $request->getUri()->getPath() . '/' . $usuario->id;

                //return $response->withRedirect('registrado', 301); // el usuario fue creado con éxito
				return $this->view->render($response, 
					'plantilla_mensaje.twig', 
					['mensaje' => 'Registro realizado correctamente, ahora puede iniciar sesión',
					 'destino' => './acceso',
					 'textoDestino' => 'Iniciar sesión']);
			}
		}
	}

	public function login($request, $response, $args) {
		$param = $request->getParsedBody();
		$usuarioEncontrado = Usuario::where('email', $param['email'])->get()->first();
		
		if($usuarioEncontrado){
			$contrasenaCorrecta = password_verify($param['contrasena'], $usuarioEncontrado['CONTRASENA']);
			if($contrasenaCorrecta){
				session_destroy();
				session_start();
				$_SESSION['email'] = $usuarioEncontrado['EMAIL'];
				$_SESSION['nombre'] = $usuarioEncontrado['NOMBRE'];
				return $response->withRedirect('inicio', 301);
			}else{
				return $response->withRedirect('contrasenaincorrecta', 301);
			}
		}else{
			return $response->withRedirect('noexistemail', 301);
		}
	}

	public function logout($request, $response, $args) {
		session_destroy();
		return $response->withRedirect('inicio', 301);
	}
}