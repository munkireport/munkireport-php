<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersContactMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_contact_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('channel')->nullable(false)
                ->comment('The notification channel to deliver via');
            $table->string('address')->nullable(false)
                ->comment('The address, url, or phone number to deliver to');
            $table->boolean('enabled')->default(true)
                ->comment('Whether this method is enabled or not.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_contact_methods');
    }
}
