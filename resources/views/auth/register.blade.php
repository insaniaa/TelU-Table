@push('js')
    <script>

        $(document).ready(function () {
            @if (session('role'))
                @if ((session('role')) == 'lecturer')
                $('#nip-container').show()
                $('#nim-container').hide()
                @else
                $('#nim-container').show()
                $('#nip-container').hide()
                @endif
            @endif
        });
        function onChangeRole(e) {
            const role = e.val();
            if(role == "lecturer") {
                $('#nip-container').show()
                $('#nim-container').hide()
            }

            if(role == "student") {
                $('#nim-container').show()
                $('#nip-container').hide()
            }
        }
    </script>
@endpush

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
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Daftar Sebagai -->
                    <div class="mb-3">
                        <x-input-label for="role" :value="__('Daftar Sebagai')" />
                        <select id="role" class="form-control" type="text" name="role" onchange="onChangeRole($(this))"
                         autofocus >
                            <option disabled selected value="">Pilih Role Anda Terlebih Dahulu</option>
                            <option value="lecturer" @selected(old('role') == 'lecturer')>Dosen</option>
                            <option value="student" @selected(old('role') == 'student')>Mahasiswa</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-danger" />
                    </div>

                    <!-- Nim -->
                    <div id="nim-container" style="display: none;">
                        <div class="mb-3">
                            <x-input-label for="nim" :value="__('nim')"/>
                            <x-text-input id="nim" class="form-control" type="nim" name="nim"
                                :value="old('nim')"/>
                            <x-input-error :messages="$errors->get('nim')" class="mt-2 text-danger" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" :value="__('Email')"/>
                            <x-text-input id="email" class="form-control" type="email" name="email_student"
                                :value="old('email')"/>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>
                    </div>

                    <!-- Nip -->
                    <div id="nip-container" style="display: none;">
                        <div class="mb-3">
                            <x-input-label for="nip" :value="__('nip')" />
                            <x-text-input id="nip" class="form-control" type="nip" name="nip"
                                :value="old('nip')"/>
                            <x-input-error :messages="$errors->get('nip')" class="mt-2 text-danger" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" :value="__('Email')"/>
                            <x-text-input id="email" class="form-control" type="email" name="email_lecturer"
                                :value="old('email')"/>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>
                    </div>

                    <!-- Button Register -->
                    <div class="mt-3 d-grid">
                        <button type="submit" class="btn btn-danger">{{ __('Register') }}</button>
                    </div>
                </form>

                <!-- Already Registered Link -->
                <div class="mt-3 text-center">
                    <span>sudah punya akun? <a href="{{ route('login') }}" class="text-danger">masuk</a></span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
