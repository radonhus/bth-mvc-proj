@include('header')

<h1>Highscores: top 10</h1>

<table class="highscore">
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Score</th>
        <th>Bonus?</th>
        <th>Date</th>
        <th>Link</th>
    </tr>
@foreach ($highscores as $highscore)
    <tr>
        <td>{{ $highscore['rank'] }}</td>
        <td>{{ $highscore['name'] }}</td>
        <td>{{ $highscore['score'] }}</td>
        <td>{{ $highscore['bonus'] }}</td>
        <td>{{ $highscore['date_played'] }}</td>
        <td><a href="{{ url('/result') }}/{{ $highscore['result_id'] }}">More info</a></td>
    </tr>
@endforeach
</table>

<h1>Richest players: top 10</h1>

<table class="highscore">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Coins</th>
    </tr>
@foreach ($richest as $rich)
    <tr>
        <td>{{ $rich['id'] }}</td>
        <td>{{ $rich['name'] }}</td>
        <td>{{ $rich['coins'] }}</td>
    </tr>
@endforeach
</table>

@include('footer')
