<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bgd Hydrofarm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user-assets/style.css">
    <link rel="stylesheet" href="user-assets/loginregis.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center auth-image-panel">
                <div class="auth-image-arch">
                    <img src="Images/image1.png" alt="Hydroponic Greens">
                </div>
            </div>

            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="auth-form-container">
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Already have an account? <a href="login.html">Sign In</a></p>

                    <form action="#" method="POST" class="mt-4">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="youremail@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="••••••••">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-auth-primary">Create Account</button>
                        </div>
                    </form>

                    <div class="auth-separator">
                        <span>or</span>
                    </div>

                    <div class="d-grid">
                        <a href="#" class="btn btn-auth-secondary">
                            <img src="Images/google.png" alt="Google logo" class="me-2">
                            Sign Up with Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>