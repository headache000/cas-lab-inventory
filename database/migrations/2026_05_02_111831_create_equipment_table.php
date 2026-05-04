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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_id')->constrained('laboratories')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('fund')->nullable();
            $table->string('par_number')->unique();
            $table->string('property_number')->unique()->nullable();
            $table->enum('status', ['working', 'damaged', 'under_repair', 'disposed'])->default('working');
            $table->date('acquired_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
