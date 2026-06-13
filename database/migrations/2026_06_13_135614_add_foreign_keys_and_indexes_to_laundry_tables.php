<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            if (!Schema::hasColumn('order_details', 'service_type_id')) {
                return;
            }

            $table->index('service_type_id');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->restrictOnDelete();

            $table->foreign('service_type_id')
                ->references('id')
                ->on('service_types')
                ->nullOnDelete();
        });

        Schema::table('order_addon_details', function (Blueprint $table) {

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('addon_id')
                ->references('id')
                ->on('addons')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_addon_details', function (Blueprint $table) {

            $table->dropForeign(['order_id']);
            $table->dropForeign(['addon_id']);
        });

        Schema::table('order_details', function (Blueprint $table) {

            $table->dropForeign(['order_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['service_type_id']);

            $table->dropIndex(['service_type_id']);
        });
    }
};
