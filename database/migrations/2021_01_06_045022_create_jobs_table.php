<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('contact_person')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->string('job_value')->nullable();
            $table->unsignedBigInteger('job_status_id')->nullable();
            $table->foreign('job_status_id')->references('id')->on('job_statuses')->onDelete('cascade');
            $table->unsignedBigInteger('job_source_id')->nullable();
            $table->foreign('job_source_id')->references('id')->on('job_sources')->onDelete('cascade');
            $table->text("comment")->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('revenue')->nullable();
            $table->string('revenue_per_hour')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
