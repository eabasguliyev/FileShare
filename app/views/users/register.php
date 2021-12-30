<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create an Account</h2>
                <p class="lead">Please fill out this form to register</p>
                <form action="<?= URLROOT ?>/users/register" method="POST">
                    <div class="form-group">
                        <label for="name">Name: <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" class="form-control form-control-lg <?= $data['errors']['name'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['name'] ?>" id="name">
                        <span class="invalid-feedback"><?= $data['errors']['name'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="username">Username: <sup class="text-danger">*</sup></label>
                        <input type="text" name="username" class="form-control form-control-lg <?= $data['errors']['username'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['username'] ?>" id="username">
                        <span class="invalid-feedback"><?= $data['errors']['username'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: <sup class="text-danger">*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg <?= $data['errors']['email'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['email'] ?>" id="email">
                        <span class="invalid-feedback"><?= $data['errors']['email'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup class="text-danger">*</sup></label>
                        <input type="password" name="password" class="form-control form-control-lg <?= $data['errors']['password'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['password'] ?>" id="password">
                        <span class="invalid-feedback"><?= $data['errors']['password'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password: <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg <?= $data['errors']['confirm_password'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['confirm_password'] ?>" id="confirm-password">
                        <span class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></span>
                    </div>
                    <div class="row mt-2">
                        <div class="col d-grid">
                            <input type="submit" value="Register" class="btn btn-success btn-block fs-6">
                        </div>
                        <div class="col d-grid">
                            <a href="<?= URLROOT ?>/users/login" class="btn btn-light btn-block fs-6">Have an account? Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once APPROOT . '/views/partials/footer.php'?>