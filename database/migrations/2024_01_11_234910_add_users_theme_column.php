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
        // The theme selection is moved from a combination of localStorage and SESSION data into the backend
        // 1. Because keeping localStorage in sync with the session is duplication and
        // 2. Because the theme should be consistent across browser sessions (makes localStorage redundant).
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme')
                ->nullable()
                ->comment('User theme preference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme']);
        });
    }
};
