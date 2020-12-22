<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddorderstatusCancelreasonOtherToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_status_id')->nullable();
            $table->foreign('order_status_id')->references('id')->on('order_status')->onDelete('set null')
            ->onUpdate('cascade');
            $table->unsignedBigInteger('cancel_reason_id')->nullable();
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_order_reasons')->onDelete('set null')
            ->onUpdate('cascade');
            $table->text('other_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
