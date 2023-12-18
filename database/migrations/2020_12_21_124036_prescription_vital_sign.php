<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrescriptionVitalSign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'prescription_vital_sign',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('prescription_id');
                $table->integer('vital_sign_id');
                $table->string('value')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescription_vital_sign');
    }
}
