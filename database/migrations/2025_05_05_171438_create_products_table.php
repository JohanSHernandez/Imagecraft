<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->decimal('base_cost', 12, 2);
            $table->decimal('sale_price', 12, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->boolean('is_percentage')->default(false);
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};