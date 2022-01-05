<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
    <link rel="shortcut icon" href="<?= URLROOT ?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= URLROOT ?>/favicon.ico" type="image/x-icon">
    <title>Admin</title>
</head>
<body>
<div class="container">
    <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card card-body bg-light mt-5">
                    <?php flash('admin_login_fail'); ?>
                    <h2>Login </h2>
                    <p class="lead">Access to admin panel</p>
                    <form action="<?= URLROOT ?>/admins/login" method="POST">
                        <div class="form-group">
                            <label for="username">Username: <sup class="text-danger">*</sup></label>
                            <input type="text" name="username" class="form-control form-control-lg <?= $data['errors']['username'] ? 'is-invalid' : '' ?> mt-1"
                                    value="<?= $data['username'] ?>" id="username">
                            <span class="invalid-feedback"><?= $data['errors']['username'] ?></span>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>