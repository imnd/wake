<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class UserTest extends TestCase
{
    protected string $prefix = 'users';
    protected array $userData = [];
    private Model $foreignUser;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->foreignUser = User::factory()->create();
    }

    /**
     * @test
     */
    public function can_get_user_details()
    {
        $result = $this->getRequest(['short-details', $this->user->id]);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_get_full_user_details()
    {
        $result = $this->getRequest(['full-details', $this->user->id]);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_update_own_profile()
    {
        $this->putRequest(['update', $this->user->id], [
            'name' => 'Momo Baoze',
            'email' => 'momo@mail.com',
            'password' => 'new_password',
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function can_not_update_foreign_profile()
    {
        $this->putRequest(['update', $this->foreignUser->id], [
            'name' => 'Momo Baoze',
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function admin_can_disable_user()
    {
        $this->patchRequest(['destroy', $this->foreignUser->id], Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function can_upload_avatar()
    {
        $this->postRequest(
            ['upload-avatar', $this->user->id],
            $this->getImageUploadParams(),
            Response::HTTP_NO_CONTENT
        );
    }
}
