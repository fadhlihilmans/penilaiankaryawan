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
        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator_user_id')->references('id')->on('users');
            $table->foreignId('evaluated_user_id')->references('id')->on('users');
            $table->foreignId('evaluation_criteria_detail_id')->references('id')->on('evaluation_criteria_details');
            $table->text('comment')->nullable();
            $table->date('date');
            $table->decimal('score', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_scores');
    }
};
