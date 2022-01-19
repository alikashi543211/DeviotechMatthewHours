<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJobTasksTableForRecruiter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('recruiter_id')->nullable();
            $table->foreign('recruiter_id')
                ->references('id')->on('recruiters')->onDelete('cascade');
            $table->string("booked_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_tasks', function (Blueprint $table) {
            //
        });
    }
}
