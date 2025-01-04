<x-guest-layout>
    <!-- Navbar -->
    <nav class="shadow navbar navbar-expand-lg navbar-light bg-light fixed-top" style="border-radius: 8px;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 50px;">
            </a>
            <div class="d-flex">
                <!-- Tombol Daftar -->
                <a href="{{ route('register') }}" class="btn btn-secondary me-2">Daftar</a>
                <!-- Tombol Masuk -->
                <a href="{{ route('login') }}" class="btn btn-danger">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center" style="padding-top: 80px;">
        <div class="p-4 shadow-lg row"
            style="background-color: #fff; border-radius: 20px; border: 3px solid rgba(0, 0, 0, 0.1); width: 68%;">
            <!-- Bagian Kiri: Gambar dan Teks -->
            <div class="text-center col-md-6 d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('images/labor_day_calendar.png') }}" alt="Register" class="mb-3 img-fluid"
                    style="max-width: 80%;">
                <h1 class="fw-bold text-dark">SELAMAT DATANG</h1>
                <h2 class="fw-bold text-danger" style="font-size: 2.5rem;">T-TABLERS</h2>
            </div>

            <!-- Bagian Kanan: Form Register -->
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')"/>
                        <x-text-input id="password" class="form-control" type="password" name="password"
                            :value="old('password')"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Password Confirmation')"/>
                        <x-text-input id="password_confirmation" class="form-control" type="password_confirmation" name="password_confirmation" type="password"
                            :value="old('password_confirmation')"/>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                    </div>

                    <!-- Button Register -->
                    <div class="mt-3 d-grid">
                        <button type="submit" class="btn btn-danger">{{ __('Atur Password') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
