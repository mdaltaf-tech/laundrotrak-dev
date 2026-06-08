<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders','is_deleted')) {
                $table->boolean('is_deleted')
                    ->default(0)
                    ->after('status');
            }

        });

        Schema::table('order_details', function (Blueprint $table) {

            if (!Schema::hasColumn('order_details','is_deleted')) {
                $table->boolean('is_deleted')
                    ->default(0);
            }

        });

        Schema::table('payments', function (Blueprint $table) {

            if (!Schema::hasColumn('payments','is_deleted')) {
                $table->boolean('is_deleted')
                    ->default(0);
            }

        });

        Schema::table('order_addon_details', function (Blueprint $table) {

            if (!Schema::hasColumn('order_addon_details','is_deleted')) {
                $table->boolean('is_deleted')
                    ->default(0);
            }

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumn('orders','is_deleted')){
                $table->dropColumn('is_deleted');
            }
        });

        Schema::table('order_details', function (Blueprint $table) {
            if(Schema::hasColumn('order_details','is_deleted')){
                $table->dropColumn('is_deleted');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if(Schema::hasColumn('payments','is_deleted')){
                $table->dropColumn('is_deleted');
            }
        });

        Schema::table('order_addon_details', function (Blueprint $table) {
            if(Schema::hasColumn('order_addon_details','is_deleted')){
                $table->dropColumn('is_deleted');
            }
        });
    }
};
