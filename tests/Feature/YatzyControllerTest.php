<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\YatzyController;
use App\Models\Yatzy\Yatzy;
use App\Models\User;

class YatzyControllerTest extends TestCase
{

    /**
     * Test that instantiated object is instance of YatzyController
     */
    public function testInstantiateYatzyController()
    {
        $controller = new YatzyController();

        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);
    }

    /**
     * Check that YatzyController class extends Controller
     */
    public function testYatzyControllerExtendsController()
    {
        $controller = new YatzyController();

        $this->assertInstanceOf("App\Http\Controllers\Controller", $controller);
    }

    /**
     * Test that gamemode route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testGamemode()
    {
        $usersObject = new User();

        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $users = $usersObject->getAllUsers();
        $coins = $usersObject->getCoins(auth()->user()->id);

        $response = $this->get('/gamemode', [
            'users' => $users,
            'coinsCurrentUser' => $coins
        ]);

        $response->assertStatus(200);
        $response->assertSee('Choose mode');
    }

    /**
     * Test that yatzyview route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testYatzyview()
    {
        $controller = new YatzyController();
        $usersObject = new User();

        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $data = [];

        $data['pointsPerRound'] = [
            "1" => -1, "2" => -1, "3" => -1, "4" => -1, "5" => -1, "6" => -1,
            "one_pair" => -1, "two_pairs" => -1, "three" => -1,
            "four" => -1, "small_straight" => -1, "large_straight" => -1,
            "full_house" => -1, "chance" => -1, "yatzy" => -1
        ];
        $data["nrOfRerolls"] = "1";
        $data["diceArray"] = ["2","1","1","1","1"];
        $data["nrOfRoundsPlayed"] = "5";
        $data["bonus"] = -1;
        $data["totalPoints"] = "5";
        $data["mode"] = "single";
        $data["bet"] = "0";
        $data["opponent"] = "2";
        $data["opponentName"] = "name";
        $data["challengeId"] = "1";
        $data["twoRerollsMade"] = "false";
        $data["gameOver"] = "false";

        session()->put('data', $data);

        $response = $this->get('/yatzyview');

        $response->assertStatus(200);
        $response->assertSee('Live');
    }

    /**
     * Test that the post route /yatzysetup in single game mode returns the
     * expected HTTP response for redirect (302)
     *
     * @return void
     */
    public function testStartSingle()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzysetup', [
            'mode' => 'single',
            'bet' => '0',
            'opponent' => '0',
            'challengeId' => '0'
        ]);

        $response->assertStatus(302);
    }

    /**
     * Test that the post route /yatzysetup in challenge mode returns the
     * expected HTTP response for redirect (302)
     *
     * @return void
     */
    public function testStartChallenge()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzysetup', [
            'mode' => 'challenge',
            'bet' => '0',
            'opponent' => '9',
            'challengeId' => '0'
        ]);

        $response->assertStatus(302);
    }

    /**
     * Test that the post route /yatzyplay returns the expected HTTP response
     * for redirect (302)
     *
     * @return void
     */
    public function testPlay()
    {
        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $yatzyObject = new Yatzy("single", "100", "1", "", "1");
        $yatzyObject->startNewRound();
        session()->put('yatzy', $yatzyObject);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzyplay', [
            '1' => 'selected',
            'roll' => 'Roll!'
        ]);

        $response->assertStatus(302);
    }
}
