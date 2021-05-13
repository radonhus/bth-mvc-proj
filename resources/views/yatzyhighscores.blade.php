@include('header')

<h1>Yatzy Highscores: top 10</h1>

<table>
    <tr>
        <th>Rank</th>
        <th>Player</th>
        <th>Score</th>
        <th>Date</th>
    </tr>
@foreach ($highscores as $highscore)
    <tr>
        <td>{{ $highscore['rank'] }}</td>
        <td>{{ $highscore['player'] }}</td>
        <td>{{ $highscore['score'] }}</td>
        <td>{{ $highscore['date_played'] }}</td>
    </tr>
@endforeach
</table>

@include('footer')
