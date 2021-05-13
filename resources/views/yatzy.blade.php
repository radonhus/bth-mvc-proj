@include('header')


<h1>Yatzy</h1>

<div class="yatzy-status" {{ $data['hideOnGameOver'] }}>
    <p>Round nr. {{ $data['nrOfRoundsPlayed'] }}/6</p>
    <p>Your score so far: {{ $data['totalPoints'] }}</p>
    <p>Rounds left:</p>
    @foreach ($data['roundsLeft'] as $value)
    <img src="{{ asset('/img/' . $value . '.png') }}" class="dice-image-small">
    @endforeach
</div>

<div class="yatzy-status" {{ $data['showOnGameOver'] }}>
    <p>Your final score: {{ $data['totalPoints'] }}</p>

    <form method="post" class="yatzy-form" action="{{ url('/yatzyScore') }}">
        @csrf
        <p>Enter your name for the highscore list:</p>
        <input type="text" maxlength="40" minlength="3" name="player" class="text">
        <input type="hidden" name="score" value="{{ $data['totalPoints'] }}">
        <input type="submit" name="submit" value="Submit score" class="submit">
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

<form method="post" class="yatzy-form" action="{{ url('/yatzy') }}">
    @csrf
    <label for="selectedRound" {{ $data['showOn2RerollsMade'] }} {{ $data['hideOnGameOver'] }}>Round is over. Save points as: </label>
    <select name="selectedRound" {{ $data['showOn2RerollsMade'] }} {{ $data['hideOnGameOver'] }}>
        @foreach ($data['roundsLeft'] as $value)
            <option value="{{ $value }}">{{ $value }}</option>
        @endforeach
    </select>
    <input type="submit" name="roundOver" value="Save points + start next round" class="submit" {{ $data['showOn2RerollsMade'] }} {{ $data['hideOnGameOver'] }}>
</form>

@include('footer')
