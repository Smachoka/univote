<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->text('manifesto')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            // Add indexes
            $table->index(['position_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};