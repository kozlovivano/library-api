<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('collection_id');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('editor')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_date')->nullable();
            $table->longText('description')->nullable();
            $table->string('reference')->nullable();
            $table->integer('pages')->nullable();
            $table->string('cover')->nullable();
            $table->string('isbn');
            $table->string('volumn')->nullable();
            $table->string('category')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            // Relations
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
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
