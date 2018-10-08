<?php
namespace App\Modelos;

//importa Eloquent para usarlo en el modelo
use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloUsuario extends Eloquent {
   // Define la llave primaria de la tabla usuarios
   protected $primaryKey = 'email';

   // Define el nombre de la tabla 
   protected $table = 'usuarios';
   
     // Define los campos que pueden llenarse en la tabla
   protected $fillable = [
       'email',
       'contrasena',
       'nombre',
	   'apellidos'
   ];
   
   /**
    * Función para crear un usuario
    * @param type Slim\Http\Request $request - solicitud http
    * @param type Slim\Http\Response $response - respuesta http
    */
    public function crea($request, $response, $args) {
		/*
		 getParsedBody() toma los parametros del cuerpo de $request que estén
		 como json o xml y lo parsea de un modo que PHP lo  entienda 
		*/
		$param = $request->getParsedBody(); 
        
		$validaciones = $this->validaArgs($param); // hace las validaciones
		if(verifica($validaciones)){
		
			// evalua si el correo ya existe en la base de datos
            $correo_existente = Usuario::where('email', $atr['email'])->get()->first();
        
			// si el correo ya existe manda un error 403
            if($correo_existente){
                echo->$this->error('YA_ESTÁ_REGISTRADO_EL_CORREO',
                                   $request->getUri()->getPath(),
                                   404);
                return $this->response->withStatus(403);
            } else {
            
				//crea un nuevo usuario a partir del modelo
                $usuario = new Usuario;

                // asigna cada elemento del arreglo $atr con su columna en la tabla usuarios
                $usuario->nombre = $atr['nombre'];
				$usuario->apellidos = $atr['apellidos'];
                $usuario->email = $atr['email'];
                $usuario->contrasena = $atr['contrasena'];

                $usuario->save(); //guarda el usuario

                // crea una ruta para el usuario con su id
                $path =  $request->getUri()->getPath() . '/' . $usuario->id;

                return $response->withStatus(201); // el usuario fue creado con éxito
            }
		}
	}
}