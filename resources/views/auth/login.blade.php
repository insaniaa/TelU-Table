<x-guest-layout>
    <!-- Navbar -->
    <nav class="shadow navbar navbar-expand-lg navbar-light bg-light fixed-top" style="border-radius: 8px;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 50px;">
            </a>
            <div class="d-flex">
                <!-- Tombol Daftar -->
                <a href="{{ route('register') }}" class="btn btn-danger me-2">Daftar</a>
                <!-- Tombol Masuk -->
                @guest
                <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                @endguest
                @auth
                <a href="{{ auth()->user()->redirectTo() }}" class="btn btn-secondary">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center" style="padding-top: 80px;">
        <div class="p-4 shadow-lg row" style="background-color: #fff; border-radius: 20px; border: 3px solid rgba(0, 0, 0, 0.1); width: 68%;">
            <!-- Bagian Kiri: Gambar dan Teks -->
            <div class="text-center col-md-6 d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('images/labor_day_calendar.png') }}" alt="Labor Day Calendar" class="mb-3 img-fluid" style="max-width: 80%;">
                <h1 class="fw-bold text-dark">WELCOME BACK</h1>
                <h2 class="fw-bold text-danger" style="font-size: 2.5rem;">TELUTIZEN</h2>
            </div>

            <!-- Bagian Kanan: Form Login -->
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                    </div>

                    <!-- Forgot Password -->
                    <div class="mb-3 text-end">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none text-danger" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">{{ __('Log in') }}</button>
                    </div>
                </form>

                <!-- Buat Akun -->
                <div class="mt-3 text-center">
                    <span>belum punya akun? <a href="{{ route('register') }}" class="text-danger">buat akun</a></span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
