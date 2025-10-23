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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->uuid()->nullable()->unique();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('media_type')->index();
            $table->text('presets')->nullable();
            $table->string('disk');
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->longText('custom_properties')->nullable();
            $table->longText('conversions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['file_name', 'disk', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
