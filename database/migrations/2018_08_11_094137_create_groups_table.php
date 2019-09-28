<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 80)->unique();
            $table->char('users_id', 255);
            $table->char('admins_id', 255);
            $table->char('on_demand', 255)->default(NULL);
            $table->char('users_warned', 255)->default(NULL);
            $table->string('avatar')->default('0');
            $table->dateTime('active_at');
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
        Schema::dropIfExists('groups');
    }
}
