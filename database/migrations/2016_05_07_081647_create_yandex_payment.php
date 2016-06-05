<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYandexPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yandex_payments', function (Blueprint $table) {

            $table->increments('id');

            $table->string('hash', 60)->nullable();
            
            $table->float('orderSumAmount');
            $table->float('shopSumAmount');
            
            $table->tinyInteger('type');
            
            $table->integer('invoiceId');
            $table->integer('yandexPaymentId');

            $table->integer('user_id')->unsigned(); //customerNumber
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('transaction_id')->unsigned();
            $table->foreign('transaction_id')->references('id')->on('transaction');
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
        Schema::table('yandex_payments', function (Blueprint $table) {
            $table->dropForeign('yandex_payments_user_id_foreign');
            $table->dropForeign('yandex_payments_transaction_id_foreign');
            $table->drop();
        });
    }
}
