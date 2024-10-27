<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = "ordenes";

    //Relacion de la tabla ordenes con detalle_ordenes
    public function detalle_ordenes()  {
        return $this->hasMany(DetalleOrden::class);
    }

    //relacion inversa con la tabla user
    public function user()  {
        return $this->belongsTo(User::class);
    }

}
