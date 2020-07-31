<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserRoleCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('security_roles')->where('id', 13)->update(['code' => 'PROVINCIAL_COORDINATOR']);
        DB::table('security_roles')->where('id', 14)->update(['code' => 'ZONAL_COORDINATOR']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('security_roles')->where('id', 13)->update(['code' => '']);
        DB::table('security_roles')->where('id', 14)->update(['code' => '']);
    }
}
