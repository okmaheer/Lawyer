<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'orders',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id');
                $table->string('payment_gateway');
                $table->string('appointment_date')->nullable();
                $table->enum(
                    'status', ['pending', 'completed']
                );
                $table->timestamps();
                $table->engine = 'InnoDB';
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
        Schema::dropIfExists('orders');
    }
}
