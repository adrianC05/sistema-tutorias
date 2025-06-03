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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            // FOREIGN KEY to students table
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            // FOREIGN KEY to schedules table
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('subject', 100);
            $table->text('reason');
            $table->string('status', 20);
            $table->timestamp('application_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
