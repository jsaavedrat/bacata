<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{	
	protected $fillable=['id_sucursal','nombre_sucursal','direccion_sucursal','estado_sucursal','nombre_imagen_sucursal'];
    protected $guarded = ['id_sucursal'];
    protected $primaryKey = 'id_sucursal';
}
