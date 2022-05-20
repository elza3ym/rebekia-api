<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitizenRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('citizen_id');
            $table->bigInteger('collector_id');
            $table->text('address')->nullable();
            $table->string('town')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->boolean('status');       
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
        Schema::dropIfExists('citizen_requests');
    }
}
