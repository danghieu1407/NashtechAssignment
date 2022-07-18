<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_login_and_has_token()
    {
        $body = [
            "email" => "danghieu14072002@gmail.com",
            "password" => "nguyendanghieu"
        ];

        $this->json('POST', 'api/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(200);
        
    }
}
