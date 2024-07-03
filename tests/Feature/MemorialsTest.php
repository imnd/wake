<?php

use App\Helpers\Statuses;
use App\Models\Memorial;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class MemorialsTest extends TestCase
{
    protected string $prefix = 'memorials';

    private Memorial $memorial;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->memorial = Memorial::factory()->create($this->getMemorialData());
    }

    /**
     * @test
     */
    public function can_see_memorial()
    {
        $this->getRequest(['memorial', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_see_memorials()
    {
        $this->getRequest(['memorials', $this->user->id]);
    }

    /**
     * @test
     */
    public function can_create_memorial()
    {
        $this->postRequest(['create'], $this->getMemorialData());
    }

    /**
     * @test
     */
    public function can_not_create_memorial_with_wrong_dates()
    {
        $dayOfBirth = $this->faker->date;
        $date = (new DateTime($dayOfBirth))->add(DateInterval::createFromDateString('-3 days'));
        $dayOfDeath = $date->format('Y-m-d');

        $this->postRequest(['create'], array_merge($this->getMemorialData(), [
            'day_of_birth' => $dayOfBirth,
            'day_of_death' => $dayOfDeath,
        ]), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function can_update_memorial()
    {
        $this->putRequest(['update', $this->memorial->id], $this->getMemorialData(), Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function can_change_memorial_status()
    {
        $this->patchRequest(
            ['change-status', $this->memorial->id],
            ['status' => Statuses::STATUS_PAID],
            Response::HTTP_NO_CONTENT
        );
        $this->patchRequest(
            ['change-status', $this->memorial->id],
            ['status' => Statuses::STATUS_ARCHIVED],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @test
     */
    public function can_delete_memorial()
    {
        $this->deleteRequest(
            ['delete', $this->memorial->id],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @test
     */
    public function can_upload_avatar()
    {
        $this->postRequest(
            ['upload-avatar', $this->memorial->id],
            $this->getImageUploadParams(),
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @test
     */
    public function can_show_memorial()
    {
        $this->prefix = '';
        $this->getRequestResponse(self::METHOD_GET, ['memorial', $this->memorial->uuid, '']);
    }
}
