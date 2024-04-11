<?php

use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class BouquetTypesTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_bouquet_types()
    {
        $result = $this->getRequest(['bouquet-types']);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('price', $result[0]);
        $this->assertArrayNotHasKey('error', $result);
    }
}
