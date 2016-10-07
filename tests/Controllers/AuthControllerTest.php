<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_fails_validation_due_to_absent_email_and_password()
    {
        // Send an empty POST request.
        //
        $response = $this->call('post', 'api/users/auth');

        // Should return error response.
        //
        $this->assertEquals(401, $response->status());

        // Expected response data.
        //
        $json = [
            'errors' => [
                'email'     => ['The email field is required.'],
                'password'  => ['The password field is required.']
            ]
        ];

        // Confirm that the response message is what we expect.
        //
        $this->seeJson($json);
    }

    /**
     * @test
     */
    public function it_passes_validation_but_fails_login_because_nonexistent_user()
    {
        // Define credentials ofa user that does not exist.
        //
        $data = [
            'email'     => 'john@johnsmith.com',
            'password'  => 'password'
        ];

        // Send a POST call with data.
        //
        $response = $this->call('post', 'api/users/auth', $data);

        // Should return errors because the user doesn't exist.
        //
        $this->assertEquals(401, $response->status());

        // The expected response data.
        //
        $json = [
            'errors' => [
                'email' => ['Invalid email or password.']
            ]
        ];

        // Confirm that the response message is what we expect.
        //
        $this->seeJsonContains($json);
    }

    /**
     * @test
     */
    public function it_passes_validation_and_logs_in_successfully()
    {
        $user_data = [
            'email'     => rand(1, 100) . '@example.com',
            'password'  => 'password'
        ];

        // Create new user record.
        //
        $user = factory(App\User::class)->create($user_data);

        // Since it's a new user record the online_check_at should be null.
        //
        $this->assertNull(null, $user->online_check_at);

        // Send a POST call
        //
        $response = $this->call('post', 'api/users/auth', $user_data);

        // Confirm that the email of the logged in user matches that
        // of the user created.
        //
        $this->assertEquals($user_data['email'], Auth::user()->email);

        // Confirm that the online_check_at field is not longer null because we
        // update this value via the "auth.login" event.
        //
        $this->assertNotNull(Auth::user()->online_check_at);

        // Return that things were successful.
        //
        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     */
    public function it_successfully_logs_the_user_out_of_their_session()
    {
        // Since we're just testing logging out there is no need to import
        // any data facebook upon the creation of the user record.
        //
        $this->withoutEvents();

        $email = rand(1, 100) . '@example.com';

        // Create user account.
        $user = factory(App\User::class)->create([
            'email' => $email
        ]);

        $this->actingAs($user)
             ->visit('api/users/logout')
             ->seeJsonEquals([]);
    }

    /**
     * @test
     */
    public function it_updates_the_users_online_timestamp()
    {
        // Prevent any events firing that would normally pull
        // in data form Facebook.
        //
        $this->withoutEvents();

        $email = rand(1, 100) . '@example.com';

        // Create user account.
        $user = factory(App\User::class)->create([
            'email' => $email
        ]);

        $online_at_before = $user->online_check_at;

        // Login as the user.
        //
        $this->actingAs($user);

        // Submit a post request.
        //
        $response = $this->call('POST', 'api/users/online');

        // Confirm that successful response was received.
        //
        $this->assertEquals(200, $response->status());

        // Confirm that no JSON output exists.
        //
        $this->seeJsonEquals([]);

        // Finally confirm that a new online_check_at timestamp exists.
        //
        $this->assertTrue(
            $online_at_before < $user->online_check_at
        );
    }

    /**
     * @test
     */
    public function it_returns_the_logged_in_users_user_data()
    {
        // Prevent any events firing that would normally pull
        // in data form Facebook.
        //
        $this->withoutEvents();

        $email = rand(1, 100) . '@example.com';

        // Create user account.
        $user = factory(App\User::class)->create([
            'email' => $email
        ]);

        $this->actingAs($user)
             ->visit('api/users/me')
             ->seeJsonContains(['email' => $user->email]);
    }


    /**
     * @test
     */
    public function it_updates_user_record_with_new_name_and_optional_password()
    {
        $this->withoutEvents();

        $email = rand(1, 100) . '@example.com';

        // Create user account.
        $user = factory(App\User::class)->create([
            'email' => $email
        ]);

        // Login as the user.
        //
        $this->actingAs($user);

        // Prepare the post data.
        //
        $post_data = [
          'name' => 'John Smith',
        ];

        $response = $this->call('POST', 'api/users/me', $post_data);

        $this->assertEquals(200, $response->status());

        $content = json_decode($response->getContent());

        // Make sure we're still working with the same record.
        //
        $this->assertEquals($user->email, $content->email);

        // Make sure that the name has changed.
        //
        $this->assertEquals('John Smith', $content->name);

        if( isSet($post_data->password))
        {
            // Check that the user's name has changed.
            $this->assertNotEquals($user->password, $content->password);
        }

    }
}