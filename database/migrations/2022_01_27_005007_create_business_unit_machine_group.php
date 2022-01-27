<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessUnitMachineGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_unit_machine_group', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('business_unit_id');
            $table->unsignedBigInteger('machine_group_id');

            $table->foreign('business_unit_id')
                ->references('id')
                ->on('business_units')
                ->onDelete('cascade');

            $table->foreign('machine_group_id')
                ->references('id')
                ->on('machine_groups')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_unit_machine_group');
    }
}
