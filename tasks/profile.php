<?php
session_start();
require '../config/db.php';

if (isset($_SESSION['loggedIn']) == false) {
    header('Location: ../index.php?loginFirst=1');
    exit;
}


// Fetch User Data
if (isset($_SESSION['userId'])) {
    $id = $_SESSION['userId'];
    $sql = $conn->prepare('SELECT * FROM users_tbl WHERE id = :id');
    $sql->bindParam(':id', $id);
    $sql->execute();
    $user = $sql->fetch();
}

// Generate CSRF TOken
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex((random_bytes(32)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isSubmitted'])) {
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . basename(__FILE__) . '?csrfError=1');
        exit;
    }
    $oldName     = htmlspecialchars($user['user_fullname']);
    $oldEmail    = htmlspecialchars($user['user_email']);
    $oldPassword = $user['user_password'];

    $newName = null;
    $newEmail = null;
    $name = filter_var(trim($_POST['fullname']), FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $currentPassword = filter_var(trim($_POST['currentPassword']));
    $newPassword = filter_var(trim($_POST['newPassword']));
    $confirmPassword = filter_var(trim($_POST['confirmPassword']));


    // Validations
    if (empty($name) || empty($email) || empty($currentPassword)) {
        header('Location: ' . basename(__FILE__) . '?emptyFields=1');
        exit;
    } elseif (!password_verify($currentPassword, $oldPassword)) {
        header('Location: ' . basename(__FILE__) . '?currentPasswordError=1');
        exit;
    } elseif ($newPassword !== $confirmPassword) {
        header('Location: ' . basename(__FILE__) . '?confirmPasswordError=1');
        exit;
    }

    if (isset($name)) {
        $newName = $name;
    } else {
        $newName = $oldName;
    }

    if (isset($email)) {
        $newEmail = $email;
    } else {
        $newEmail = $oldEmail;
    }


    // Hash New password
    $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $id = $_SESSION['userId'];


    // Update Data in Database

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare('UPDATE users_tbl
                                             SET user_fullname  = :fname,
                                                 user_email     = :uemail,
                                                 user_password  = :upassword       
                                            WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fname', $newName);
        $stmt->bindParam(':uemail', $newEmail);
        $stmt->bindParam(':upassword', $hashPassword);
        $result = $stmt->execute();
        if ($result) {
            $conn->commit();
            header('Location: index.php?success=1');
            exit;
        }
    } catch (Exception $e) {
        $conn->rollBack();
        throw new Exception('Profile Update Error ' . $e->getMessage());
    }
}






require '../includes/header.php'; ?>

<body class="bg-light">
    <!-- User Info Section -->
    <div class="bg-white shadow-sm border-bottom">
        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px; font-size: 15px; font-weight: bold;">
                        <?= htmlspecialchars($_SESSION['userName']) ?>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">
                            <?= htmlspecialchars($_SESSION['userName']) ?>
                        </h6>
                        <small class="text-muted"><?= htmlspecialchars($_SESSION['userEmail']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <h1 class="p-2">Update Profile</h1>
        <form method="post" action="<?= htmlspecialchars(basename(__FILE__)) ?>" class="card p-5">
            <input type="hidden" name="__csrf" value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">

            <!-- Full Name -->
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe"
                    value="<?= htmlspecialchars($user['user_fullname']) ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="your.email@example.com"
                    value="<?= htmlspecialchars($user['user_email']) ?>">
            </div>

            <!-- Current Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="password" name="currentPassword"
                    placeholder="Create a strong password" autocomplete="off">
                <div class="form-text">Must be at least 8 characters long</div>
            </div>

            <!-- New Password -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="newPassword"
                    placeholder="Create a new strong password">
                <div class="form-text">Must be at least 8 characters long</div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="confirmPassword" class="form-label">New Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                    placeholder="Re-enter your new password">
            </div>
            <!-- Update Button -->
            <div class=" mb-3">
                <button type="submit" name="isSubmitted" class="btn btn-dark btn-lg">
                    Update Profile
                </button>
                <a href="index.php" type="submit" class="btn btn-outline-dark ">
                    ← Back
                </a>
            </div>

        </form>
    </div>

    <?php require '../includes/footer.php'; ?>
    <?php $conn = null; ?>