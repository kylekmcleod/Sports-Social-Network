<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                    <h1 class="display-4 fw-bold text-center text-md-start">GET STARTED</h1>
                    <p class="lead text-center text-md-start">Create your account to start using the ultimate sport-oriented social media platform.</p>

                    <!-- Logos for NBA, NHL, NFL, MLB -->
                    <div class="d-flex justify-content-center justify-content-md-start mb-4">
                        <img src="https://cdn.freebiesupply.com/images/large/2x/nba-logo-transparent.png" alt="NBA Logo" class="logo mx-3" width="25">
                        <img src="https://upload.wikimedia.org/wikipedia/en/3/3a/05_NHL_Shield.svg" alt="NHL Logo" class="logo mx-3" width="40">
                        <img src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a2/National_Football_League_logo.svg/1200px-National_Football_League_logo.svg.png" alt="NFL Logo" class="logo mx-2" width="40">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/800px-Major_League_Baseball_logo.svg.png" alt="MLB Logo" class="logo mx-3" style="max-width: 70px; height: auto; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- form -->
            <div class="col-12 col-md-6">
                <div class="card-body py-5 px-4">
                    <form action="../src/controllers/RegisterAuthController.php" method="POST" id="registerForm" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <div class="profile-upload-container text-center mb-3">
                                <!-- Profile Image Preview -->
                                <img id="profile-preview" 
                                     src="https://t3.ftcdn.net/jpg/09/64/89/18/360_F_964891898_SuTIP6H2AVZkBuUG2cIpP9nvdixORKpM.jpg" 
                                     alt="Profile Image"
                                     class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ccc;"/>
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" id="profile-upload" name="profileImage" accept="image/*" style="display: none;" />

                            <div class="col-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                                <div id="firstNameError" class="text-danger" style="display:none;">First name is required.</div>
                            </div>
                            <div class="col-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                                <div id="lastNameError" class="text-danger" style="display:none;">Last name is required.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div id="usernameError" class="text-danger" style="display:none;">Username is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div id="emailError" class="text-danger" style="display:none;">Please enter a valid email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div id="passwordError" class="text-danger" style="display:none;">Password is required.</div>
                        </div>
                        
                        <!-- Upload Profile Button -->
                        <button type="button" class="btn btn-outline-grey w-100 mb-2" id="uploadProfileBtn">Upload Profile Image</button>
                        
                        <button type="submit" name="register" class="btn w-100 register-btn">SIGN UP</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="login.php">Log in</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/registerValidation.js"></script>
    <script src="../assets/js/authProfileUpdate.js"></script>
</body>
</html>
