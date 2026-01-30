<?php require './header.php'; ?>

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
                        <div class="basic-form">
                            <form method="POST" action="">

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
                                                name="fullname" placeholder="Enter full name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email" class="font-weight-bold">
                                                Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control form-control-lg" id="email"
                                                name="email" placeholder="Enter email address" required>
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
                                                name="password" placeholder="Enter password" required minlength="8">
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
                                                placeholder="Confirm password" required>
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
                                                class="form-control form-control-lg default-select" required>
                                                <option value="">Select a role...</option>
                                                <option value="admin">Administrator</option>
                                                <option value="manager">Manager</option>
                                                <option value="editor">Editor</option>
                                                <option value="user">Standard User</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status" class="font-weight-bold">
                                                Account Status <span class="text-danger">*</span>
                                            </label>
                                            <select id="status" name="status"
                                                class="form-control form-control-lg default-select" required>
                                                <option value="">Select status...</option>
                                                <option value="active" selected>Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="pending">Pending Approval</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-row mt-5">
                                    <div class="form-group col-md-12 text-center text-md-left">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fa fa-check mr-2"></i>Add User
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg px-4 ml-2">
                                            <i class="fa fa-undo mr-2"></i>Reset
                                        </button>
                                        <a href="users.php" class="btn btn-outline-danger btn-lg px-4 ml-2">
                                            <i class="fa fa-times mr-2"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <h5 class="alert-heading">
                                <i class="fa fa-info-circle mr-2"></i>Important Information
                            </h5>
                            <ul class="mb-0 pl-3">
                                <li>All fields marked with <span class="text-danger">*</span> are required</li>
                                <li>Password must be at least 8 characters long</li>
                                <li>A welcome email will be sent to the user's email address</li>
                                <li>Users can change their password after first login</li>
                            </ul>
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