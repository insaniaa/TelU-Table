<footer class="py-1 mt-auto footer bg-light" style="box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);">
    <style>
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0; /* Padding atas-bawah */
            border-top: 1px solid #eaeaea;
        }
        .footer-logo-text img {
            height: 50px;
            margin-bottom: 10px;
            padding-top: 10px;
        }
        .footer h5 {
            font-size: 1rem; /* Ukuran heading tetap */
            margin-bottom: 10px; /* Jarak bawah heading */
            font-weight: bold;
            color: black;
            padding-top: 10px;
        }
        .footer p, .footer ul li {
            font-size: 0.9rem; /* Ukuran teks tetap */
            color: black;
            margin: 0;
        }
        .footer ul {
            list-style: none; /* Hilangkan bullet */
            padding: 0;
        }
        .footer ul li {
            margin-bottom: 5px; /* Jarak antar item menu */
        }
        .footer-container {
            max-width: 1200px;
            margin: auto;
            padding: 0 15px;
        }
        .footer-menu {
            padding-left: 60px; /* Geser menu lebih ke kanan */
        }
        @media (min-width: 768px) {
            .footer-container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
        }
    </style>

    <div class="container footer-container">
        <!-- Logo dan Deskripsi -->
        <div class="col-md-6 footer-logo-text">
            <img src="{{ asset('images/logo.png') }}" alt="Footer Logo">
            <p>E-Table adalah aplikasi untuk membantu mengelola jadwal dengan lebih efisien dan terstruktur.</p>
        </div>
        <!-- Menu -->
        <div class="col-md-3 footer-menu">
            <h5>Menu</h5>
            <ul>
                <li>Kelola Kelas</li>
                <li>Kelola Jadwal</li>
                <li>Kelola Matkul</li>
                <li>Kelola User</li>
            </ul>
        </div>
        <!-- Kontak -->
        <div class="col-md-3">
            <h5>Kontak</h5>
            <p>Email: etable@gmail.com</p>
            <p>Telepon: 0856-4142-1197</p>
        </div>
    </div>
</footer>
