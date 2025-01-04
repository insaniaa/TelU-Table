<x-guest-layout>
    <!-- Navbar -->
    <nav class="shadow navbar navbar-expand-lg navbar-light bg-light fixed-top" style="border-radius: 8px;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 50px;">
            </a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center" style="padding-top: 80px;">
        <div class="p-4 shadow-lg text-center" style="background-color: #fff; border-radius: 20px; border: 3px solid rgba(0, 0, 0, 0.1); width: 60%;">
            <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" class="mb-4" style="height: 50px;">
            <h1 class="fw-bold text-success">Selamat!</h1>
            <p class="fs-5 text-dark">
                Anda telah berhasil melakukan registrasi. Mohon periksa email Anda untuk tautan verifikasi.
                Pastikan Anda menggunakan email yang aktif untuk melanjutkan proses.
            </p>
            <p class="text-muted">
                Jika Anda tidak menerima email dalam beberapa menit, pastikan untuk memeriksa folder spam atau hubungi tim dukungan kami.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Kembali ke Halaman Masuk</a>
        </div>
    </div>
</x-guest-layout>
