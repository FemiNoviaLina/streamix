<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->references('id')->on('group_members');
            $table->string('status');
            $table->dropColumn('user_id');
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('member_id');
            $table->dropColumn('status');
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('group_id')->references('id')->on('sharing_groups');
        });
    }
};
