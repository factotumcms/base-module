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
                $table->timestamp('sent_at')->nullable();
                $table->text('response')->nullable();
            });

            $table->dropPrimary();
            $table->dropColumn('id');
            $table->uuid('id')->primary()->default(DB::raw('UUID()'))->first();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['channel', 'route', 'sent_at', 'response']);
            $table->dropSoftDeletes();

            $table->dropPrimary();
            $table->dropColumn('id');
            $table->uuid('id')->primary()->first();
        });
    }
};
