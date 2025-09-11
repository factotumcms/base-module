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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('data_type');
            $table->string('group');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['key', 'type', 'group']);
        });

        Schema::create('setting_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('setting_id')->constrained()->cascadeOnDelete();

            $table->text('value');
            $table->unique(['user_id', 'setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
