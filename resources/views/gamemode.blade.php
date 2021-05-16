@include('header')

<h1>Choose game mode</h1>

<form method="post" action="{{ url('/yatzystart') }}">
    @csrf
    <input type="hidden" name="challengeId" value="0">
    <label for="opponent">Choose opponent: </label>
    <select name="opponent" required>
        @foreach ($users as $user)
        @if ($user['id'] != auth()->user()->id)
            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
        @endif
        @endforeach
    </select>
    <label for="bet">Your bet (win: 2 x bet if you beat your opponent): </label>
    <input type="number" name="bet" value="0" min="0" max="{{ $coinsCurrentUser }}" required>
    <button type="submit" name="mode" value="challenge">Start challenge game</button>
</form>

<br><br>

<form method="post" action="{{ url('/yatzystart') }}">
    @csrf
    <input type="hidden" name="opponent" value="0">
    <input type="hidden" name="challengeId" value="0">
    <label for="bet">Your bet (win: 2 x bet if you reach 250 points): </label>
    <input type="number" name="bet" value="0" min="0" required>
    <button type="submit" name="mode" value="single">Start single player game</button>
</form>

@include('footer')
