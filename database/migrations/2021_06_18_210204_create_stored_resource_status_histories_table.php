<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoredResourceStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stored_resource_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stored_resource_id');
            $table->unsignedBigInteger('stored_resource_content_id')->nullable();
            $table->integer('request_execution_time')->default(0);
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('stored_resource_status_histories');
    }
}
