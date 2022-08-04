<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('first_surname')->nullable();
            $table->string('second_surname')->nullable();
            $table->enum('document_type',['CC','TI','RI']);
            $table->string('document_number');
            $table->string('phone');
            $table->string('address');
            $table->string('email');
            $table->date('birthday');
            $table->enum('active_member',['S','N'])->default('S');
            $table->enum('active',['S','N'])->default('S');
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
        Schema::dropIfExists('members');
    }
}
