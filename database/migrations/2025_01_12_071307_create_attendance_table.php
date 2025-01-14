<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->enum('status', ['present', 'absent']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance');
    }
}

