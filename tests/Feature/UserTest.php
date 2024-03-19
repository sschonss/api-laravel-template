<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use GuzzleHttp\Client;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_register_user(): void
    {
        $user = new User();
        $user->name = 'Teste';
        $user->email = 'teste@teste.com';
        $user->password = '123456789';
        $user->save();

        $this->assertTrue($user->id > 0);

        $user->delete();
    }

    public function test_update_user(): void
    {
        $user = new User();
        $user->name = 'Teste';
        $user->email = 'teste@teste.com';
        $user->password = '123456789';
        $user->save();

        $user->name = 'Teste 2';
        $user->save();

        $this->assertEquals('Teste 2', $user->name);

        $user->delete();

    }


}
