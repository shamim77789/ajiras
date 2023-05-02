<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->integer('assigned_to_id')->unsigned()->nullable();
            $table->integer('follow_up_category_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('name')->nullable();
            $table->text('notes')->nullable();
            $table->date('due_date')->nullable();
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
        Schema::drop('follow_ups');
    }
}
