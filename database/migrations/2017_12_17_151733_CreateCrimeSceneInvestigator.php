<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrimeSceneInvestigator extends Migration
{
    const TABLE_NAME = "crimeSceneInvestigator";
    const COL_ID = "crimeSceneInvestigator_id";


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->integer(self::COL_ID)->unsigned();

            $table->foreign(self::COL_ID)->references("rolePolice_id")->on("rolePolice");

            $table->softDeletes();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::table(self::TABLE_NAME)->insert([
            "crimeSceneInvestigator_id" => 2
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(self::TABLE_NAME);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
