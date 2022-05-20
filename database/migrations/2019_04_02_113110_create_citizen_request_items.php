<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitizenRequestItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_request_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id');
            $table->bigInteger('count');
            $table->bigInteger('citizenRequest_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizen_request_items');
    }
}
