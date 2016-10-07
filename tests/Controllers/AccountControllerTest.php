<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_denies_access_due_to_not_being_logged_in()
    {
        $response = $this->call('GET', 'api/accounts');

        $this->assertEquals(401, $response->status());

        $this->seeJson(['You are not authorized.']);
    }
}