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

@empty($openChallenges)
@else
<h3>You have been challenged! Your open challenges:</h3>
<table class="highscore">
    <tr>
        <th>Open since</th>
        <th>Bet</th>
        <th>Challenger</th>
        <th colspan="2">Respond</th>
    </tr>
@foreach ( $openChallenges as $openChallenge)
    <tr>
        <td>{{ $openChallenge['time'] }}</td>
        <td>{{ $openChallenge['bet'] }} ¥</td>
        <td>{{ $openChallenge['challenger_name'] }}</td>
        <td>
            <form method="post" action="{{ url('/yatzystart') }}">
                @csrf
                <input type="hidden" name="challengeId" value="{{ $openChallenge['id'] }}">
                <input type="hidden" name="opponent" value="{{ $openChallenge['challenger_id'] }}">
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
@endempty

@empty($openChallengesSent)
@else
<h3>Challenges opened by you (waiting for opponent to accept):</h3>
<table class="highscore">
    <tr>
        <th>Open since</th>
        <th>Bet</th>
        <th>Challenger</th>
    </tr>
@foreach ( $openChallengesSent as $openChallengeSent)
    <tr>
        <td>{{ $openChallengeSent['time'] }}</td>
        <td>{{ $openChallengeSent['bet'] }} ¥</td>
        <td>{{ $openChallengeSent['opponent_name'] }}</td>
    </tr>
@endforeach
</table>
@endempty

@empty($finishedChallenges)
@else
<h3>Finished challenges:</h3>
<table class="highscore">
    <tr>
        <th>Challenged opened</th>
        <th>Challenger</th>
        <th>Opponent</th>
        <th>Score</th>
        <th>Result</th>
        <th>Link</th>
    </tr>
@foreach ( $finishedChallenges as $finishedChallenge)
    <tr>
        <td>{{ $finishedChallenge['time'] }}</td>
        <td>{{ $finishedChallenge['challenger_name'] }}</td>
        <td>{{ $finishedChallenge['opponent_name'] }}</td>
        <td>{{ $finishedChallenge['challenger_score'] }} – {{ $finishedChallenge['opponent_score'] }}</td>
        <td>
            @if ($finishedChallenge['winner'] == auth()->user()->id)
                <span class="green">Win ({{ $finishedChallenge['bet']/2 }} ¥ won)</span>
            @elseif ($finishedChallenge['winner'] == 0)
                Tie
            @else
                <span class="red">Loss ({{ $finishedChallenge['bet']/2 }} ¥ lost)</span>
            @endif
        </td>
        <td><a href="{{ url('/challenge') }}/{{ $finishedChallenge['challenge_id'] }}">More info</a></td>
    </tr>
@endforeach
</table>
@endempty

@include('footer')
