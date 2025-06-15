<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Today's Appointment</title>
    <style>
        /* CSS-nya tetap sama seperti sebelumnya */
        {{--
            Untuk file produksi, sebaiknya pindahkan CSS ini ke file public/css/home.css
            dan ganti dengan <link rel="stylesheet" href="{{ asset('css/home.css') }}">
        --}}
        body {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            grid-column-gap: 8px;
            grid-row-gap: 8px;
            height: 97.5vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
        }
        /* ... (seluruh style tetap seperti yang kamu buat) ... */
    </style>
</head>
<body>

    <div class="navigation-bar">
        <div class="row">
            <img src="{{ asset('images/mindmeet.png') }}" alt="MindMeet Logo" class="logo">
            <h2>MindMeet</h2>
        </div>

        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('schedule') }}">Schedule</a></li>
            <li><a href="{{ route('appointment') }}">Appointment</a></li>
            <li style="margin-top: 131%;"><a href="{{ route('logout') }}">Log Out</a></li>
        </ul>
    </div>

    <div class="header">
        <div class="calendar-header">
            <h2>September 2025</h2>

            <div class="calendar-nav">
                <button>Month</button>
                <button>Week</button>
                <button>Day</button>
                <button>List</button>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="calendar-grid">
            <!-- Day Headers -->
            @foreach (['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                <div class="day-header">{{ $day }}</div>
            @endforeach

            <!-- Calendar Days -->
            @php
                $days = [
                    ['day' => 29],
                    ['day' => 30],
                    ['day' => 1],
                    ['day' => 2],
                    ['day' => 3],
                    ['day' => 4],
                    ['day' => 5],
                    ['day' => 6],
                    ['day' => 7],
                    ['day' => 8, 'events' => ['9:00 (2 hours)', '13:00 (60 min)']],
                    ['day' => 9],
                    ['day' => 10],
                    ['day' => 11],
                    ['day' => 12],
                    ['day' => 13],
                    ['day' => 14],
                    ['day' => 15],
                    ['day' => 16],
                    ['day' => 17, 'events' => ['20:00 (60 min)']],
                    ['day' => 18, 'events' => ['13:00 (60 min)']],
                    ['day' => 19],
                    ['day' => 20],
                    ['day' => 21, 'events' => ['13:00 (60 min)']],
                    ['day' => 22],
                    ['day' => 23],
                    ['day' => 24, 'events' => ['13:00 (60 min)']],
                    ['day' => 25],
                    ['day' => 26],
                    ['day' => 27],
                    ['day' => 28],
                    ['day' => 29],
                    ['day' => 30],
                    ['day' => 31],
                ];
            @endphp

            @foreach ($days as $day)
                <div class="day-cell">
                    {{ $day['day'] }}
                    @if (!empty($day['events']))
                        @foreach ($day['events'] as $event)
                            <div class="event green">{{ $event }}</div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>    
    </div>

</body>
</html>
