<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRelationToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            DB::statement("ALTER TABLE appointments CHANGE COLUMN relation relation ENUM('father', 'mother', 'sister', 'brother', 'friend', 'other');");
            $table->string('other_relation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            Schema::dropIfExists('relation');
            Schema::dropIfExists('other_relation');
        });
    }
}
