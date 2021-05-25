<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RootRouteTest extends TestCase
{
    /**
     * Test that the routes render an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testRootRoute()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }
}
