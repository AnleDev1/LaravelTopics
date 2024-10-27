<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Marca extends Model
{
    use HasFactory;

    //Relacion 1:N de marcas con productos. marcas siendo 1
    public function productos(){
        return $this->hasMany(Producto::class);
    }

}
