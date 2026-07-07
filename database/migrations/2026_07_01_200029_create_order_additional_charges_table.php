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
        Schema::create('order_additional_charges', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('charge_type_id')
                ->constrained('additional_charge_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('amount', 10, 2)->default(0);

            $table->text('remarks')->nullable();

            $table->boolean('is_deleted')->default(false);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Prevent duplicate charge types on the same order
            $table->unique(['order_id', 'charge_type_id']);

            // Common lookup index
            $table->index(['order_id', 'is_deleted']);
            $table->index('charge_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_additional_charges');
    }
};
