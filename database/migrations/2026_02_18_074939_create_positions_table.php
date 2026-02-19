<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_votes')->default(1);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            // Add index for faster queries
            $table->index(['election_id', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};