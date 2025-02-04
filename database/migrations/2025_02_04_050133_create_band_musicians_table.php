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
        Schema::create('band_musicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('band_id')->constrained()->onDelete('cascade');
            $table->foreignId('musician_id')->constrained()->onDelete('cascade');
            $table->string('instrument')->nullable();
            $table->boolean('vocalist')->default(false);
            $table->json('match_metadata')->nullable();
            $table->decimal('match_score', 8, 2)->nullable();
            $table->timestamps();
            
            // A musician can only have one instrument assignment per band
            $table->unique(['band_id', 'musician_id', 'instrument'], 'unique_band_instrument_assignment');
            
            // A band can only have one vocalist
            $table->unique(['band_id', 'vocalist'], 'unique_band_vocalist');
            
            // Indexes for common queries
            $table->index(['musician_id', 'instrument']);
            $table->index(['band_id', 'instrument']);
            $table->index('vocalist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('band_musicians');
    }
};
