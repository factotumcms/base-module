<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename the `avatar` column to `avatar_id` in the `users` table.
     *
     * Creates a new `avatar_id` column with the same properties as `avatar`, copies the data,
     * and then drops the old `avatar` column.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('avatar_id')->nullable()->after('id')->constrained('media')->nullOnDelete();
        });

        DB::table('users')->update(['avatar_id' => DB::raw('avatar')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('avatar');
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
            $table->foreignId('avatar')->nullable()->after('id')->constrained('media')->nullOnDelete();
        });

        DB::table('users')->update(['avatar' => DB::raw('avatar_id')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('avatar_id');
        });
    }
};
