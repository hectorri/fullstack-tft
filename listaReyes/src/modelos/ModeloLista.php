<?php
namespace App\Modelos;

//importa Eloquent para usarlo en el modelo
use Illuminate\Database\Eloquent\Model as Eloquent;

class ModeloLista extends Eloquent {
	//Desactivamos timestamps
	public $timestamps = false;
	// Define la llave primaria de la tabla listas
	protected $primaryKey = 'id';
	
	// Define el nombre de la tabla 
	protected $table = 'listas';
   
	// Define los campos que pueden llenarse en la tabla
	protected $fillable = [
		'nombre',
		'email'
	];
}