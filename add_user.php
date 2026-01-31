<?php
session_start();
require './config.php';


// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    // Verify CSRF Token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        $database->errors[] = "Invalid CSRF Token";
        header('Location: ' . basename(__FILE__));
        exit;
    }

    $table = 'users_tbl';
    $redirect = './all_user.php';
    $fullName = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userRole = htmlspecialchars($_POST['userRole']);
    $status = htmlspecialchars($_POST['status']);
    $file = $_FILES['profileImage'];
    $uploadDir = '/uploads/profile_image/';

    $userValidate = [
        'user_fullname' => $fullName,
        'user_email' => $email,
        'user_password' => $password,
        'user_role' => $password,
        'user_status' => $userRole,
    ];

    if (strlen($password)  < 8) {
        $database->errors[] = "Password must be in 8 characters";
        return false;
    }

    if ($password !== $confirmPassword) {
        $database->errors[] = "Password and Confirm password must be matched";
        return false;
    }

    // Create hashPassword
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    // Image Upload
    if (isset($_FILES['profileImage'])) {
        $image = $database->file($table, $file, $uploadDir);
    } else {
        $image = null;
    }

    $params = [
        'user_fullname' => $fullName,
        'user_email' => $email,
        'user_password' => $hashPassword,
        'user_role' => $userRole,
        'user_status' => $status,
        'profile_image' => $image
    ];

    $database->store($table, $params, $redirect);
}

require './header.php';
?>

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add New User</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-xl-10 col-lg-11 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-user-plus mr-2"></i>Add New User
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if ($database->getErrors()) {
                            foreach ($database->getErrors() as $error) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        }


                        ?>
                        <div class="basic-form">
                            <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                enctype="multipart/form-data">
                                <input type="hidden" name="__csrf" value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">

                                <!-- Personal Information -->
                                <div class="mb-4 pb-3 border-bottom">
                                    <h5 class="text-primary mb-3">
                                        <i class="fa fa-user mr-2"></i>Personal Information
                                    </h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fullname" class="font-weight-bold">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="fullname"
                                                name="fullname" placeholder="Enter full name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email" class="font-weight-bold">
                                                Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control form-control-lg" id="email"
                                                name="email" placeholder="Enter email address">
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Security -->
                                <div class="mb-4 pb-3 border-bottom">
                                    <h5 class="text-primary mb-3">
                                        <i class="fa fa-lock mr-2"></i>Account Security
                                    </h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="password" class="font-weight-bold">
                                                Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control form-control-lg" id="password"
                                                name="password" placeholder="Enter password" minlength="8">
                                            <small class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> Minimum 8 characters required
                                            </small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="confirmPassword" class="font-weight-bold">
                                                Confirm Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control form-control-lg"
                                                id="confirmPassword" name="confirmPassword"
                                                placeholder="Confirm password">
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Settings -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fa fa-cog mr-2"></i>Account Settings
                                    </h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="userRole" class="font-weight-bold">
                                                User Role <span class="text-danger">*</span>
                                            </label>
                                            <select id="userRole" name="userRole"
                                                class="form-control form-control-lg default-select">
                                                <option value="" selected disabled>Select a role...</option>
                                                <option value="admin">Admin</option>
                                                <option value="manager">Manager</option>
                                                <option value="employee">Employee</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status" class="font-weight-bold">
                                                Account Status <span class="text-danger">*</span>
                                            </label>
                                            <select id="status" name="status"
                                                class="form-control form-control-lg default-select">
                                                <option value="" selected disabled>Select status...</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="confirmPassword" class="font-weight-bold">
                                                Profile Image <span class="text-danger">*</span>
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" name="profileImage" class="custom-file-input">
                                                <label class="custom-file-label">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-row mt-5">
                                    <div class="form-group col-md-12 text-center text-md-left">
                                        <button type="submit" name="issSubmitted" class="btn btn-primary btn-lg px-5">
                                            <i class="fa fa-check mr-2"></i>Add User
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg px-4 ml-2">
                                            <i class="fa fa-undo mr-2"></i>Reset
                                        </button>
                                        <a href="all_user.php" class="btn btn-outline-danger btn-lg px-4 ml-2">
                                            <i class="fa fa-times mr-2"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->

<?php require './footer.php'; ?>