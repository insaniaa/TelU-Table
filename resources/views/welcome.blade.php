<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tambahkan overflow-x: hidden ke body */
        body {
            overflow-x: hidden;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .rectangle-about-us {
            background: linear-gradient(180deg, rgba(255, 186, 186, 1.00) 0%, rgba(188, 16, 16, 1.00) 100%);
            border-radius: 97px 0 0 97px;
            width: 710px;
            height: 610px;
            position: absolute;
            right: 0;
            margin-top: 69px;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rectangle-about-us img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 97px 0 0 97px;
            object-fit: cover;
        }

        .content {
            z-index: 2;
            padding-left: 50px;
        }

        .content h1 {
            font-size: 2.5rem;
            color: #333;
        }

        .content p {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #555;
        }

        .footer {
            background-color: #f8f9fa;
            /* Warna sama dengan navbar */
            color: #000;
            /* Warna teks */
            padding: 30px 50px;
            box-shadow: inset 0 6px 6px -6px rgba(0, 0, 0, 0.1);
            /* Shadow di atas footer */
        }

        .footer a {
            color: #000;
            /* Sesuaikan dengan warna teks navbar */
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
            /* Opsional: tambahkan hover effect */
        }

        .footer-menu {
            padding-left: 110px;
            /* Menggeser lebih ke kiri */
        }

        .about-text {
            text-align: justify;
            /* Membuat teks rata kanan-kiri */
            line-height: 1.8;
            /* Memberikan jarak antarbaris */
            max-width: 90%;
            /* Lebar teks dibuat lebih panjang (90% dari parent container) */
            margin: auto;
            /* Memastikan teks tetap berada di tengah */
            padding-top: 20px;
        }

        .footer-logo-text {
            padding-left: 50px;
            /* Geser elemen lebih ke kanan */
            text-align: left;
            /* Opsional: sesuaikan perataan teks */
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="shadow navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            @guest
                <div class="d-flex">
                    <!-- Tombol Daftar -->
                    <a href="{{ route('register') }}" class="btn btn-secondary me-2">Daftar</a>
                    <!-- Tombol Masuk -->
                    <a href="{{ route('login') }}" class="btn btn-danger">Masuk</a>
                </div>
            @endguest
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero-section">
        <div class="rectangle-about-us">
            <img src="{{ asset('images/hero-image.png') }}" alt="Hero Image" style="width: 90%;">
        </div>
        <div class="content">
            <h1>Check Your Schedule</h1>
            <p>Mulai manajemen jadwal dan tugasmu untuk memaksimalkan waktu yang kamu miliki!</p>
            @guest
                <a href="{{ route('register') }}" class="mt-3 btn btn-danger" style="width: 100px;">Mulai</a>
            @endguest

            @auth
                @if (auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('admin.master_data.schedule.index') }}" class="mt-3 btn btn-primary">Kembali</a>
                @elseif (auth()->user()->hasRole('Lecturer'))
                    <a href="{{ route('lecturer.task.tasks_today') }}" class="mt-3 btn btn-primary">Kembali</a>
                @elseif (auth()->user()->hasRole('Student'))
                    <a href="{{ route('student.tasks_today') }}" class="mt-3 btn btn-primary">Kembali</a>
                @endif
            @endauth
        </div>
    </section>

    <!-- Summary Section -->
    <section id="summary" class="py-5 text-center summary">
        <h2 class="mb-5 text-center">Summary</h2>
        <img src="{{ asset('images/logo.png') }}" alt="Summary Image" class="mb-3" style="width: 350px;">
        <p class="mt-4">E-Table adalah solusi manajemen jadwal yang dirancang untuk mempermudah aktivitas harian Anda.
        </p>
    </section>

    <!-- About Section -->
    <section id="about-us"
        class="py-5 text-center about-section d-flex flex-column align-items-center justify-content-center">
        <h2 class="mb-5 text-center">About Us</h2>
        <img src="{{ asset('images/logo-team.png') }}" alt="Team Image" class="mb-3" style="width: 250px;">
        <div class="container">
            <p class="text-justify about-text">
                Kami adalah tim High Tech, terdiri dari tiga individu dari Program Studi D3 Rekayasa Perangkat Lunak dan
                Aplikasi (RPLA) kelas 4703 di Universitas Telkom. Bersama-sama, kami mengembangkan aplikasi "E-Table",
                yang dirancang untuk membantu pengguna mengatur waktu mereka dengan efisien. Dibuat dengan tekad untuk
                memberikan manfaat yang nyata, kami berkomitmen untuk terus meningkatkan aplikasi kami demi memenuhi
                kebutuhan pengguna dengan lebih baik.
                Terima kasih telah memberikan dukungan Anda kepada kami!
            </p>
        </div>
    </section>

    <!-- Meet Our Team Section -->
    <section class="py-5 text-center meet-our-team">
        <h2 class="mb-5 text-center">Meet Our Team</h2>
        <div class="text-center row">
            <div class="col-md-4">
                <h4>Salma Nur Azizah</h4>
                <p>47-03</p>
                <p>D3 RPLA</p>
                <p>Telkom University</p>
            </div>
            <div class="col-md-4">
                <h4>Evansius Rafael S.</h4>
                <p>47-03</p>
                <p>D3 RPLA</p>
                <p>Telkom University</p>
            </div>
            <div class="col-md-4">
                <h4>Tiyas Insania D.</h4>
                <p>47-03</p>
                <p>D3 RPLA</p>
                <p>Telkom University</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="row">
            <div class="col-md-6 footer-logo-text">
                <img src="{{ asset('images/logo.png') }}" alt="Footer Logo" style="height: 50px; margin-bottom: 15px;">
                <p>E-Table adalah aplikasi untuk membantu mengelola jadwal dengan lebih efisien dan terstruktur.</p>
            </div>
            <div class="col-md-3 footer-menu">
                <h5>Menu</h5>
                <ul class="list-unstyled">
                    <li><a href="#hero">Beranda</a></li> <!-- Mengarah ke Hero Section -->
                    <li><a href="#summary">Summary</a></li> <!-- Mengarah ke Summary Section -->
                    <li><a href="#about-us">About Us</a></li> <!-- Mengarah ke About Us Section -->
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Kontak</h5>
                <p>Email: etable@gmail.com</p>
                <p>Telepon: 0856-4142-1197</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
