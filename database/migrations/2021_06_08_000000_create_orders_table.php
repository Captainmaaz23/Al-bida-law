<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rest_id');
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('user_id');
            $table->string('order_no')->default(0);
            $table->string('pin_no')->default(0);
            $table->double('order_value');
            $table->unsignedBigInteger('promo_id')->nullable();
            $table->double('promo_value')->default(0);
            $table->double('total_value');
            $table->tinyInteger('vat_included')->default(0);
            $table->double('vat_value')->default(0);
            $table->double('service_charges')->default(0);
            $table->double('final_value');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->dateTime('cancelled_time')->nullable()->default(null);
            $table->dateTime('confirmed_time')->nullable()->default(null);
            $table->dateTime('declined_time')->nullable()->default(null);
            $table->dateTime('accepted_time')->nullable()->default(null);
            $table->dateTime('scheduled_time')->nullable()->default(null);
            $table->dateTime('preparation_time')->nullable()->default(null);
            $table->dateTime('ready_time')->nullable()->default(null);
            $table->dateTime('picked_time')->nullable()->default(null);
            $table->dateTime('collected_time')->nullable()->default(null);
            $table->dateTime('pickup_time')->nullable()->default(null);
            $table->tinyInteger('pickup_option')->nullable()->default(null);
            $table->unsignedBigInteger('pickup_option_id')->nullable()->default(null);
            $table->string('pay_method')->nullable()->default('cash'); // cash, wallet, user_options
            $table->tinyInteger('pay_method_id')->nullable()->default(null);
            $table->tinyInteger('pay_status')->default(0);
            $table->string('transaction_id')->nullable();
            $table->tinyInteger('is_rated')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('cancel_reason')->nullable();
            $table->string('decline_reason')->nullable();
            $table->tinyInteger('user_arrived')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rest_id')->references('id')->on('restaurants')
                    ->onDelete('cascade');

            $table->foreign('table_id')->references('id')->on('serve_tables')
                    ->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('app_users')
                    ->onDelete('cascade');
        });
        Schema::table('orders', function (Blueprint $table) {
            // Adding composite index for 'status' and 'created_at'
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('orders');
    }
}
