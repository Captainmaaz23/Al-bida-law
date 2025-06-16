<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rest_id');
            $table->string('title');
            $table->string('ar_title')->nullable();
            $table->string('icon')->default('menu.png');
            $table->tinyInteger('is_order')->default(1);
            $table->tinyInteger('availability')->default(1);
            $table->integer('re_available_time')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('rest_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {
        Schema::dropIfExists('menus');
    }
}
