<?php

use App\Condition;
use App\Database\Schema\Blueprint;
use App\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * List of allowed comparison options.
     *
     * @var array
     * @see http://php.net/manual/en/language.operators.comparison.php
     */
    protected $comparison_options = Condition::COMPARISON_OPTIONS;

    /**
     * Create a new CreateConditionsTable instance.
     */
    public function __construct()
    {
        $this->schema = $this->getSchemaBuilder();

        $this->schema->blueprintResolver(function($table, $callback)
        {
            return new Blueprint($table, $callback);
        });
    }

    /**
     * Return the schema builder.
     *
     * @return Illuminate\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        $db = DB::connection();
        $db->setSchemaGrammar(new MySqlGrammar);

        return $db->getSchemaBuilder();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('conditions', function (Blueprint $table)
        {
            $table->increments('id');
            $table->set('comparison_options', $this->comparison_options);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conditions');
    }
}
