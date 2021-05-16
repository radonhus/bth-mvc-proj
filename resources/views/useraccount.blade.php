@include('header')

<h1>{{ auth()->user()->name }}'s Account</h1>

<pre>
Användarinfo:

Id: {{ auth()->user()->id }}
Coins: {{ $userCoins }} ¥

Statistik:
Sammanlagt histogram

Sammanlagd poäng

Medelpoäng per match

Medel-Histogram per match

Listor:

Result list (max 30?)

Finished challenges (med info om wins/losses)
</pre>

<h3>Open challenges:</h3>
<table class="highscore">
    <tr>
        <th>Id</th>
        <th>Time</th>
        <th>Bet</th>
        <th>Challenger</th>
        <th colspan="2">Respond</th>
    </tr>
@foreach ( $openChallenges as $openChallenge)
    <tr>
        <td>{{ $openChallenge['id'] }}</td>
        <td>{{ $openChallenge['time'] }}</td>
        <td>{{ $openChallenge['bet'] }} ¥</td>
        <td>{{ $openChallenge['challenger_user_id'] }}</td>
        <td>
            <form method="post" action="{{ url('/yatzystart') }}">
                @csrf
                <input type="hidden" name="challengeId" value="{{ $openChallenge['id'] }}">
                <input type="hidden" name="opponent" value="{{ $openChallenge['challenger_user_id'] }}">
                <input type="hidden" name="bet" value="{{ $openChallenge['bet'] }}">
                <button type="submit" name="mode" value="accept">Accept – Start game!</button>
            </form>
        </td>
        <td>
            <form method="post" action="{{ url('/deny') }}">
                @csrf
                <input type="hidden" name="challengeId" value="{{ $openChallenge['id'] }}">
                <button type="submit" name="mode" value="challenge">Deny Challenge</button>
            </form>
        </td>
    </tr>
@endforeach
</table>

@include('footer')
