<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('event');
            $table->text('target');
            $table->text('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->text('email_activated')->nullable();
            $table->text('sms_content')->nullable();
            $table->text('sms_activated')->nullable();
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
        Schema::dropIfExists('notice_managers');
    }
}
