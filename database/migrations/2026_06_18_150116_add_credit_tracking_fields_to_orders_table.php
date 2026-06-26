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
        Schema::table('orders', function (Blueprint $table) {

            $table->boolean('was_delivered_on_credit')
                ->default(false)
                ->after('payment_status');

            $table->timestamp('credit_delivered_at')
                ->nullable()
                ->after('was_delivered_on_credit');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'was_delivered_on_credit',
                'credit_delivered_at'
            ]);

        });
    }
};
