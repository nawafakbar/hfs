{{-- resources/views/layouts/auth.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bgd Hydrofarm</title>
    <link rel="icon" href="{{ asset('user-assets/Images/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('user-assets/Images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('user-assets/css/loginregis.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row vh-100">
            {{-- Panel Gambar Kiri --}}
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center auth-image-panel">
                <div class="auth-image-arch">
                    <img src="{{ asset('user-assets/Images/image1.png') }}" alt="Hydroponic Greens">
                </div>
            </div>
            {{-- Panel Form Kanan (Konten Dinamis) --}}
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>