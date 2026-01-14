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

                <!-- Registration Card -->
                <div class="card shadow border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="card-title text-center mb-2 fw-bold fs-3">Create Account</h1>
                        <p class="text-center text-muted mb-4">Sign up to get started</p>
                        <form>
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    placeholder="John Doe">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="your.email@example.com">
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Create a strong password">
                                <div class="form-text">Must be at least 8 characters long</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                    placeholder="Re-enter your password">
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mb-3">
                                <button name="isSubmit" type="submit" class="btn btn-dark btn-lg">Create
                                    Account</button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">Already have an account?
                                <a href="index.php" class="text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php require 'includes/footer.php' ?>