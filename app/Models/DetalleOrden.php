<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;
    protected $table = "detalle_ordenes";

    //relaciones inversas de detalle_ordenes 
    public function producto()  {
        return $this->belongsTo(Producto::class);
    }

    
    public function orden()  {
        return $this->belongsTo(Orden::class);
    }

}
