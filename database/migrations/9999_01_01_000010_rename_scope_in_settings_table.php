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
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key', 'scope', 'group']);
            $table->renameColumn('scope', 'visibility');
            $table->unique(['visibility', 'group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['visibility', 'group', 'key']);
            $table->renameColumn('visibility', 'scope');
            $table->unique(['key', 'scope', 'group']);
        });
    }
};
