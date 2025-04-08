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
        Schema::create('patients', function (Blueprint $table) {
            $table->id(); //primary key, autoincrementado
            $table->string('name',50);
            $table->date('birthdate');
            $table->string('gender',20);
            $table->text('address');
            $table->string('phone',20);
            $table->string('email',50)->unique()->nullable();
            $table->timestamps();
            /** create_at(fecha/hora) , update_at */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
