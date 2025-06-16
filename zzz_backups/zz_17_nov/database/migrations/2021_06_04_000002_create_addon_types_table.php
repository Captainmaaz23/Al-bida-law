<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonTypesTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {

        Schema::create('addon_types', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('item_id');

            $table->string('title');

            $table->string('title_ar')->nullable();

            $table->tinyInteger('is_mandatory')->default('0');

            $table->tinyInteger('is_multi_select')->default('0');

            $table->tinyInteger('max_selection')->default(1);

            $table->tinyInteger('status')->default('1');

            $table->string('icon')->default('addon_type.png');

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on('items')
                    ->onDelete('cascade');
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {

        Schema::dropIfExists('addon_types');
    }
}
