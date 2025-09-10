<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wave8\Factotum\Base\Types\BaseSettingGroup;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->enum('type', SettingType::getValues()->toArray());
            $table->enum('data_type', SettingDataType::getValues()->toArray());
            $table->enum('group', BaseSettingGroup::getValues()->toArray());
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['key', 'type', 'group']);
        });

        Schema::create('setting_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('setting_id')->index();
            $table->text('value');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');

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
