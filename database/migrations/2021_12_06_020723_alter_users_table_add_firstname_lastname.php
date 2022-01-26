<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterUsersTableAddFirstnameLastname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 60)->after('name');
            $table->string('last_name', 60)->after('first_name');
        });

        DB::table('users')->chunkById(1, function($users) {
            foreach($users as $user) {
                $userModel = \App\Models\User::find($user->id);
                $name_arr = explode(' ', $user->name);
                $userModel->first_name = $name_arr[0];
                $userModel->last_name = $name_arr[1];
                $userModel->save();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        DB::table('users')->chunkById(10, function($users) {
            foreach($users as $user) {
                $userModel = \App\Models\User::find($user->id);
                $userModel->name = $userModel->first_name.' '.$userModel->last_name;
                $userModel->save();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
}
