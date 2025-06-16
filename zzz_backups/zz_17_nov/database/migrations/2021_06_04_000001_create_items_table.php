<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {

        Schema::create('items', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('menu_id');

            $table->string('name');

            $table->string('ar_name')->nullable();

            $table->text('description');

            $table->text('ar_description')->nullable();

            $table->double('price');

            $table->double('discount_type')->default(0); //0 for %, 1 for fixed value      

            $table->double('discount')->default(0);

            $table->double('total_value');

            $table->string('image')->default('item.png');

            $table->tinyInteger('has_options')->default(0);

            $table->tinyInteger('variations')->default(0);

            $table->tinyInteger('is_order')->default(1);

            $table->tinyInteger('selling_status')->default(0);

            $table->tinyInteger('availability')->default(1);

            $table->integer('re_available_time')->default(0);

            $table->tinyInteger('status')->default(1);

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->foreign('menu_id')->references('id')->on('menus')
                    ->onDelete('cascade');
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {

        Schema::drop('items');
    }
}
