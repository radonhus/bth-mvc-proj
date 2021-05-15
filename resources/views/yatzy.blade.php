@include('header')

<div class="game-main-wrapper">

<div class="game-content-left">

<h1>Yatzy</h1>

    <div class="yatzy-status" {{ $data['hideOnGameOver'] }}>
        <p>Your total score so far: {{ $data['totalPoints'] }}</p>
    </div>

    <div class="yatzy-status" {{ $data['showOnGameOver'] }}>
        <p>Your final score: {{ $data['totalPoints'] }}</p>

        <form method="post" class="yatzy-form" action="{{ url('/yatzyScore') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
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

@isset($data['frequency'])
<pre>
Histogram:
@foreach ($data['frequency'] as $key => $value)
    {{ $key }}: {{ $value }}
@endforeach
</pre>
@endisset

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
                    <button type="submit" name="selectedRound" value="{{ $key }}" class="submit button green" {{ $data['showOn2RerollsMade'] }} {{ $data['hideOnGameOver'] }}>Save</button>
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
