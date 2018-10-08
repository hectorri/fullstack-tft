<?php
namespace App\Modelos;

//importa Eloquent para usarlo en el modelo
use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloListaProductos extends Eloquent {
	//Desactivamos timestamps
	public $timestamps = false;
	// Define la llave primaria de la tabla lista_productos
	protected $primaryKey = ['id_lista', 'id_producto'];
	
	// Define el nombre de la tabla 
	protected $table = 'lista_productos';
   
	// Define los campos que pueden llenarse en la tabla
	protected $fillable = [
		'id_lista',
		'id_producto',
		'comprado'
	];
}