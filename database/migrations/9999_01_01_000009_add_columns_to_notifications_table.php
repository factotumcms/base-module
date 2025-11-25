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
        Schema::table('notifications', function (Blueprint $table) {
            $table->after('data', function ($table) {
                $table->string('channel');
                $table->string('route');
                $table->char('lang', 2);
                $table->timestamp('sent_at')->nullable();
                $table->text('response')->nullable();
            });

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['channel', 'route', 'lang', 'sent_at', 'response']);
            $table->dropSoftDeletes();
        });
    }
};
