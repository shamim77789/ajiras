<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePledgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pledges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->integer('family_id')->unsigned()->nullable();
            $table->tinyInteger('pledge_type')->default(1);
            // 0 is family, 1 is member
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->tinyInteger('recurring')->default(0);
            $table->string('recur_frequency')->default(31);
            $table->enum('recur_type',
                array('day', 'week', 'month', 'year'))->default('month');
            $table->date('recur_start_date')->nullable();
            $table->date('recur_end_date')->nullable();
            $table->date('recur_next_date')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->integer('times_number')->default(1);
            $table->string('year')->nullable();
            $table->string('month')->nullable();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
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
        Schema::drop('pledges', function (Blueprint $table) {
            //
        });
    }
}
