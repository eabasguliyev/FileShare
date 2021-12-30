<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <?php flash('register_success'); ?>
                <?php 
                    if(flash('logout_success')){
                        session_destroy();
                    }?>
                <h2>Login to Account</h2>
                <p class="lead">Access and manage your files</p>
                <form action="<?= URLROOT ?>/users/login" method="POST">
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
                    <div class="row mt-2">
                        <div class="col d-grid">
                            <input type="submit" value="Login" class="btn btn-success btn-block fs-5">
                        </div>
                        <div class="col d-grid">
                            <a href="<?= URLROOT ?>/users/register" class="btn btn-light btn-block fs-5">Don't have an account yet? Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once APPROOT . '/views/partials/footer.php'?>