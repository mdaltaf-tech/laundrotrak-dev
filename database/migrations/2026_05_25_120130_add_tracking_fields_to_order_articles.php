<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'order_articles',
            function (Blueprint $table) {

                $table->timestamp(
                    'processing_at'
                )->nullable();

                $table->timestamp(
                    'ready_at'
                )->nullable();

                $table->timestamp(
                    'delivered_at'
                )->nullable();

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'order_articles',
            function (Blueprint $table) {

                $table->dropColumn([
                    'processing_at',
                    'ready_at',
                    'delivered_at'
                ]);

            }
        );
    }
};
