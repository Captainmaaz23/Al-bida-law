<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {



        Schema::create('restaurants', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();

            $table->string('arabic_name')->nullable();

            $table->string('location', 250)->nullable();

            $table->string('phoneno')->unique();

            $table->string('email')->unique();

            $table->string('website_link', 250)->nullable();

            $table->string('qrcode', 250)->nullable();

            $table->double('lat')->nullable();

            $table->double('lng')->nullable();

            $table->integer('avg_time')->default('20');

            $table->double('rating')->default('0');

            $table->integer('reviews')->default('0');

            $table->integer('likes')->default('0');

            $table->text('description')->nullable();

            $table->text('arabic_description')->nullable();

            $table->string('profile')->default('restaurant.png');

            $table->tinyInteger('is_open')->default('1');

            $table->integer('re_open_time')->default(0);

            $table->tinyInteger('is_featured')->default('0');

            $table->tinyInteger('status')->default('1');

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {

        Schema::dropIfExists('restaurants');
    }
}
