<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('transaction_name')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('orderid')->nullable();
            $table->string('paypal_transactionid')->nullable();
            $table->string('status');
            $table->decimal('amount',30);
            $table->string('currency');
            $table->string('paypal_description')->nullable();
            $table->timestamp('created_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
