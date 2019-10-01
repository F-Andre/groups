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
            $table->text('description');
            $table->char('users_id', 255);
            $table->char('admins_id', 255);
            $table->char('on_demand', 255)->default('');
            $table->char('users_warned', 255)->default('');
            $table->string('avatar')->default('public/default/default_avatar.png');
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
