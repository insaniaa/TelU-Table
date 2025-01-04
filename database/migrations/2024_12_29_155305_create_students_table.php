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
        Schema::create('students', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->foreignUuid('id_user')->nullable()->index();
            $table->foreignUuid('id_student_class')->references('id_student_class')->on('student_classes');
            $table->char('guardian_lecturer', 20)->references('lecturer_code')->on('lecturers');
            $table->string('student_name');
            $table->string('student_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
