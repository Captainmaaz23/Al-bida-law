<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('rest_id')->default('1');

            $table->string('company_name')->nullable();

            $table->string('name')->nullable();

            $table->string('phone')->unique();

            $table->string('email')->unique();

            $table->string('password')->nullable();

            $table->tinyInteger('status')->default('1');

            $table->tinyInteger('application_status')->default('0');

            $table->tinyInteger('approval_status')->default('0');

            $table->string('license_no')->nullable();

            $table->string('license_expiry')->nullable();

            $table->string('principal_place')->nullable();

            $table->string('address')->nullable();

            $table->double('lat')->nullable();

            $table->double('lng')->nullable();

            $table->string('website')->nullable();

            $table->text('activities')->nullable();

            $table->text('comments')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            $table->timestamp('phone_verified_at')->nullable();

            $table->string('device_name')->nullable();

            $table->string('device_id')->nullable();

            $table->string('theme')->default('flat')->nullable();

            $table->string('header')->default('light')->nullable();

            $table->string('sidebar')->default('dark')->nullable();

            $table->string('photo')->default('user.png');

            $table->rememberToken();

            $table->integer('created_by')->nullable();

            $table->integer('updated_by')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
