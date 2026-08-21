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
        Schema::create('business_day_closures', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Register Reference
            |--------------------------------------------------------------------------
            */

            $table->foreignId('cash_register_id')
                ->constrained('cash_registers')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Business Date
            |--------------------------------------------------------------------------
            |
            | Only one closure is allowed for a business date.
            |
            */

            $table->date('business_date')->unique();

            /*
            |--------------------------------------------------------------------------
            | Financial Snapshot
            |--------------------------------------------------------------------------
            */

            $table->decimal('opening_cash', 12, 2)->default(0);

            $table->decimal('cash_collection', 12, 2)->default(0);

            $table->decimal('upi_collection', 12, 2)->default(0);

            $table->decimal('card_collection', 12, 2)->default(0);

            $table->decimal('wallet_collection', 12, 2)->default(0);

            $table->decimal('other_collection', 12, 2)->default(0);

            $table->decimal('expense_amount', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Cash Reconciliation
            |--------------------------------------------------------------------------
            */

            // Actual cash removed from the till.
            $table->decimal('withdraw_amount', 12, 2)->default(0);

            // Cash that should remain in the till.
            $table->decimal('expected_cash', 12, 2)->default(0);

            // Physical cash counted at closing.
            $table->decimal('counted_cash', 12, 2)->default(0);

            // counted_cash - expected_cash
            $table->decimal('difference_amount', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Reconciliation Notes
            |--------------------------------------------------------------------------
            */

            $table->string('difference_reason')->nullable();

            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Closure Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('closed_by')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            | IMPORTANT:
            | closed_at records the actual moment of closure.
            | It must not automatically change when the record is updated.
            */

            $table->timestamp('closed_at');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | One-to-One Register Closure
            |--------------------------------------------------------------------------
            |
            | A cash register can only have one closure record.
            |
            */

            $table->unique('cash_register_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_day_closures');
    }
};
