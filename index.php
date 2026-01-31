<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require './config.php';

// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    //Verify csrf token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        $_SESSION['message'] = 'Invalid CSRF token';
        header("Location: " . basename(__FILE__));
        exit;
    }

    $table = 'users_tbl';
    $email = htmlspecialchars($_POST['email']);
    $password  = $_POST['password'];
    $redirect = './dashboard.php';

    $database->attempt($table, $email, $password, $redirect);
}

?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Task Management System</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon.ico">
    <link href="./css/style.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-7">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <?php
                                if ($database->getErrors()) {
                                    foreach ($database->getErrors() as $error) {
                                        echo "<div class='alert alert-danger'>$error</div>";
                                    }
                                }


                                ?>
                                <div class="auth-form">
                                    <h1 class="text-center mb-4 text-white">Task Management System</h1>
                                    <h4 class="text-center mb-4 text-white">Sign in your account</h4>
                                    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                                        <input type="hidden" name="__csrf"
                                            value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Email</strong></label>
                                            <input type="email" class="form-control" name="email" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="issSubmitted"
                                                class="btn bg-white mt-5 text-primary btn-block">Sign
                                                Me
                                                In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/deznav-init.js"></script>

</body>

</html>