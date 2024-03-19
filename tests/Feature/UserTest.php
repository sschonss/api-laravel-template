<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use GuzzleHttp\Client;


class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('key:generate');
        $this->artisan('jwt:secret');
    }
    private $token;
    public function test_the_register_user_endpoint_returns_a_successful_response(): void
    {
        $guzzle = new GuzzleHttp\Client();
        $response = $guzzle->post('http://localhost/api/register', [
            'form_params' => [
                'name' => 'John Doe',
                'email' => 'john@john.com',
                'password' => 'password',
            ]
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'status' => 'success'
        ]);

    }

    public function test_the_login_user_endpoint_returns_a_successful_response(): void
    {

        $guzzle = new GuzzleHttp\Client();
        $response = $guzzle->post('http://localhost/api/register', [
            'form_params' => [
                'name' => 'John Doe',
                'email' => 'john@john.com',
                'password' => 'password',
            ]
        ]);

        $response = $guzzle->post('http://localhost/api/login', [
            'form_params' => [
                'email' => 'john@john.com',
                'password' => 'password',
            ]
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $this->token = $response->json('access_token');

    }
}
