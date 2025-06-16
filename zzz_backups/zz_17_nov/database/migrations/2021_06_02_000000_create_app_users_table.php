<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration {

    /**

     * Run the migrations.

     *

     * @return void

     */
    public function up() {

        Schema::create('app_users', function (Blueprint $table) {

            $table->id();

            $table->tinyInteger('user_type')->default('1');

            $table->unsignedBigInteger('table_id')->default(0);

            $table->string('phone')->unique();

            $table->string('name')->nullable();

            $table->string('email')->nullable();

            $table->string('username')->nullable();

            $table->string('password')->nullable();

            $table->string('thw_client_id')->nullable();

            $table->double('balance')->default(0);

            $table->double('lat')->nullable();

            $table->double('lng')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            $table->timestamp('phone_verified_at')->nullable();

            $table->string('device_name')->nullable();

            $table->string('device_id')->nullable();

            $table->tinyInteger('photo_type')->default('0');

            $table->string('photo')->default('app_user.png');

            $table->tinyInteger('status')->default('1');

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('verification_codes', function (Blueprint $table) {

            $table->id();

            $table->integer('user_id');

            $table->string('type')->default('phone');

            $table->string('sent_to');

            $table->string('code');

            $table->tinyInteger('verified')->default('1');

            $table->timestamp('verified_at')->nullable();

            $table->string('token')->nullable();

            $table->tinyInteger('expired')->default('1');

            $table->timestamp('generated_at')->nullable();

            $table->timestamp('expired_at')->nullable();

            $table->string('device_id')->nullable();

            $table->timestamps();
        });
    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */
    public function down() {

        Schema::dropIfExists('verification_codes');

        Schema::dropIfExists('app_users');
    }
}
