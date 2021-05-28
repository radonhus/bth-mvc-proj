<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\SessionController;
use App\Models\User;

class SessionControllerTest extends TestCase
{

    /**
     * Test that instantiated object is instance of SessionController
     */
    public function testInstantiateSessionController()
    {
        $controller = new SessionController();

        $this->assertInstanceOf("App\Http\Controllers\SessionController", $controller);
    }

    /**
     * Check that SessionController class extends Controller
     */
    public function testSessionControllerExtendsController()
    {
        $controller = new SessionController();

        $this->assertInstanceOf("App\Http\Controllers\Controller", $controller);
    }

    /**
     * Test that the / route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testStartView()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Log in to play');
    }

    /**
     * Test that the / route renders an OK HTTP response (200) as logged in
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testStartViewLoggedIn()
    {
        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $response = $this->get('/');

        $response->assertSee('Hello');
    }

    /**
     * Test that the login route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testLoginView()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Log in to play');
    }


    /**
     * Test that the post route returns expected HTTP response (302)
     *
     * @return void
     */
    public function testVerifyCreate()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/login', [
            'name' => 'admin',
            'password' => 'admin'
        ]);

        $response->assertStatus(302);
        $response->assertSee('Redirect');
    }


    /**
     * Test that the post route returns expected HTTP response (302) with error
     * when attempting to login with wrong credentials
     *
     * @return void
     */
    public function testVerifyCreateError()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/login', [
            'name' => 'admin',
            'password' => 'wrong'
        ]);

        $response->assertSessionHasErrors();
        $response->assertStatus(302);
        $response->assertSee('Redirect');
    }

    /**
     * Test that logout logs out the user
     *
     * @return void
     */
    public function testLogoutView()
    {
        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $before = auth()->check();

        $this->get('/logout');

        $after = auth()->check();

        $this->assertEquals(true, $before);
        $this->assertEquals(false, $after);
    }
}
