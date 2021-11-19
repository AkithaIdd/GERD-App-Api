<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('patients');
       Schema::create('patients',function(Blueprint $table){

        $table->id()->startingValue('1200');
        $table->integer('doctor_id');
        $table->string('name');
        $table->date('date_of_birth');
        $table->integer('age');
        $table->integer('age_of_onset');
        $table->integer('phoneNumber')->unique();
    
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
