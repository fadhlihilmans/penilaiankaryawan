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
        Schema::create('evaluation_criteria_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_criteria_id')->references('id')->on('evaluation_criterias');
            $table->string('name');
            $table->text('description');
            $table->integer('weight');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_criteria_details');
    }
};
