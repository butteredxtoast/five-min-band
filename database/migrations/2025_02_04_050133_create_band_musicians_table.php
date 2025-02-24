<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            // Basic indexes for query performance
            $table->index(['musician_id', 'instrument']);
            $table->index(['band_id', 'instrument']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('band_musicians');
    }
};
