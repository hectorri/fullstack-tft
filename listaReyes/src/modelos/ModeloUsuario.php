<?php
namespace App\Modelos;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloUsuario extends Eloquent {

	//Desactivamos timestamps
	public $timestamps = false;

	// Define la llave primaria de la tabla usuarios
	protected $primaryKey = 'email';

	// Define el nombre de la tabla 
	protected $table = 'usuarios';

	// Define los campos que pueden guardarse en la tabla
	protected $fillable = [
		'email',
		'contrasena',
		'nombre',
		'apellidos'
	];
}