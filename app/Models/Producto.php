<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    //creando relacion inversa siendo producto N

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function modelo(){
        return $this->belongsTo(Modelo::class);
    }

    //relacion 1:N de productos con detalle ordenes
    public function detalle_ordenes(){
        return $this->hasMany(DetalleOrden::class);
    }


}
