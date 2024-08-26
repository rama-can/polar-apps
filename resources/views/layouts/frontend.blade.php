@props(['title', 'notIndex' => false])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Home' }} | Polar</title>
    <meta name="description" content="{{ $title ?? 'Home' }} | Portal Layanan Laboratorium Berbasis Mobile">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Home' }} | Polar">
    <meta property="og:description" content="{{ $title ?? 'Home' }} | Portal Layanan Laboratorium Berbasis Mobile">
    <meta property="og:image" content="{{ url('assets/images/polar-350.png') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="polarlabsen.link">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $title ?? 'Home' }} | Polar">
    <meta name="twitter:description" content="{{ $title ?? 'Home' }} | Portal Layanan Laboratorium Berbasis Mobile">
    <meta name="twitter:image" content="{{ url('assets/images/polar-350.png') }}">
    @if ($notIndex ?? false)
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/izitoast/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-override.min.css') }}">
    <link rel="stylesheet" id="theme-color" href="{{ asset('assets/css/dark.min.css') }}">
    <link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" crossorigin="anonymous" />

    @stack('styles')
    {{-- toast --}}
    <x-toast />
    <style>
        html, body {
            background-color: white;
            margin: 0; /* Menghapus margin default dari body */
            padding: 0; /* Menghapus padding default dari body */
        }

        @media (max-width: 768px) {
            .back-button {
                width: 50px;
                height: 50px;
                padding: 0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                position: fixed;
                bottom: 20px;
                left: 20px;
                z-index: 1000;
            }

            .back-button i {
                font-size: 24px;
            }
        }

        .popover-profile {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }

        .popover-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-menu {
            min-width: 150px;
            border: 1px solid #f8f9fa;
        }
        .dropdown-item {
            display: flex;
            align-items: center; /* Vertikal center */
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s ease, padding 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
            text-decoration: none;
            padding: 10px 15px; /* Adjust padding on hover */
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

</head>
<body class="d-flex flex-column min-vh-100">
    <div id="app" class="flex-grow-1">
        <x-notif-session />
        <main id="content" role="main" class="container mb-4">
            {{ $slot }}
        </main>
    </div>
    <footer class="bg-light text-center text-lg-start mt-auto py-3">
        <div class="container">
            {{-- <p class="mb-0">© 2024 POLAR.</p> --}}
        </div>
    </footer>
    <div class="popover-profile dropdown">
        @guest
            <img src="{{ asset('assets/images/auth.png') }}" id="loginIcon" data-bs-toggle="dropdown" aria-expanded="false" alt="Login Icon" class="img-user border border-secondary rounded-circle bg-white">
            <ul class="dropdown-menu dropdown-menu-end border border-primary" aria-labelledby="loginIcon">
                <li>
                    <a class="dropdown-item" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-2 mb-1"></i> Login
                    </a>
                </li>
            </ul>
        @else
            <img src="{{ Auth::user()->image }}"
                id="profileImage"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                alt="User Profile"
                class="img-user rounded-circle border border-secondary bg-white"
                style="width: 50px; height: 50px; object-fit: cover;">

            <ul class="dropdown-menu dropdown-menu-end border border-primary" aria-labelledby="profileImage">
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user me-2 ms-2"></i> Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('product-category.index') }}">
                        <i class="fa-solid fa-list me-2 ms-2 mb-1"></i> Kategori
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('products.index') }}">
                        <i class="fas fa-search me-2 ms-2"></i> Pencarian
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="">
                        <i class="fas fa-sync me-2 ms-2"></i> Refresh
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input type="hidden" name="logout" value="logout">
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2 ms-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        @endguest
    </div>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" crossorigin="anonymous"></script>
    {{-- icon --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" ></script>

    <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>

    <!-- js for this page only -->
    @stack('js')
</body>
</html>
