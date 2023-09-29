<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFurnitureItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furniture_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('url')->unique();
            $table->string('furniture_type');
            $table->foreignId('furniture_store_id')->constrained()->cascadeOnDelete();
            $table->decimal('height')->nullable();
            $table->decimal('width')->nullable();
            $table->decimal('depth')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furniture_items');
    }
}
