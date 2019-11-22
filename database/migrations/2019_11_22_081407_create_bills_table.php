<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_id')->unique();
            $table->string('receptionist_id');
            $table->string('status');
            $table->string('table_number');
            $table->double('total_price')->default(0);
            $table->string('customer_name');
            $table->string('street')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
