<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('jwt:secret');
    }
    private $token;
    public function test_the_register_user_endpoint_returns_a_successful_response(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@john.com',
            'password' => 'password',
            ]);

        $response->assertStatus(201);

        $response->assertJson([
            'status' => 'success'
        ]);

    }

    public function test_the_login_user_endpoint_returns_a_successful_response(): void
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@john.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/login', [
            'email' => 'john@john.com',
            'password' => 'password',
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
