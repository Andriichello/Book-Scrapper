<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->primary();
            $table->foreignId('publisher_id')->references('id')->on('publishers');

            $table->string('slug', 255)->nullable();
            $table->string('title', 255);
            $table->string('original_title', 255)->nullable();
            $table->text('description')->nullable();
            $table->boolean('ebook')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('club_price')->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('language', 50)->nullable();
            $table->string('original_language', 50)->nullable();
            $table->string('cover', 50)->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->string('format', 50)->nullable();
            $table->string('year', 4)->nullable();
            $table->string('age_restriction', 10)->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedSmallInteger('reviews')->default(0);
            $table->string('isbn', 255);
            $table->json('details')->nullable();

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
        Schema::dropIfExists('books');
    }
}
