<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOldoOauthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("SELECT CONCAT( 'DROP TABLE ', GROUP_CONCAT(table_name) , ';' ) 
       AS statement FROM information_schema.tables 
       WHERE table_name LIKE 'oauth_auth%';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
