<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2><?= $data['content']->card_title ?></h2>
                <p class="lead"><?= $data['content']->card_text ?></p>
                <form action="<?= URLROOT ?>/users/register" method="POST">
                    <div class="form-group">
                        <label for="name"><?= $data['content']->name ?>: <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" class="form-control form-control-lg <?= $data['errors']['name'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['name'] ?>" id="name">
                        <span class="invalid-feedback"><?= $data['errors']['name'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="username"><?= $data['content']->username ?>: <sup class="text-danger">*</sup></label>
                        <input type="text" name="username" class="form-control form-control-lg <?= $data['errors']['username'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['username'] ?>" id="username">
                        <span class="invalid-feedback"><?= $data['errors']['username'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email"><?= $data['content']->email ?>: <sup class="text-danger">*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg <?= $data['errors']['email'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['email'] ?>" id="email">
                        <span class="invalid-feedback"><?= $data['errors']['email'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password"><?= $data['content']->pass ?>: <sup class="text-danger">*</sup></label>
                        <input type="password" name="password" class="form-control form-control-lg <?= $data['errors']['password'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['password'] ?>" id="password">
                        <span class="invalid-feedback"><?= $data['errors']['password'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password"><?= $data['content']->pass_confirm ?>: <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg <?= $data['errors']['confirm_password'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['confirm_password'] ?>" id="confirm-password">
                        <span class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></span>
                    </div>
                    <div class="row mt-2">
                        <div class="col d-grid">
                            <input type="submit" value="<?= $data['content']->register ?>" class="btn btn-success btn-block fs-6">
                        </div>
                        <div class="col d-grid">
                            <a href="<?= URLROOT ?>/users/login" class="btn btn-light btn-block fs-6"><?= $data['content']->login ?></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?php require_once APPROOT . '/views/partials/footer.php'?>