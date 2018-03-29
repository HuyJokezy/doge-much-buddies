<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owner')->unsigned();
            $table->enum('breed', ['Mixed', 
                                    'Labrador Retriever',
                                    'German Shepherds',
                                    'Golden Retriever',
                                    'Bulldog',
                                    'Boxer',
                                    'Rottweiler',
                                    'Dachshund',
                                    'Husky',
                                    'Great Dane',
                                    'Doberman Pinschers',
                                    'Australian Shepherds',
                                    'Corgi',
                                    'Shiba']);
            $table->enum('gender', ['Male', 'Female']);
            $table->string('profile_image')->nullable();
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('ownerfriendship', function (Blueprint $table) {
            $table->integer('owner1')->unsigned();
            $table->integer('owner2')->unsigned();
            $table->timestamps();

            $table->foreign('owner1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('owner2')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dogs');
        Schema::dropIfExists('ownerfriendship');
    }
}
