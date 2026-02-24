<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::dropIfExists('job_listing_location');
        Schema::dropIfExists('job_listings');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }

    public function down() : void
    {
        if (! Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('logo')->nullable();
                $table->text('about')->nullable();
                $table->string('domain')->nullable()->unique();
                $table->string('url')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('city')->nullable();
                $table->string('region')->nullable();
                $table->string('country')->nullable();
                $table->unique(['city', 'region', 'country']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_listings')) {
            Schema::create('job_listings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->index();
                $table->string('url');
                $table->longText('html');
                $table->string('source');
                $table->string('language');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description');
                $table->json('technologies')->nullable();
                $table->string('setting');
                $table->string('employment_status')->nullable();
                $table->string('seniority')->nullable();
                $table->unsignedBigInteger('min_salary')->nullable();
                $table->unsignedBigInteger('max_salary')->nullable();
                $table->string('currency')->nullable();
                $table->boolean('equity')->default(false);
                $table->json('perks')->nullable();
                $table->json('interview_process')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_listing_location')) {
            Schema::create('job_listing_location', function (Blueprint $table) {
                $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
                $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
                $table->unique(['job_listing_id', 'location_id']);
            });
        }

        if (! Schema::hasTable('subscribers')) {
            Schema::create('subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('confirmation_token', 64)->nullable()->index();
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamp('confirmation_sent_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->json('slug');
                $table->string('type')->nullable();
                $table->integer('order_column')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('taggables')) {
            Schema::create('taggables', function (Blueprint $table) {
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->morphs('taggable');
                $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
            });
        }
    }
};
