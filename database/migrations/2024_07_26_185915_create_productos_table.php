<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 350);
            $table->string('imagen', 200);
            $table->integer('existencia');
            $table->decimal('precio', 8, 2);
            $table->string('estado', 1)->default('A');
            /* Construccion de las llaves foraneas */
            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')->references('id')->on('marcas');

            $table->unsignedBigInteger('modelo_id');
            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->timestamps();
        /*  $table->foreignId('modelo_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade'); 
        */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
