<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostToRepairJobsTable extends Migration
{
    public function up()
    {
        Schema::table('repair_jobs', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('tasks');
        });
    }

    public function down()
    {
        Schema::table('repair_jobs', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
}
