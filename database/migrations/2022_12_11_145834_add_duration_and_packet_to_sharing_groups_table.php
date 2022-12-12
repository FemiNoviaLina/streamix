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
        Schema::table('sharing_groups', function (Blueprint $table) {
            $table->string('packet');
            $table->unsignedInteger('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sharing_groups', function (Blueprint $table) {
            $table->dropColumn('packet');
            $table->dropColumn('duration');
        });
    }
};
