@include('header')

<h1>Highscores</h1>

<table class="top10">
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th colspan="2" class="bar-header">Score</th>
        <th>Bonus</th>
        <th>Date</th>
        <th>Details</th>
    </tr>
@foreach ($highscores as $highscore)
    <tr>
        <td>{{ $highscore['rank'] }}</td>
        <td>{{ $highscore['name'] }}</td>
        <td class="bar-label">{{ $highscore['score'] }}</td>
        <td class="bar">
            <div class="percent{{ $highscore['percent'] }}"></div>
        </td>
        <td>
            @if ( $highscore['bonus'] == 50 )
                Yes
            @else
                No
            @endif
        </td>
        <td>{{ $highscore['date_played'] }}</td>
        <td><a href="{{ url('/result') }}/{{ $highscore['result_id'] }}"><button class="blue fixed-width">Details</button></a></td>
    </tr>
@endforeach
</table>

<h1>Members by prize money</h1>

<table class="top10">
    <tr>
        <th>Name</th>
        <th colspan="2" class="bar-header">Coins</th>
    </tr>
@foreach ($richest as $rich)
    <tr>
        <td>{{ $rich['name'] }}</td>
        <td class="bar-label">{{ $rich['coins'] }} ¥</td>
        <td class="bar">
            <div class="percent{{ $rich['percent'] }}"></div>
        </td>
    </tr>
@endforeach
</table>

@include('footer')
