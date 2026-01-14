<?php
require '../includes/header.php';
?>

<body class="bg-light">
    <!-- User Info Section -->
    <div class="bg-white shadow-sm border-bottom">
        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px; font-size: 15px; font-weight: bold;">
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">
                        </h6>
                        <small class="text-muted"></small>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold mb-1">My Tasks</h1>
                <p class="text-muted mb-0">Manage and organize your daily tasks</p>
            </div>
        </div>

        <!-- Add Task Form -->
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">Edit Task</h5>
                            <!-- Back Button -->
                            <a href="index.php" class="btn btn-outline-secondary">
                                ← Back
                            </a>
                        </div>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg"
                                        placeholder="Enter a task here..." name="task" value="">
                                </div>
                                <div class="col-md-3">
                                    <button name="submit" type="submit" class="btn btn-dark btn-lg w-100">Update
                                        Task</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php require '../includes/footer.php'; ?>