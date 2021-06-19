<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoredResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stored_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('protocol')->default('https');
            $table->string('domain');
            $table->integer('port')->default(80);
            $table->ipAddress('ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('last_status')->nullable();
            $table->integer('last_request_execution_time')->default(0);
            $table->dateTime('last_checked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stored_resources');
    }
}
