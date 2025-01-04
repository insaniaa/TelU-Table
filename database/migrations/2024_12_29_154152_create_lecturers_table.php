<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->string('nip')->primary();
            $table->foreignUuid('id_user')->nullable()->references('id_user')->on('users')->index();
            $table->string('lecturer_name');
            $table->string('lecturer_email')->nullable();
            $table->char('lecturer_code', 20)->index();
            $table->boolean('lecturer_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
