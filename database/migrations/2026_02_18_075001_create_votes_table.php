<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('candidate_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('position_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Ensure one vote per user per position
            $table->unique(['user_id', 'position_id'], 'user_position_unique');
            
            // Add indexes for faster queries
            $table->index(['candidate_id']);
            $table->index(['position_id']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};