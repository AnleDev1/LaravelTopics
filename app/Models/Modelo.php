<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Modelo extends Model
{
    use HasFactory;

    //Relacion 1:N de modelos con productos. modelos siendo 1
    public function productos(){
        return $this->hasMany(Producto::class);
    }
}
