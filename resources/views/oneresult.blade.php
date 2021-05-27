@include('header')

<div class="results-main-wrapper">

<div class="one-result-content-left">

    <div class="statusbox left">
    <h2>Game results</h2>
    <p><span class="bold">Player</span>: {{ $result['name'] }}</p>
    <p><span class="bold">Date played</span>: {{ $result['date_played'] }}</p>
    <p><span class="bold">Score</span>: {{ $result['score'] }}</p>
    <p><span class="bold">Bonus</span>: {{ $result['bonus'] }}</p>
    </div>

    <div class="statusbox left histogram-wrapper">
        <h2>Histogram</h2>
        <p>Distribution of the {{ $histogram['sum'] }} dice thrown in this game</p>
        <ul class="histogram">
        <li>
        <span class="percent-height{{ $histogram['percent_1'] }}"></span>
        <p class="dice">Ones</p>
        <p class="stats">{{ $histogram['1'] }} ({{ $histogram['percent_1'] }} %)</p>
        </li>
        <li>
        <span class="percent-height{{ $histogram['percent_2'] }}"></span>
        <p class="dice">Twos</p>
        <p class="stats">{{ $histogram['2'] }} ({{ $histogram['percent_2'] }} %)</p>
        </li>
        <li>
        <span class="percent-height{{ $histogram['percent_3'] }}"></span>
        <p class="dice">Threes</p>
        <p class="stats">{{ $histogram['3'] }} ({{ $histogram['percent_3'] }} %)</p>
        </li>
        <li>
        <span class="percent-height{{ $histogram['percent_4'] }}"></span>
        <p class="dice">Fours</p>
        <p class="stats">{{ $histogram['4'] }} ({{ $histogram['percent_4'] }} %)</p>
        </li>
        <li>
        <span class="percent-height{{ $histogram['percent_5'] }}"></span>
        <p class="dice">Fives</p>
        <p class="stats">{{ $histogram['5'] }} ({{ $histogram['percent_5'] }} %)</p>
        </li>
        <li>
        <span class="percent-height{{ $histogram['percent_6'] }}"></span>
        <p class="dice">Sixes</p>
        <p class="stats">{{ $histogram['6'] }} ({{ $histogram['percent_6'] }} %)</p>
        </li>
        </ul>
    </div>


</div>

<div class="results-content-right">

    <div class="scorecard-wrapper">
        <h3>Yatzy Scorecard</h3>
        <table class="scorecard">
            <tr>
                <th>Round</th>
                <th>Points</th>
            </tr>

            @foreach ($scorecard as $key => $value)
            <tr>
                <td>
                    <span class="scorecard-{{ $key }}"></span>
                </td>
                <td>
                    {{ $value }}
                </td>
            @endforeach
            </tr>
            <tr class="bonus">
                <td>
                    Bonus
                </td>
                <td>
                    {{ $result['bonus'] }}
                </td>
            </tr>
            <tr class="totalpoints">
                <td>
                    Total points:
                </td>
                <td>
                {{ $result['score'] }}
                </td>
            </tr>
        </table>
    </div>

</div>

</div>

@include('footer')
