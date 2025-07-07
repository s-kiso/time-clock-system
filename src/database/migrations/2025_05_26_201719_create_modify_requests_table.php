<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModifyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modify_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id');
            $table->foreignId('user_id');
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->time('clock_in');
            $table->time('clock_out');
            $table->string('notes');
            $table->string('status');
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
        Schema::dropIfExists('modify_requests');
    }
}
