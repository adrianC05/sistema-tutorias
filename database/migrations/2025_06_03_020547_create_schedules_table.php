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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // FOREIGN KEY to tutors table
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->date('class_date')->nullable(); // Fecha de la clase
            $table->dateTime('start_time'); // Hora de inicio
            $table->dateTime('end_time'); // Hora de fin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
