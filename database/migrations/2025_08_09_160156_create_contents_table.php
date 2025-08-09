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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->enum('type', ['video', 'article']);
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('duration')->default(0);
            $table->float('score')->default(0);
            $table->timestamp('published_at');
            $table->json('tags')->nullable();
            $table->integer('reading_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
