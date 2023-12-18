<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrescriptionLaboratoryTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'prescription_laboratory_test',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('prescription_id');
                $table->integer('laboratory_test_id');
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
        Schema::dropIfExists('prescription_laboratory_test');
    }
}
