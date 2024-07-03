<?php

namespace Tests;

use App\Models\Memorial;
use App\Models\User;
use App\Services\PaymentService;
use DateInterval;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Stripe\Customer;
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
    protected bool $guest = false;

    protected string $prefix = '';
    protected ?Customer $customer;

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
        $paymentService = new PaymentService();
        $this->customer = $paymentService->createCustomer([
            'name'  => $this->faker->firstName,
            'email' => $this->user->email
        ]);

        $this->withoutExceptionHandling();
    }

    protected function asGuest(): static
    {
        $this->guest = true;
        return $this;
    }

    protected const METHOD_GET = 'get';
    protected const METHOD_POST = 'post';
    protected const METHOD_PUT = 'put';
    protected const METHOD_PATCH = 'patch';
    protected const METHOD_DELETE = 'delete';

    protected function getRequest(
        mixed $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult(self::METHOD_GET, $routeParams, $queryParams, $status);
    }

    protected function postRequest(
        mixed $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult(self::METHOD_POST, $routeParams, $queryParams, $status);
    }

    protected function putRequest(
        mixed $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult(self::METHOD_PUT, $routeParams, $queryParams, $status);
    }

    protected function patchRequest(
        mixed $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult(self::METHOD_PATCH, $routeParams, $queryParams, $status);
    }

    protected function deleteRequest(
        mixed $routeParams,
        mixed $queryParams = [],
        int $status = null
    ): array {
        return $this->getRequestResult(self::METHOD_DELETE, $routeParams, $queryParams, $status);
    }

    // Expected HTTP status code
    protected const SUCCESS_STATUSES = [
        self::METHOD_GET => Response::HTTP_OK,
        self::METHOD_POST => Response::HTTP_CREATED,
        self::METHOD_PUT => Response::HTTP_NO_CONTENT,
        self::METHOD_PATCH => Response::HTTP_NO_CONTENT,
        self::METHOD_DELETE => Response::HTTP_NO_CONTENT,
    ];

    protected function getRequestResult(
        string $method,
        mixed $routeParams = [],
        mixed $queryParams = [],
        int $status = null,
    ) {
        $response = $this->getRequestResponse($method, $routeParams, $queryParams, $status);

        return $response?->json() ?: [];
    }

    protected function getRequestResponse(
        string $method,
        mixed $routeParams = [],
        mixed $queryParams = [],
        int &$status = null,
    ) {
        $route = $this->getRoute(...(array)$routeParams);

        if (is_integer($queryParams)) {
            $status = $queryParams;
            $queryParams = [];
        }

        if (is_null($status)) {
            $status = self::SUCCESS_STATUSES[$method] ?? Response::HTTP_OK;
        }

        if (in_array($status, self::SUCCESS_STATUSES)) {
            $this->withoutExceptionHandling();
        } else {
            $this->withExceptionHandling();
        }

        $method = "{$method}Json";

        $response = $this;
        if (!$this->guest) {
            $response = $response
                ->actingAs($this->user, 'api');
        }

        $response = $response
            ->$method(
                $route,
                $queryParams
            );

        $response->assertStatus($status);

        return $status === Response::HTTP_NO_CONTENT ? null : $response;
    }

    protected function getRoute(string $action, mixed $params = [], $path = 'api.v1'): string
    {
        if ($this->prefix) {
            if ($path !== '') {
                $path .= '.';
            }
            $path .= "{$this->prefix}";
        }

        if ($path !== '') {
            $path .= '.';
        }
        $path .= $action;

        return route($path, $params);
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
            'day_of_birth' => $dayOfBirth = $this->faker->date,
            'day_of_death' => (new DateTime($dayOfBirth))
                ->add(DateInterval::createFromDateString('30 years'))
                ->format('Y-m-d'),
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
