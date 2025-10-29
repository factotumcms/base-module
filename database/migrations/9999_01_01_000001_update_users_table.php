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
        // Update default users table
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');

            $table->after('first_name', function (Blueprint $table) {
                $table->string('last_name')->nullable();
                $table->string('username')->unique();
            });

            $table->boolean('is_active')->after('password')->default(true);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
            $table->dropUnique(['username']);

            $table->dropColumn(['last_name', 'username', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
