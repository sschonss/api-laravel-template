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
        $this->mockConsoleInteraction();
        $this->artisan('jwt:secret');
    }

    private function mockConsoleInteraction()
    {
        $this->instance(
            Symfony\Component\Console\Output\OutputInterface::class,
            Mockery::mock(Symfony\Component\Console\Output\OutputInterface::class)
        );

        $this->instance(
            Symfony\Component\Console\Input\InputInterface::class,
            Mockery::mock(Symfony\Component\Console\Input\InputInterface::class)
        );

        $this->instance(
            Symfony\Component\Console\Style\SymfonyStyle::class,
            Mockery::mock(Symfony\Component\Console\Style\SymfonyStyle::class)
                ->shouldReceive('askQuestion')
                ->andReturn('')
                ->getMock()
        );
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
