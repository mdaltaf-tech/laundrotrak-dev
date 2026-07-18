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
            | References
            |--------------------------------------------------------------------------
            */

            $table->foreignId('cash_register_id')
                ->constrained()
                ->cascadeOnDelete();

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

            $table->decimal('withdraw_amount', 12, 2)->default(0);

            $table->decimal('expected_cash', 12, 2)->default(0);

            $table->decimal('counted_cash', 12, 2)->default(0);

            $table->decimal('difference_amount', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Reconciliation
            |--------------------------------------------------------------------------
            */

            $table->string('difference_reason')->nullable();

            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('closed_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('closed_at');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique('cash_register_id');

            $table->index('business_date');

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
