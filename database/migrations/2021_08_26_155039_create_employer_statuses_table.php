<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employer_id")->references('id')->on("employers");
            $table->timestamp("online_at");
            $table->timestamp("offline_at")->nullable();
            $table->timestamps();

            // todo think about the db again
            // emp id       online_at       offline_at
            // 1            5:30            6:00
            // 2            7:00            >>>>

            // emp id       status              from        to
            // 1            online            6:00         6:30
            // 2            offline            6:30         >>>>
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employer_statuses');
    }
}
