<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateGemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('gems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gemtype');
            $table->timestamp('extract_date', 0)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('assign_date', 0)->nullable();
            $table->timestamp('confirm_date', 0)->nullable();
            $table->unsignedBigInteger('earner');
            $table->unsignedBigInteger('approver')->nullable();
            $table->enum('method', ['auto', 'manual'])->nullable();
            $table->unsignedBigInteger('owner')->nullable();
            $table->enum('status', ['assigned', 'confirmed', 'not_assigned'])->default('not_assigned');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::table('gems', function (Blueprint $table) {
            $table->foreign('gemtype')->references('id')->on('gem_types')->constrained();
            $table->foreign('earner')->references('id')->on('users')->constrained();
            $table->foreign('approver')->references('id')->on('users')->constrained();
            $table->foreign('owner')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gems');
    }
}
