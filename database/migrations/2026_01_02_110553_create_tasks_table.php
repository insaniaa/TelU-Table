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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id_task')->primary();
            $table->foreignUuid('course_code')->references('course_code')->on('courses');
            $table->foreignUuid('id_student_class')->references('id_student_class')->on('student_classes');
            $table->string('task_name');
            $table->string('task_module')->nullable();
            $table->string('task_file')->nullable();
            $table->timestamp('task_deadline');
            $table->timestamp('task_status')->default(1);
            $table->timestamp('task_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
