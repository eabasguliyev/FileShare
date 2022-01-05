<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a href="<?= URLROOT ?>/admins/index" class="navbar-brand fs-1">
            Admin Panel
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navmenu">
            <ul class="navbar-nav ms-auto fs-5">
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <?= ucwords($_SESSION['admin_username']) ?>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a href="<?= URLROOT ?>/admins/logout" class="nav-link text-white">
                        Logout
                    </a>
                </li>
                <li class="nav-item">
                    <select class="form-select bg-dark select-lang text-white mt-1 cursor-pointer">
                        <option value="1" selected>En</option>
                        <option value="2">Az</option>
                    </select>
                </li>
            </ul>
        </div>
    </div>
</nav>