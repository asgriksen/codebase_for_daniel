<?php

class TaskModelTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_array_of_statuses()
    {
        // Access the task model.
        //
        $task = App\Task::getModel();

        // Fetch the available status enum values.
        //
        $statuses = $task->getEnumOptions( 'status' );

        // Confirm that an array has been returned.
        //
        $this->assertTrue( is_array( $statuses ) );

        // Define the expected array.
        //
        $expected = [
            'CREATED',
            'PROGRESS',
            'FINISHED'
        ];

        // Assert that $statuses a contains what we expect.
        //
        $this->assertEquals( $expected , $statuses );
    }

    /**
     * @test
     */
    public function it_returns_array_of_types()
    {
        // Access the task model.
        //
        $task = App\Task::getModel();

        // Fetch the available type enum values.
        //
        $types = $task->getEnumOptions( 'type' );

        // Confirm that an array has been returned.
        //
        $this->assertTrue( is_array( $types ) );

        // Define the expected array.
        //
        $expected = [
            'AD_CREATE',
            'AD_UPDATE',
            'AD_REMOVE'
        ];

        // Assert that $types a contains what we expect.
        //
        $this->assertEquals( $expected , $types );
    }
}