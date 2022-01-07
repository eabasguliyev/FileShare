<?php require_once APPROOT . '/views/partials/header.php'?>
<div class="container">
    <form action="<?= URLROOT ?>/files/info/<?= $data['id'] ?>" method="POST" class="d-flex flex-column h-50 justify-content-center align-items-center">
        <h1 class="fs-3 my-4"><?= $data['content']->title ?></h1>
        <p class="mt-2"><?= $data['content']->desc ?></p>
        <div class="w-50 mt-5">
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label mt-1"><?= $data['content']->password ?></label>
                <div class="col-sm-5">
                        <input type="password" name="password" class="form-control <?= $data['errors']['password'] ? 'is-invalid' : '' ?> mt-1"
                                value="<?= $data['password'] ?>" id="inputPassword">
                        <span class="invalid-feedback"><?= $data['errors']['password'] ?></span>
                    </div>
                <div class="col-sm-5">
                    <input type="submit" class="btn btn-primary mt-1" id="submit" value="Ok">
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?php require_once APPROOT . '/views/partials/footer.php'?>
