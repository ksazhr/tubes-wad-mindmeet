<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Booking</title>
    <link rel="stylesheet" href="{{ asset('css/konselor.css') }}">
</head>
<body>
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
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf

            <div class="form-row1">
                <div class="form-input">
                    <label for="konselor-id">Konselor ID</label>
                    <input type="text" id="konselor-id" name="konselor_id" placeholder="Konselor ID" value="{{ old('konselor_id') }}">
                </div>
                <div class="form-input">
                    <label for="konselor-name">Name</label>
                    <input type="text" id="konselor-name" name="konselor_name" placeholder="Konselor Name" value="{{ old('konselor_name') }}">
                </div>
            </div>

            <div class="form-row2">
                <div class="form-input">
                    <label for="start-date">Start Date</label>
                    <input type="date" id="start-date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="form-input">
                    <label for="end-date">End Date</label>
                    <input type="date" id="end-date" name="end_date" value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="form-row3">
                <div class="form-input">
                    <label for="start-time">Start Time</label>
                    <input type="time" id="start-time" name="start_time" value="{{ old('start_time') }}">
                </div>
                <div class="form-input">
                    <label for="end-time">End Time</label>
                    <input type="time" id="end-time" name="end_time" value="{{ old('end_time') }}">
                </div>
            </div>

            <button type="submit" class="submit-button">Submit</button>
        </form>
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
