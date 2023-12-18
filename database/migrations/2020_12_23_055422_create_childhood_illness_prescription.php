<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildhoodIllnessPrescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childhood_illness_prescription', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('childhood_illness_id');
            $table->integer('prescription_id');
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
        Schema::dropIfExists('childhood_illness_prescription');
    }
}
