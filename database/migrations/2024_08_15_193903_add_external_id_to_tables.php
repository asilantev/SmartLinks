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
        Schema::table('smart_links', function (Blueprint $table) {
            $table->integer('external_id')->nullable();
        });
        Schema::table('redirect_rules', function (Blueprint $table) {
            $table->integer('external_id')->nullable();
        });
        Schema::table('rule_conditions', function (Blueprint $table) {
            $table->integer('external_id')->nullable();
        });
        Schema::table('condition_types', function (Blueprint $table) {
            $table->integer('external_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smart_links', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
        Schema::table('redirect_rules', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
        Schema::table('rule_conditions', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
        Schema::table('condition_types', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
