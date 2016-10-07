<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLayerRefIdInFavorOfLayerPolymorphicRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rule_applications', function (Blueprint $table)
        {
            $table->dropColumn('layer');
            $table->dropColumn('ref_id');

            $table->integer('layer_id')->unsigned()->after('rule_id');
            $table->string('layer_type')->after('layer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rule_applications', function (Blueprint $table)
        {
            $table->enum('layer', ['CAMPAIGN','AD','AD_SET'])->nullable();
            $table->string('ref_id', 32)->nullable();
        });
    }
}
