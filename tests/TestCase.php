<?php

namespace Tests;

use App\Models\Memorial;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\{
    TestCase as BaseTestCase,
    WithFaker,
    RefreshDatabase
};

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use WithFaker;

    protected User $user;
    protected string $prefix = '';

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create([
            'is_admin' => true,
        ]);
        $this->withoutExceptionHandling();
    }

    protected function getRequest(
        array $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult('get', $routeParams, $queryParams, $status);
    }

    protected function postRequest(
        array $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult('post', $routeParams, $queryParams, $status);
    }

    protected function putRequest(
        array $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult('put', $routeParams, $queryParams, $status);
    }

    protected function patchRequest(
        array $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult('patch', $routeParams, $queryParams, $status);
    }

    protected function deleteRequest(
        array $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult('delete', $routeParams, $queryParams, $status);
    }

    protected function getRequestResult(
        string $method,
        array $routeParams = [],
        mixed $queryParams = [],
        int $status = null
    ): array {
        $route = $this->getRoute(...$routeParams);

        if (is_integer($queryParams)) {
            $status = $queryParams;
            $queryParams = [];
        }
        // Expected HTTP status code
        if (is_null($status)) {
            $status = [
                'get' => Response::HTTP_OK,
                'post' => Response::HTTP_CREATED,
                'put' => Response::HTTP_NO_CONTENT,
                'patch' => Response::HTTP_NO_CONTENT,
                'delete' => Response::HTTP_NO_CONTENT,
            ][$method] ?? Response::HTTP_OK;
        }

        $method = "{$method}Json";
        $response = $this
            ->actingAs($this->user, 'api')
            ->$method(
                $route,
                $queryParams
            );

        $response->assertStatus($status);

        return $status === Response::HTTP_NO_CONTENT ? [] : $response->json();
    }

    protected function getRoute(string $action, mixed $params = []): string
    {
        $path = 'api.v1';

        if ($this->prefix) {
            $path .= ".{$this->prefix}";
        }

        return route("$path.$action", $params);
    }

    # FAKE DATA FOR TESTING

    protected function getMemorialData(): array
    {
        return [
            'user_id' => $this->user->id,
            'title' => $this->faker->title,
            'text' => $this->faker->text,
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => [
                Memorial::GENDER_MALE,
                Memorial::GENDER_FEMALE,
                Memorial::GENDER_OTHER,
            ][$this->faker->numberBetween(0, 2)],
            'place_of_birth' => $this->faker->address,
            'place_of_death' => $this->faker->address,
            'day_of_birth' => $this->faker->date,
            'day_of_death' => $this->faker->date,
        ];
    }

    protected function getBouquetData($memorial): array
    {
        return [
            'memorial_id' => $memorial->id,
            'user_id' => $this->user->id,
        ];
    }

    protected function getImage(): File
    {
        return UploadedFile::fake()->image("{$this->faker->userName}.jpg");
    }

    protected function getImageUploadParams(): array
    {
        return [
            'name' => $this->faker->userName,
            'file' => $this->getImage(),
        ];
    }

    protected function getVideo(): File
    {
        return UploadedFile::fake()->create("{$this->faker->userName}.mpg", 1000, 'video/mpeg');
    }

    protected function getVideoUploadParams(): array
    {
        return [
            'name' => $this->faker->userName,
            'file' => $this->getVideo(),
        ];
    }
}
