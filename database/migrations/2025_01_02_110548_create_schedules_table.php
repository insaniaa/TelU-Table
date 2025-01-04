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
            $table->uuid('id_schedule')->primary();
            $table->foreignUuid('id_student_class')->references('id_student_class')->on('student_classes');
            $table->foreignUuid('course_code')->references('course_code')->on('courses');
            $table->foreignUuid('nip')->references('nip')->on('lecturers');
            $table->string('schedule_day');
            $table->time('schedule_start_time');
            $table->time('schedule_end_time');
            $table->boolean('schedule_status')->default(1);
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
