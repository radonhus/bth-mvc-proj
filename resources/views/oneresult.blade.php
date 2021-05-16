@include('header')

<div class="results-main-wrapper">

<div class="results-content-left">

    <h1>Game results</h1>

    <pre>
    @foreach ($result as $key => $value)
        {{ $key }}: {{ $value }}
    @endforeach

    @foreach ($histogram as $key => $value)
        {{ $key }}: {{ $value }}
    @endforeach
    </pre>

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
