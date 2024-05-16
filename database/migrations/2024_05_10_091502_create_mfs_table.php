<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->string('description');
        $table->string('model');
        $table->date('produced_on');
        $table->foreignId('mf_id')->constrained()->onDelete('cascade'); // Đảm bảo mf_id không nullable
        $table->string('image');
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
        Schema::dropIfExists('mfs');
    }
}