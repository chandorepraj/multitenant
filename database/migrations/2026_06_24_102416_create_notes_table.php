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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
            ->constrained('tenants')
            ->cascadeOnDelete();
            $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()
            ->constrained('leads')
            ->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()
            ->constrained('customers')
            ->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
