<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Jadwal Mahasiswa</title>
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
            <li><a href="{{ route('schedule') }}">Schedule</a></li>
            <li><a href="{{ route('appointment') }}">Appointment</a></li>
            <li style="margin-top: 131%;"><a href="{{ route('logout') }}">Log Out</a></li>
        </ul>
    </div>

    <div class="header">
        <h2>Hello {{ $user->name ?? 'Agung' }},</h2>
        <h1>Good Morning!</h1>
    </div>

    <div class="main">
        <form action="{{ route('booking.submit') }}" method="POST">
            @csrf

            <div class="form-row1">
                <div class="form-input">
                    <label for="mahasiswa-nim">NIM</label>
                    <input type="text" id="mahasiswa-nim" name="nim" placeholder="Your NIM" value="{{ old('nim', $user->student_id ?? '') }}">
                </div>
                <div class="form-input">
                    <label for="mahasiswa-name">Name</label>
                    <input type="text" id="mahasiswa-name" name="name" placeholder="Your Name" value="{{ old('name', $user->name ?? '') }}">
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
        <img src="{{ asset('images/profil.jpg') }}" alt="Profile Picture">
        <div class="profile-info">
            <h3>{{ $user->name ?? 'Frixtho Alex Credorius Latumahina' }}</h3>
            <p>{{ $user->student_id ?? '102022300000' }}</p>
            <p>{{ $user->major ?? 'S1 Sistem Informasi' }}</p>
            <p>{{ $user->email ?? 'frixthoalex@student.telkomuniversity.ac.id' }}</p>
        </div>
    </div>
</body>
</html>
