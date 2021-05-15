@include('header')

<h1>Yatzy Highscores: top 10</h1>

<table class="highscore">
    <tr>
        <th>Rank</th>
        <th>User</th>
        <th>Score</th>
        <th>Date</th>
    </tr>
@foreach ($highscores as $highscore)
    <tr>
        <td>{{ $highscore['rank'] }}</td>
        <td>{{ $highscore['user_id'] }}</td>
        <td>{{ $highscore['score'] }}</td>
        <td>{{ $highscore['date_played'] }}</td>
    </tr>
@endforeach
</table>

@include('footer')
