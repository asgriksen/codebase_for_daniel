<?php

class RuleModelTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_array_of_constants()
    {
        // Access the rule model.
        //
        $rule = App\Rule::getModel();

        // Fetch the constants from the model.
        //
        $constants = $rule->getConstants();

        // Confirm that the $constants is an array.
        //
        $this->assertTrue( is_array($constants) );
    }

    /**
     * @test
     */
    public function it_returns_array_of_strategies()
    {
        // Access the rule model.
        //
        $rule = App\Rule::getModel();

        // Fetch the available strategy enum values.
        //
        $strategies = $rule->getEnumOptions( 'strategy' );

        // Confirm that an array has been returned.
        //
        $this->assertTrue( is_array( $strategies ) );

        // Define the expected array.
        //
        $expected = [
            'ECONOMIC',
            'BALANCED',
            'AGGRESIVE'
        ];

        // Assert that $strategies a contains what we expect.
        //
        $this->assertEquals( $expected , $strategies );
    }
}