<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->string('school_year', 9);
            $table->string('semester', 10);
            $table->string('status', 15)->default('enrolled');
            $table->decimal('grade', 3, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'school_year', 'semester'], 'enrollment_unique_term');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
