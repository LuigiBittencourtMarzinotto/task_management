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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tas_nome');
            $table->dateTime('tas_data_inicio');
            $table->dateTime('tas_data_final');
            $table->string('tas_observacao');
            $table->unsignedBigInteger('status_id');
            $table->char('tas_ativo', 1)->default("S");
            $table->timestamps();
            //foreign key (constraints)
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
