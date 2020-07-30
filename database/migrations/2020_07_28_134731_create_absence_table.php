<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsenceTable extends Migration
{
    public function up()
    {
        Schema::create('absence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('absence_id')->nullable();
            $table->date('absence_begin')->nullable();
            $table->date('absence_end')->nullable();
            $table->string('absence_type')->nullable();
            $table->integer('substitute_01_id')->nullable();
            $table->integer('substitute_02_id')->nullable();
            $table->integer('substitute_03_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absence');
    }
}

