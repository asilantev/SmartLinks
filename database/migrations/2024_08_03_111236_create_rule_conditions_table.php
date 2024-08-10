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
        Schema::create('rule_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('redirect_rules')->onDelete('cascade');
            //$table->enum('condition_type_id', array_map(fn(UnitEnum $case) => $case->value, \App\Impl\ConditionType::cases()));
            $table->foreignId('condition_type_id')->constrained('condition_types');
            $table->json('condition_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_conditions');
    }
};
