<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_technology', function (Blueprint $table) {

            $table->id(); //il campo id non è necessario

            $table->foreignId('project_id')->constrained()->cascadeOnDelete(); //cascadeOnDelete  si applica solo alla tabella ponte non alle tabelle in relazione
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_technology');
    }
};
