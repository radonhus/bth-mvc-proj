@include('header')

<h1>Start a new game</h1>

<div class="form-box">

    <div class="width-wrapper">

    <h1>Challenge</h1>

    <p>Challenge another member to a game of Yatzy. The person challenged will
    be able to see and accept your challenge once they log in to their account.
    </p>

    <p>You can bet any amount, from 0 to {{ $coinsCurrentUser }} ¥ (your current balance).
    If your opponent does not have enough money in their account, the bet will
    be lowered accordingly. The winner of the challenge wins the whole pot.</p>

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
        <label for="bet">Your bet: </label>
        <input type="number" name="bet" value="0" min="0" max="{{ $coinsCurrentUser }}" required>
        <button type="submit" name="mode" value="challenge">Start challenge</button>
    </form>

    </div>

</div>

<div class="form-box">

    <div class="width-wrapper">

    <h1>Single player game</h1>

    <p>
    Can you reach 250 points or more? If you do, you win twice your bet from the
    bank. You can bet any amount, from 0 to {{ $coinsCurrentUser }} ¥ (your current balance).
    </p>

    <form method="post" action="{{ url('/yatzystart') }}">
        @csrf
        <input type="hidden" name="opponent" value="0">
        <input type="hidden" name="challengeId" value="0">
        <label for="bet">Your bet: </label>
        <input type="number" name="bet" value="0" min="0" max="{{ $coinsCurrentUser }}" required>
        <button type="submit" name="mode" value="single">Start single player game</button>
    </form>

    </div>

</div>

@include('footer')
