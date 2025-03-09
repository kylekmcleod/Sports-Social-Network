<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/globals.css">
    <link rel="stylesheet" href="../assets/css/authentication/auth.css">
</head>
<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row align-items-center w-100">
    
            <!-- titles -->
            <div class="col-12 col-md-6 mb-4 mb-md-0 px-4 d-flex justify-content-center justify-content-md-start">
                <div>
                    <h1 class="display-4 fw-bold text-center text-md-start">LOG IN</h1>
                    <p class="lead text-center text-md-start">Sign in to your account and get back to the ultimate sports platform.</p>

                    <!-- Logos for NBA, NHL, NFL, MLB -->
                    <div class="d-flex justify-content-center justify-content-md-start mb-4">
                        <img src="https://cdn.freebiesupply.com/images/large/2x/nba-logo-transparent.png" alt="NBA Logo" class="logo mx-3" width="25">
                        <img src="https://upload.wikimedia.org/wikipedia/en/3/3a/05_NHL_Shield.svg" alt="NHL Logo" class="logo mx-3" width="40">
                        <img src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a2/National_Football_League_logo.svg/1200px-National_Football_League_logo.svg.png" alt="NFL Logo" class="logo mx-2" width="40">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/800px-Major_League_Baseball_logo.svg.png" alt="MLB Logo" class="logo mx-3" style="max-width: 70px; height: auto; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- Login Form Section -->
            <div class="col-12 col-md-6">
                <form id="loginForm" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" required>
                        <div id="emailError" class="text-danger" style="display:none;">Please enter a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                        <div id="passwordError" class="text-danger" style="display:none;">Password is required.</div>
                    </div>
                    <button type="submit" class="btn w-100 register-btn">LOG IN</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register.html">Sign up</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/loginValidation.js"></script>
</body>
</html>
