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
}