<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_articles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('order_detail_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('tag_number')->unique();

            $table->string('article_name');

            $table->string('service_name')->nullable();

            $table->string('color_code')->nullable();

            $table->tinyInteger('status')
                ->default(0);

            $table->foreignId('created_by')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_articles');
    }
};
