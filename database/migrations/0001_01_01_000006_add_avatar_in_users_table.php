<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a nullable `avatar` column to the `users` table and create a foreign key to `media.id`
     * that sets `avatar` to NULL when the referenced media row is deleted.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('avatar')->nullable()->after('id');
            $table->foreign('avatar')->references('id')->on('media')->nullOnDelete();
        });
    }

    /**
     * Remove the avatar foreign key and column from the users table.
     *
     * Drops the foreign key constraint on `avatar` and then drops the `avatar` column from the `users` table.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['avatar']);
            $table->dropColumn('avatar');
        });
    }
};
