<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
        public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('vacation_id')->nullable();
            $table->date('vacation_begin')->nullable();
            $table->date('vacation_end')->nullable();
            $table->string('leave_type')->nullable();
            $table->integer('substitute01_id')->nullable();
            $table->integer('substitute02_id')->nullable();
            $table->integer('substitute03_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}

