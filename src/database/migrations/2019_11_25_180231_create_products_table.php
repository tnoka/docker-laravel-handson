<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('本のタイトル');
            $table->string('author')->comment('本の著者');
            $table->unsignedInteger('category_id')->comment('本のカテゴリー');
            $table->integer('recommend')->comment('おすすめ値');
            $table->string('text')->comment('メモ');
            $table->string('product_image')->nullable()->comment('本の画像');
            $table->unsignedInteger('user_id')->comment('ユーザID');
            $table->softDeletes();
            $table->timestamps();

            $table->index('id');
            $table->index('category_id');
            $table->index('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
