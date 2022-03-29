<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->date('insert_date')->comment('日付');
            $table->time('start')->comment('開始時間');
            $table->time('end')->nullable()->comment('終了時間');
            $table->integer('user_id')->comment('利用者');
            $table->boolean('food_fg')->default(0)->comment('食事提供加算フラグ');
            //$table->boolean('ourside_fg')->default(0)->comment('施設外支援フラグ');
            $table->boolean('outside_fg')->default(0)->comment('施設外支援フラグ');
            $table->boolean('medical_fg')->default(0)->comment('医療連携体制加算フラグ');
            $table->integer('note_id')->nullable()->comment('備考ID');
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
        Schema::dropIfExists('performances');
    }
}
