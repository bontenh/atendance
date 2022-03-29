<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsertablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usertables', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('last_name')->comment('性');
            $table->string('first_name')->comment('名');
            $table->string('last_name_kana')->comment('セイ');
            $table->string('first_name_kana')->comment('メイ');
            $table->integer('school_id')->comment('所属ID');
            $table->softDeletes()->comment('削除フラグ');
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
        Schema::dropIfExists('usertables');
    }
}
