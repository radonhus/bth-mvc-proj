@include('header')

<h1>Hello {{ auth()->user()->name }}!</h1>

<div class="user-account-wrapper">

    <div class="statusbox left">
    <h2>Statistics</h2>
    <p><span class="bold">Coins</span>: {{ $userCoins }} ¥</p>
    <p><span class="bold">Games played</span>: {{ $stats['countGames'] }}</p>
    <p><span class="bold">Total score</span>: {{ $stats['sumScore'] }}</p>
    <p><span class="bold">Max. score</span>: {{ $stats['maxScore'] }}</p>
    <p><span class="bold">Avg. score</span>: {{ $stats['avgScore'] }}</p>
    <p><span class="bold">Games over 250</span>: {{ $stats['countOver250'] }} ({{ $stats['quotaOver250'] }} %)</p>
    <p><span class="bold">Games with bonus</span>: {{ $stats['countBonus'] }} ({{ $stats['quotaBonus'] }} %)</p>
    </div>

    <div class="statusbox right">
    @if(empty($openChallenges) and empty($openChallengesSent))
    <h2>No open challenges</h2>
    @else
    <h2>Open challenges</h2>
    @endif

    @empty($openChallenges)
    @else
    <h3>Challenges sent to you</h3>
    <table class="highscore">
        <tr>
            <th>Date</th>
            <th>Bet</th>
            <th>Challenger</th>
            <th colspan="2"></th>
        </tr>
    @foreach ($openChallenges as $openChallenge)
        <tr>
            <td>{{ $openChallenge['time'] }}</td>
            <td>{{ $openChallenge['bet'] }} ¥</td>
            <td>{{ $openChallenge['challenger_name'] }}</td>
            <td class="two-buttons-wrapper">
                @if ($openChallenge['denied'] == "denied")
                    <span class="red">You declined</span>
                @else
                <form method="post" action="{{ url('/yatzysetup') }}">
                    @csrf
                    <input type="hidden" name="challengeId" value="{{ $openChallenge['challenge_id'] }}">
                    <input type="hidden" name="opponent" value="{{ $openChallenge['challenger_id'] }}">
                    <input type="hidden" name="bet" value="{{ $openChallenge['bet'] }}">
                    <button type="submit" name="mode" value="accept">Play!</button>
                </form>
                <form method="post" action="{{ url('/myaccount') }}">
                    @csrf
                    <input type="hidden" name="challengeId" value="{{ $openChallenge['challenge_id'] }}">
                    <input type="hidden" name="challenger" value="{{ $openChallenge['challenger_id'] }}">
                    <input type="hidden" name="bet" value="{{ $openChallenge['bet'] }}">
                    <button type="submit" name="deny" value="deny" class="red">Decline</button>
                </form>
                <div>
                @endif
            </td>
        </tr>
    @endforeach
    </table>
    @endempty

    @empty($openChallengesSent)
    @else
    <h3>Challenges sent by you</h3>
    <table class="highscore">
        <tr>
            <th>Date</th>
            <th>Bet</th>
            <th colspan="2">Opponent</th>
        </tr>
    @foreach ( $openChallengesSent as $openChallengeSent)
        <tr>
            <td>{{ $openChallengeSent['time'] }}</td>
            <td>{{ $openChallengeSent['bet'] }} ¥</td>
            <td>{{ $openChallengeSent['opponent_name'] }}</td>
            <td>
                @if ($openChallengeSent['denied'] == "denied")
                    <span class="red">Declined by {{ $openChallengeSent['opponent_name'] }}</span>
                @endif
            </td>
        </tr>
    @endforeach
    </table>
    @endempty

    </div>
</div>

@empty($finishedChallenges)
@else
<h3>Completed challenges</h3>
<table class="highscore">
    <tr>
        <th>Date</th>
        <th>Challenger</th>
        <th>Opponent</th>
        <th>Score</th>
        <th>Result</th>
        <th>Details</th>
    </tr>
@foreach ( $finishedChallenges as $finishedChallenge)
    <tr>
        <td>{{ $finishedChallenge['time'] }}</td>
        <td>{{ $finishedChallenge['challenger_name'] }}</td>
        <td>{{ $finishedChallenge['opponent_name'] }}</td>
        <td>{{ $finishedChallenge['challenger_score'] }} – {{ $finishedChallenge['opponent_score'] }}</td>
        <td>
            @if ($finishedChallenge['winner'] == auth()->user()->id)
                <span class="green">You won ({{ $finishedChallenge['bet']/2 }} ¥ earned)</span>
            @elseif ($finishedChallenge['winner'] == 0)
                Tie
            @else
                <span class="red">You lost ({{ $finishedChallenge['bet']/2 }} ¥ lost)</span>
            @endif
        </td>
        <td><a href="{{ url('/challenge') }}/{{ $finishedChallenge['challenge_id'] }}"><button class="blue fixed-width">Details</button></a></td>
    </tr>
@endforeach
</table>
@endempty

@empty($allGames)
@else
<h3>All games</h3>
<table class="highscore">
    <tr>
        <th>Date</th>
        <th colspan="2" class="bar-header">Score</th>
        <th>Bonus</th>
        <th>Details</th>
    </tr>
@foreach ( $allGames as $game)
    <tr>
        <td>{{ $game['date_played'] }}</td>
        <td class="bar-label">{{ $game['score'] }}</td>
        <td class="bar">
            <div class="percent{{ $game['percent'] }}"></div>
        </td>
        <td>
            @if ( $game['bonus'] == 50 )
                Yes
            @else
                No
            @endif
        </td>
        <td><a href="{{ url('/result') }}/{{ $game['result_id'] }}"><button class="blue fixed-width">Details</button></a></td>
    </tr>
@endforeach
</table>
@endempty

@include('footer')
