<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairJobsTable extends Migration
{
    public function up()
    {
        Schema::create('repair_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
            $table->unsignedBigInteger('technician_id');
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['New', 'InRepair', 'Completed'])->default('New');
            $table->text('tasks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_jobs');
    }
}

