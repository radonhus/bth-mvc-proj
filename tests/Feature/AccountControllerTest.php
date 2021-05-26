<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\AccountController;
use App\Models\User;
use App\Models\Database\TableResult;
use App\Models\Database\TableChallenges;

class AccountControllerTest extends TestCase
{

    /**
     * Test that instantiated object is instance of AccountController
     */
    public function testInstantiateAccountController()
    {
        $controller = new AccountController();

        $this->assertInstanceOf("App\Http\Controllers\AccountController", $controller);
    }

    /**
     * Check that AccountController class extends Controller
     */
    public function testAccountControllerExtendsController()
    {
        $controller = new AccountController();

        $this->assertInstanceOf("App\Http\Controllers\Controller", $controller);
    }

    /**
     * Test that the register route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testStartView()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    /**
     * Test that the post route /register works = trying to register an
     * already registered user attempts to redirect to the previous page
     *
     * @return void
     */
    public function testVerifySaveRegisterError()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/register', [
            'name' => 'admin',
            'password' => 'admin',
            'password_confirmation' => 'admin'
        ]);

        $response->assertStatus(302);
        $response->assertSee('Redirect');
    }

    /**
     * Test that the post route /register works = trying to register
     * a new user returns the myAccount view
     *
     * @return void
     */
    public function testVerifySaveRegisterOK()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/register', [
            'name' => 'testperson',
            'password' => 'test',
            'password_confirmation' => 'test'
        ]);

        $response->assertStatus(200);
        $response->assertSee('testperson');
    }

    /**
     * Test that the denying challenge renders a
     * view containing the expected string "Live"
     *
     * @return void
     */
    public function testDenyChallenge()
    {
        $users = new User();
        $results = new TableResult();
        $challenges = new TableChallenges();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 0;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();

        $challengerResultId = $latestResult[0]->id;

        $challenges->challenger_user_id = 19;
        $challenges->challenger_result_id = $challengerResultId;
        $challenges->opponent_user_id = 22;
        $challenges->bet = 0;

        $challenges->save();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();

        $challengeId = $latestChallenge[0]->id;

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/myaccount', [
            'challengeId' => $challengeId,
            'challenger' => 19,
            'bet' => '0'
        ]);

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();

        $checkDenied = $latestChallenge[0]->denied;

        $this->assertEquals('denied', $checkDenied);
    }

    /**
     * Remove added database record after test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $usersObject = new User();
        $resultObject = new TableResult();
        $challengesObject = new TableChallenges();

        $usersObject->where('name', 'testperson')->delete();
        $challengesObject->where('challenger_user_id', 19)->delete();
        $resultObject->where('user_id', 19)->delete();
    }
}
