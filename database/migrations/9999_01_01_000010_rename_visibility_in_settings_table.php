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
            $table->dropUnique(['visibility', 'group', 'key']);
            $table->dropColumn('visibility');
            $table->boolean('is_editable')->default(false)->after('id');
            $table->unique(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['group', 'key']);
            $table->dropColumn('is_editable');
            $table->string('visibility')->after('id');
            $table->unique(['visibility', 'group', 'key']);
        });
    }
};
