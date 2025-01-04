@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 50px;">
</div>

# {{ $subject }}!

Halo **{{ $name }}**,

{{ $message }}
{{-- Selamat! Akun Anda telah berhasil diaktifkan. --}}
Untuk mengatur password Anda, silakan klik tombol di bawah ini:

@component('mail::button', ['url' => $url])
Atur Password
@endcomponent

Terima kasih telah bergabung bersama kami, dan selamat menikmati layanan **Tel-U TIMETABLE**.

---

<div style="text-align: center; font-size: 12px; color: #777;">
    <small>Copyright by Tel-U TIMETABLE 2024</small>
</div>

@endcomponent
