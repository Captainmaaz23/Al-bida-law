<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOptionsTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {

        Schema::create('item_options', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('item_id');

            $table->unsignedBigInteger('type_id');

            $table->string('name');

            $table->string('ar_name')->nullable();

            $table->string('description')->nullable();

            $table->string('ar_description')->nullable();

            $table->decimal('price');

            $table->string('picture')->default('option.png');

            $table->tinyInteger('status')->default(1);

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on('items')
                    ->onDelete('cascade');

            $table->foreign('type_id')->references('id')->on('addon_types')
                    ->onDelete('cascade');
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {

        Schema::dropIfExists('item_options');
    }
}
