<?php

use App\Models\StudentClass;
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
        Schema::create('student_classes', function (Blueprint $table) {
            $table->uuid('id_student_class')->primary();
            $table->string('student_class_name');
            $table->boolean('student_class_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classes');
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'id_student_class');
    }
};
