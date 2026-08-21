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
        Schema::create('cash_registers', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Business Date
            |--------------------------------------------------------------------------
            */

            $table->date('business_date')->unique();
			$table->string('receipt_no', 30)->nullable()->unique();

            /*
            |--------------------------------------------------------------------------
            | Manual Entries
            |--------------------------------------------------------------------------
            */

            // Cash withdrawn from the till (owner, bank deposit, etc.)
            $table->decimal('withdraw_amount', 12, 2)->default(0);

            // Physical cash counted at end of day
            $table->decimal('closing_cash', 12, 2)->nullable();
			$table->timestamp('reconciled_at')->nullable();

            $table->text('remarks')->nullable();
			$table->boolean('is_closed')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
