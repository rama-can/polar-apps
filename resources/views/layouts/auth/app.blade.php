<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Auth' }} | Polar</title>
    <meta name="description" content="{{ $title ?? 'Auth' }} | Portal Layanan Laboratorium Berbasis Mobile">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Auth' }} | Polar">
    <meta property="og:description" content="{{ $title ?? 'Auth' }} | Portal Layanan Laboratorium Berbasis Mobile">
    <meta property="og:image" content="{{ url('assets/images/polar-350.png') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="polarlabsen.link">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $title ?? 'Auth' }} | Polar">
    <meta name="twitter:description" content="{{ $title ?? 'Auth' }} | Portal Layanan Laboratorium Berbasis Mobile">
    <meta name="twitter:image" content="{{ url('assets/images/polar-350.png') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('vendor/izitoast/css/iziToast.min.css') }}">
    <x-toast />

    @vite(['resources/css/auth.css'])
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
</head>

<body>
    <x-notif-session />
    <section class="login">
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">

                @include('layouts.auth.left-side')

                @yield('content')

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
        integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('vendor/izitoast/js/iziToast.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-password').click(function() {
                var passwordField = $('.show-password');
                var passwordFieldType = passwordField.attr('type');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).find('i').removeClass('text-dark').addClass('text-info');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).find('i').removeClass('text-info').addClass('text-dark');
                }
            });
        });
    </script>
</body>

</html>
