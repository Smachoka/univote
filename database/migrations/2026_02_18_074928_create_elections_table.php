<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->enum('status', ['upcoming', 'active', 'completed', 'closed'])->default('upcoming');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add indexes for faster queries
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};