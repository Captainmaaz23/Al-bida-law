<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSubDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_sub_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('addon_id');
            $table->double('total_value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('detail_id')->references('id')->on('order_details')
                    ->onDelete('cascade');

            $table->foreign('addon_id')->references('id')->on('item_options')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_sub_details');
    }
}
