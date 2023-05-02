<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->tinyInteger('member_type')->default(1);
            //0 is anonymous
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->integer('family_id')->unsigned()->nullable();
            $table->integer('fund_id')->unsigned()->nullable();
            $table->integer('contribution_batch_id')->unsigned()->nullable();
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->text('files')->nullable();
            $table->text('trans_ref')->nullable();
            $table->decimal('amount',10,2);
            $table->decimal('actual_amount',10,2)->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
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
        Schema::drop('contributions');
    }

}
