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
        Schema::create('redirects_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smart_link_id')->constrained();
            $table->foreignId('rule_id')->nullable()->constrained('redirect_rules');
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer', 2048)->nullable();
            $table->string('redirect_url', 2048);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirects_log');
    }
};
