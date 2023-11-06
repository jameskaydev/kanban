<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanban_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('board_id');
            $table->string('list_id');
            $table->string('title');
            $table->string('description');
            $table->string('due_date');
            $table->string('label');
            $table->string('status')->default('active');
            $table->integer('card_order');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kanban_cards');
    }
};
