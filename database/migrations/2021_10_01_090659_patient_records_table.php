<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_records',function(Blueprint $table){

            $table->id()->startingValue('1200');
            $table->integer('patientId');
            $table->date('date_of_test');
            $table->integer('age_of_onset');
            $table->float('length_of_les');
        
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients_records');
    }
}
