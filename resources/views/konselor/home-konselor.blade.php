<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Today's Appointment</title>

    <!-- CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/konselor.css') }}" />

    <!-- Inline CSS tambahan -->
    <style>
        h2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .appointment-card {
            background-color: #f7f9fc;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            padding: 20px 25px;
            margin-bottom: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
        }

        .appointment-card:hover {
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }

        .appointment-card p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
            font-weight: 500;
        }

        .appointment-card p:first-child {
            font-weight: 700;
            color: #222;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="image-section"></div>

    <div class="navigation-bar">
        <div class="row">
            <img src="{{ asset('images/mindmeet.png') }}" alt="MindMeet Logo" class="logo">
            <h2>MindMeet</h2>
        </div>

        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('booking.input') }}">Input Booking</a></li>
            <li><a href="{{ route('booking.edit') }}">Edit Booking</a></li>
            <li style="margin-top: 131%;"><a href="{{ route('logout') }}">Log Out</a></li>
        </ul>
    </div>

    <div class="header">
        <h2>Hello Agung,</h2>
        <h1>Good Morning!</h1>
    </div>

    <div class="main">
        <h2 style="margin-bottom: 20px; margin-top: 3px;">Today's Appointment</h2>

        {{-- Contoh data statis, bisa diganti dengan looping dari controller --}}
        @php
            $appointments = [
                ['id' => '1020223000000000', 'time' => '09.00 - 10.00'],
                ['id' => '1020223000000000', 'time' => '11.00 - 12.00'],
                ['id' => '1020223000000000', 'time' => '13.00 - 15.00'],
                ['id' => '11023000', 'time' => '15.00 - 16.00'],
            ];
        @endphp

        @foreach ($appointments as $appt)
            <div class="appointment-card">
                <p>ID: {{ $appt['id'] }}</p>
                <p>Time: {{ $appt['time'] }}</p>
            </div>
        @endforeach
    </div>    

    <div class="profil">
        <img src="{{ asset('images/profil.jpg') }}" alt="Profil Agung">
        <div class="profile-info">
            <h3>Agung Restu Haikal S., M.Psi., Psikolog</h3>
            <p>102022300000</p>
            <p>agungrestuhaikal@gmail.com</p>
        </div>
    </div>

</body>
</html>
