<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_platform', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('platform_id')->constrained('platforms')->cascadeOnDelete();
            $table->enum('platform_status', ['pending', 'published', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'platform_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_platform');
    }
};
