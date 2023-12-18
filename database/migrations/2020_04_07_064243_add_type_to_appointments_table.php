<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum(
                'type',
                ['offline', 'online']
            )->nullable();
            $table->enum(
                'paid',
                ['pending', 'completed']
            )->nullable();
            $table->enum(
                'payout_progress',
                ['processing', 'processed']
            )->nullable();
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
            Schema::dropIfExists('type');
            Schema::dropIfExists('paid');
        });
    }
}
