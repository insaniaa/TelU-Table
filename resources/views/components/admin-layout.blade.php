<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @stack('css')
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            /* Pastikan navbar berada di atas elemen lain */
        }

        .navbar-nav .nav-link {
            color: #585858;
            /* Default text color */
            font-weight: 500;
            /* Slightly bold text */
            padding-bottom: 3px;
            /* Closer underline */
            border-bottom: 2px solid transparent;
            /* Default underline */
            transition: all 0.3s ease;
            /* Smooth transition */
            font-size: 0.9rem;
            /* Adjust font size smaller */
        }

        .navbar-nav .nav-link:hover {
            color: #bc1010;
            /* Hover text color */
        }

        .navbar-nav .active-link {
            color: #bc1010 !important;
            /* Active text color */
            border-bottom: 2px solid #bc1010;
            /* Active underline closer to text */
            width: auto;
            /* Fit underline to text width */
        }

        .navbar-nav .nav-item {
            margin-right: 30px;
            /* Increase spacing between items */
        }

        .container-fluid {
            max-width: 1440px;
            /* Extend width */
            padding-left: 1rem;
            /* Add spacing to the left */
            padding-right: 1rem;
            /* Add spacing to the right */
        }

        body {
            padding-top: 70px;
            /* Adjust for fixed navbar height */
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans ">
    <x-navbar></x-navbar>
    <div class="p-4 mx-4 mt-4 mb-5 text-white rounded-4 d-flex align-items-center justify-content-between"
        style="background-color: #bc1010; height: 150px; border-radius: 20px; position: relative;">
        <div class="ms-5 ps-5">
            <h1 class="mb-2 fw-bold" style="font-size: 28px;">
                @php
                    $currentHour = now('Asia/Jakarta')->hour;
                    $greeting = '';

                    if ($currentHour >= 5 && $currentHour < 10) {
                        $greeting = 'Selamat Pagi';
                    } elseif ($currentHour >= 10 && $currentHour < 15) {
                        $greeting = 'Selamat Siang';
                    } elseif ($currentHour >= 15 && $currentHour < 18) {
                        $greeting = 'Selamat Sore';
                    } else {
                        $greeting = 'Selamat Malam';
                    }
                @endphp
                {{ $greeting }} {{ auth()->user()->name }}!
            </h1>


            @if (auth()->user()->hasRole('Super Admin'))
                <p class="mb-0 fw-medium" style="font-size: 20px;">Kelola jadwalnya yuk</p>
            @elseif (auth()->user()->hasRole('Lecturer'))
                <p class="mb-0 fw-medium" style="font-size: 20px;">Kelola tugasnya yuk</p>
            @elseif (auth()->user()->hasRole('Student'))
                <p class="mb-0 fw-medium" style="font-size: 20px;">Semangat tubesnya yuk bisa yuk</p>
            @endif
        </div>
        <img src="{{ asset('images/buku.png') }}" alt="Gambar Buku"
            style="width: 250px; height: auto; position: absolute; top: 50%; left: calc(100% - 350px); transform: translateY(-50%);">
    </div>
    <main class="m-4 rounded" style="height: 100vh;background-color: #FFE0E0;overflow-y: scroll">
        {{ $slot }}
    </main>
    <x-footer></x-footer>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- SweatAlert JS -->
    @if (session('success'))
        <script>
            const ToastSuccess = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            ToastSuccess.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            })
        </script>
    @endif
    @if (session('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            })
        </script>
    @endif
    @stack('js')
</body>

</html>
