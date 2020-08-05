<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElfsGemtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elfs_gemtypes', function (Blueprint $table) {
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('gemtypeId');
            $table->double('coeff')->default(0);
            $table->timestamps();
        });
        Schema::table('elfs_gemtypes', function (Blueprint $table) {
            $table->foreign('gemtypeId')->references('id')->on('gem_types')->constrained();
            $table->foreign('userId')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elfs_gemtypes');
    }
}
