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
    public function user_can_login()
    {
        $this->postRequest(['login'], $this->userData, Response::HTTP_OK);
    }
}
