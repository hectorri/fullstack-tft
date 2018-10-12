<?php
namespace App\Modelos;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloProducto extends Eloquent {

	//Desactivamos timestamps
	public $timestamps = false;

	// Define la llave primaria de la tabla productos
	protected $primaryKey = 'id';
	
	// Define el nombre de la tabla 
	protected $table = 'productos';
   
	// Define los campos que pueden guardarse en la tabla
	protected $fillable = [
		'id_lista',
		'nombre_producto',
		'imagen',
		'enlace_compra',
		'comprado'
	];
}