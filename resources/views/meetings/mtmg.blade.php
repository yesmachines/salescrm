@php($mtmg = 0)
<table border="1" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th rowspan="2" style="background-color: yellow; border: 1px solid black; text-align: center; font-weight: bold;">Day</th>
            <th colspan="{{ $maxMeetings }}" style="background-color: yellow; border: 1px solid black; text-align: center; font-weight: bold;">Client Name</th>
            <th colspan="{{ $maxMeetings }}" style="background-color: yellow; border: 1px solid black; text-align: center; font-weight: bold;">Meeting Grade</th>
            <th rowspan="2" style="background-color: yellow; border: 1px solid black; text-align: center; font-weight: bold;">DTMG</th>
        </tr>
        <tr>
            @foreach(range(1, $maxMeetings) as $i)
            <th style="background-color: #bfbfbf; border: 1px solid black; text-align: center;">Meeting {{ $i }}</th>
            @endforeach
            @foreach(range(1, $maxMeetings) as $i)
            <th style="background-color: #bdd6ee; border: 1px solid black; text-align: center;">Meeting {{ $i }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($formattedMeetings as $day => $meetings)
        <tr>
            <td>{{ $meetings['disp_day'] }}</td>
            {{-- Client Names --}}
            @foreach($meetings['clients'] as $client)
            <td>{{ $client }}</td>
            @endforeach
            @for($i = count($meetings['clients']); $i < $maxMeetings; $i++)
            <td></td>
            @endfor

            {{-- Meeting Grades --}}
            @foreach($meetings['grades'] as $grade)
            <td style="text-align: right;">{{ $grade }}</td>
            @endforeach
            @for($i = count($meetings['grades']); $i < $maxMeetings; $i++)
            <td></td>
            @endfor

            {{-- Daily Total Grade (DTMG) --}}
            <td style="text-align: right;">{{ $meetings['total'] }}</td>
        </tr>
        @php($mtmg += $meetings['total'])
        @endforeach
        <tr>
            @for($i = 1; $i <= (2 * $maxMeetings); $i++)
            <td></td>

            @if($i == 6)
            <td style="background-color: #92d050; font-weight: bold; border: 1px solid black;">MTMG</td>
            @endif

            @if($i == (2 * $maxMeetings))
            <td style="text-align: right; border: 1px solid black;">{{$mtmg}}</td>
            @endif
            @endfor
        </tr>
    </tbody>
</table>