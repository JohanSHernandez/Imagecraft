<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->foreignId('album_id')->constrained()->onDelete('cascade');
            $table->boolean('is_selected')->default(false);
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('edit_type')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('photos');
    }
};