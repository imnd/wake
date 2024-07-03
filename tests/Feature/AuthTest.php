<?php

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class AuthTest extends TestCase
{
    protected string $prefix = 'users';
    protected array $userData = [];

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $userData = $this->userData = [
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ];
        $userData['password'] = Hash::make($userData['password']);
        $this->user = User::factory()->create($userData);
    }

    /**
     * @test
     */
    public function user_can_register()
    {
        $result = $this->postRequest('register', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ], Response::HTTP_CREATED);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('avatar', $result);
        $this->assertArrayHasKey('token', $result);
    }

    /**
     * @test
     */
    public function user_can_login_and_logout()
    {
        $this->postRequest('login', $this->userData, Response::HTTP_OK);
        $result = $this->postRequest('logout', Response::HTTP_OK);
        $this->assertArrayHasKey('message', $result);
    }

    /**
     * @test
     */
    public function user_can_forgot_password()
    {
        $this
            ->asGuest()
            ->postRequest('password.email', [
                'email' => $this->user->email,
            ], Response::HTTP_OK)
        ;
    }
}
