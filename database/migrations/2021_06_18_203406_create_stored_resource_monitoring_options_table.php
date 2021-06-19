<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoredResourceMonitoringOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stored_resource_monitoring_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stored_resource_id');
            $table->unsignedBigInteger('monitoring_option_id');
            $table->string('request_type')->default('GET');
            $table->text('request_headers')->nullable();
            $table->text('request_parameters')->nullable();
            $table->text('request_cookies')->nullable();
            $table->text('request_body')->nullable();
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
        Schema::dropIfExists('stored_resource_monitoring_options');
    }
}
