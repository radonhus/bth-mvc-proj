@include('header')

<h1>Challenge Results</h1>

<div class="results-main-wrapper">

<div class="results-content-left">

    <div class="statusbox">
        <h2>{{ $challenge['challenger_name'] }}</h2>
        <p><span class="bold">Score</span>: {{ $challenge['challenger_score'] }}</p>
    </div>

    <div class="scorecard-wrapper">
        <h3>Yatzy Scorecard</h3>
        <table class="scorecard">
            <tr>
                <th>Round</th>
                <th>Points</th>
            </tr>

            @foreach ($scorecardChallenger as $key => $value)
            <tr>
                <td>
                    <span class="scorecard-{{ $key }}"></span>
                </td>
                <td>
                    {{ $value }}
                </td>
            @endforeach
            </tr>
            <tr class="bonus">
                <td>
                    Bonus
                </td>
                <td>
                    {{ $resultChallenger['bonus'] }}
                </td>
            </tr>
            <tr class="totalpoints">
                <td>
                    Total points:
                </td>
                <td>
                {{ $resultChallenger['score'] }}
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="results-content-center">

    @if ( $challenge['winner'] == auth()->user()->id)
        <h1 class="green">YOU WON!</h1>
        <span class="green result-icon">E</span>
        <h2 class="green">This challenge earned you {{ $challenge['bet']/2 }} ¥!</h2>
    @elseif ($challenge['winner'] == 0)
        <span class="result-icon">J</span>
        <h1>IT'S A TIE!</h1>
    @else
        <h1 class="red">YOU LOST!</span></h1>
        <span class="red result-icon">g</span>
        <h2 class="red">This challenge made you {{ $challenge['bet']/2 }} ¥ poorer...</h2>

    @endif

</div>

<div class="results-content-right">

    <div class="statusbox">
        <h2>{{ $challenge['opponent_name'] }}</h2>
        <p><span class="bold">Score</span>: {{ $challenge['opponent_score'] }}</p>
    </div>

    <div class="scorecard-wrapper">
        <h3>Yatzy Scorecard</h3>
        <table class="scorecard">
            <tr>
                <th>Round</th>
                <th>Points</th>
            </tr>

            @foreach ($scorecardOpponent as $key => $value)
            <tr>
                <td>
                    <span class="scorecard-{{ $key }}"></span>
                </td>
                <td>
                    {{ $value }}
                </td>
            @endforeach
            </tr>
            <tr class="bonus">
                <td>
                    Bonus
                </td>
                <td>
                    {{ $resultOpponent['bonus'] }}
                </td>
            </tr>
            <tr class="totalpoints">
                <td>
                    Total points:
                </td>
                <td>
                {{ $resultOpponent['score'] }}
                </td>
            </tr>
        </table>
    </div>
</div>

@include('footer')
