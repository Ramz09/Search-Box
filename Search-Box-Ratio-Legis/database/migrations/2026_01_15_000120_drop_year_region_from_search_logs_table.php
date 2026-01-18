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
        Schema::table('search_logs', function (Blueprint $table) {
            if (Schema::hasColumn('search_logs', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('search_logs', 'region')) {
                $table->dropColumn('region');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('search_logs', 'year')) {
                $table->string('year')->nullable();
            }
            if (!Schema::hasColumn('search_logs', 'region')) {
                $table->string('region')->nullable();
            }
        });
    }
};
