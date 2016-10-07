<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_creates_user_with_unique_verify_token_and_token_expiration_date()
    {
        $user_data = [
            'email'     => rand(1, 100) . '@example.com',
            'password'  => 'password'
        ];

        // Create new user record.
        //
        $user = factory(App\User::class)->create($user_data);

        // Confirm the email of the created account matches that
        // of what we desired to use.
        //
        $this->assertEquals($user->email, $user_data['email']);

        // Confirm that a verify token exists.
        //
        $this->assertNotNull( $user->verify_token );

        // Confirm that a verify token expiration date exists.
        //
        $this->assertNotNull( $user->verify_token_expiry );
    }

    /**
     * @test
     */
    public function it_returns_a_unique_verification_token()
    {
        // Obtain a list of all verify tokens
        //
        $verify_tokens = User::get()->lists('verify_token')->toArray();

        // Now we will generate a new unique verify token.
        //
        $token = User::getModel()->generateUniqueVerifyToken();

        // Now we will confirm that the $token does not exist in the list of $verify_tokens.
        //
        $this->assertTrue( ! in_array($token, $verify_tokens) );
    }
}