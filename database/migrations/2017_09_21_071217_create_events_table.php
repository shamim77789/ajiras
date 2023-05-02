<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('event_location_id')->unsigned()->nullable();
            $table->integer('event_calendar_id')->unsigned()->nullable();
            $table->text('name')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->tinyInteger('all_day');
            $table->date('start_date');
            $table->string('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->string('end_time')->nullable();
            $table->tinyInteger('recurring')->default(0);
            $table->string('recur_frequency')->default(30);
            $table->date('recur_start_date')->nullable();
            $table->date('recur_end_date')->nullable();
            $table->date('recur_next_date')->nullable();
            $table->enum('recur_type',
                array('day', 'week', 'month', 'year'))->default('month');
            $table->enum('checkin_type',
                array('everyone', 'specific_tags', 'no_one', 'form_respondents'))->default('everyone');
            $table->text('tags')->nullable();
            $table->tinyInteger('include_checkout')->default(0);
            $table->tinyInteger('family_checkin')->default(0);
            $table->text('featured_image')->nullable();
            $table->text('gallery')->nullable();
            $table->text('files')->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
            $table->text('notes')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
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
        Schema::drop('events', function (Blueprint $table) {
            //
        });
    }
}
