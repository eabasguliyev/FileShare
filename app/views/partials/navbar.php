<?php
    $content = FileHelper::getPageContentByLang('navbar', Core::$lang);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger fixed-top">
    <div class="container">
        <a href="<?= URLROOT ?>" class="navbar-brand fs-1">
            <i class="bi bi-cloud-arrow-down-fill me-2"></i><?= SITENAME ?>
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navmenu">
            <ul class="navbar-nav ms-auto fs-5">
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/files/upload" class="nav-link text-white">
                        <i class="bi bi-plus-square-fill m-1"></i><?= $content->menu1 ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/files/all" class="nav-link text-white">
                    <?= $content->menu2 ?>
                    </a>
                </li>
                <?php if(isLoggedIn()): ?>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/users/storage/<?= $_SESSION['user_storage_id']?>" class="nav-link text-white">
                        <?= $_SESSION['user_name'] ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/users/logout" class="nav-link text-white">
                    <?= $content->menu3 ?>
                    </a>
                </li>
                <?php else: ?> 
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/users/login" class="nav-link text-white">
                        <?= $content->menu4 ?>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="bg-light-red rounded px-2">
                        <a href="<?= URLROOT ?>/users/register" class="nav-link text-white">
                        <?= $content->menu5 ?>
                        </a>
                    </div>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <select id="langs" class="form-select bg-danger select-lang text-white mt-1 cursor-pointer">
                        <?php foreach(Core::$langs as $value ): ?>
                            <option <?= Core::$lang == $value ? 'selected' : '' ?>><?= ucfirst($value) ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
            </ul>
        </div>
    </div>
</nav>