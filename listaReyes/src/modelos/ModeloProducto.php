<?php
namespace App\Modelos;

//importa Eloquent para usarlo en el modelo
use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloProducto extends Eloquent {
	//Desactivamos timestamps
	public $timestamps = false;
	// Define la llave primaria de la tabla productos
	protected $primaryKey = 'id';
	
	// Define el nombre de la tabla 
	protected $table = 'productos';
   
	// Define los campos que pueden llenarse en la tabla
	protected $fillable = [
		'id_lista',
		'nombre_producto',
		'descripcion',
		'imagen',
		'enlace_compra'
	];
}