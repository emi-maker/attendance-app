<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStatusColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('work_status')->nullable();
        });

        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->integer('request_status')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('work_status');
        });

        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dropColumn('request_status');
        });
    }
}
