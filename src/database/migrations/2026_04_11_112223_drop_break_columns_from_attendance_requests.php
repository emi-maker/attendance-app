<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBreakColumnsFromAttendanceRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dropColumn(['request_break_start', 'request_break_end']);
    });
}  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->time('request_break_start')->nullable();
            $table->time('request_break_end')->nullable();
    });  
}
}