<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->char('source_uuid', 26)->unique();
            $table->string('source_path');
            $table->string('source_hash', 64);
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->text('content')->nullable();
            $table->string('website_url');
            $table->string('outbound_url');
            $table->string('pricing_model');
            $table->boolean('has_free_plan')->default(false);
            $table->boolean('has_free_trial')->default(false);
            $table->boolean('is_open_source')->default(false);
            $table->json('categories');
            $table->string('image_path')->nullable();
            $table->foreignId('review_post_id')->nullable()->unique()->constrained('posts')->nullOnDelete();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('tools');
    }
};
