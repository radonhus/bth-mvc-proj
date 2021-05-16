@include('header')

<div class="game-main-wrapper">

<div class="game-content-left">

<pre>
{{ $data['mode'] }}
{{ $data['bet'] }}
{{ $data['opponent'] }}
{{ $data['challengeId'] }}
</pre>

<h1>Yatzy</h1>

    @if ($data['hideOnGameOver'] == 'hidden')
    <div class="yatzy-status">
        <p>Your final score: {{ $data['totalPoints'] }}</p>

        <form method="post" class="yatzy-form" action="{{ url('/highscores') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="mode" value="{{ $data['mode'] }}">
            <input type="hidden" name="bet" value="{{ $data['bet'] }}">
            <input type="hidden" name="opponent" value="{{ $data['opponent'] }}">
            <input type="hidden" name="challengeId" value="{{ $data['challengeId'] }}">
            <input type="hidden" name="score" value="{{ $data['totalPoints'] }}">
            <input type="hidden" name="bonus" value="{{ $data['bonus'] }}">
            @foreach ($data['pointsPerRound'] as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            @foreach ($data['frequency'] as $key => $value)
                <input type="hidden" name="dice_{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="submit" name="submit" value="Submit results to database" class="submit">
        </form>
    </div>
    @else
    <div class="yatzy-status">
        <p>Your total score so far: {{ $data['totalPoints'] }}</p>
    </div>
    @endif

    <form method="post" class="yatzy-form" action="{{ url('/yatzy') }}">
        @csrf
        <p {{ $data['hideOn2RerollsMade'] }}>Select which dice to roll again (rerolls made: {{ $data['nrOfRerolls'] }})</p>
        <div class="yatzy-dice">
            @foreach ($data['diceArray'] as $key => $value)
            <div class="onedice">
                <img src="{{ asset('/img/' . $value . '.png') }}" class="dice-image"><br>
                <input type="checkbox" name="{{ $key }}" value="selected" {{ $data['hideOn2RerollsMade'] }}>
            </div>
            @endforeach
        </div>
        <input type="submit" name="roll" value="Roll selected dice" class="submit" {{ $data['hideOn2RerollsMade'] }}>
    </form>

</div>

<div class="game-content-right">
    <div class="scorecard-wrapper">
        <h3>Yatzy Scorecard</h3>
        <table class="scorecard">
            <tr>
                <th>Round</th>
                <th>Points</th>
            </tr>

            @foreach ($data['pointsPerRound'] as $key => $value)
            <tr>
                <td>
                    <span class="scorecard-{{ $key }}"></span>
                </td>
                <td>
                @if ($value < 0)
                    <form method="post" action="{{ url('/yatzy') }}">
                    @csrf
                    <input type="hidden" name="roundOver" value="roundOver">
                    @if ($data['showOn2RerollsMade'] == '')
                    <button type="submit" name="selectedRound" value="{{ $key }}" class="submit button green">Save</button>
                    @endif
                    </form>
                @else
                    {{ $value }}
                @endif
                </td>

            @endforeach
            </tr>
            <tr>
                <td>
                    Bonus
                </td>
                <td>
                @if ($data['bonus'] >= 0)
                    {{ $data['bonus'] }}
                @endif
                </td>
            </tr>
        </table>
    </div>
</div>
</div>
@include('footer')
