<?php
require 'includes/header.php';
?>

<body class="bg-light">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1 text-dark">To-Do List</h2>
                    <p class="text-muted mb-0">Manage your tasks efficiently</p>
                </div>

                <!-- Login Card -->
                <div class="card shadow border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="card-title text-center mb-2 fw-bold">Welcome Back</h3>
                        <p class="text-center text-muted mb-4">Please login to your account</p>

                        <form>
                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="your.email@example.com">
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter your password">
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <a href="#" class="text-decoration-none">Forgot password?</a>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-3">
                                <button name="isSubmitted" type="submit" class="btn btn-dark btn-lg">
                                    Login
                                </button>
                            </div>
                        </form>

                        <!-- Registration Link -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">Don't have an account?
                                <a href="registration.php" class="text-decoration-none">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php require 'includes/footer.php' ?>