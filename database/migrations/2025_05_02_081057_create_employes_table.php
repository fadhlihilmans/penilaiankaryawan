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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('position_id')->references('id')->on('positions');
            $table->foreignId('education_id')->references('id')->on('educations');
            $table->string('nip');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['male','female']);
            $table->enum('employment_status', ['permanent','non-permanent'])->default('permanent');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
