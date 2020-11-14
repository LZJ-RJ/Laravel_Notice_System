<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_boxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('owner')->comment('擁有者');
            $table->text('box_source_event')->comment('訊息匣來源事件');
            $table->text('box_type')->comment('訊息匣類型');
            $table->text('box_content')->comment('訊息匣內容')->nullable();
            $table->text('read_at')->comment('已讀的時間點/人')->nullable();
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
        Schema::dropIfExists('notice_boxes');
    }
}
