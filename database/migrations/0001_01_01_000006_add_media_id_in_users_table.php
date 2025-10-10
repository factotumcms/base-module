<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a nullable `media_id` column to the `users` table and create a foreign key to `media.id`
     * that sets `media_id` to NULL when the referenced media row is deleted.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('id');
            $table->foreign('media_id')->references('id')->on('media')->nullOnDelete();
        });
    }

    /**
     * Remove the media_id foreign key and column from the users table.
     *
     * Drops the foreign key constraint on `media_id` and then drops the `media_id` column from the `users` table.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn('media_id');
        });
    }
};
