<?php
    $domain = ucfirst(strtolower(SITENAME)) . '.com';
?>
<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="fs-3 my-4"><?= $data['content']->title ?></h1>
        <div class="container w-75">
           <div class="d-flex flex-column justify-content-start">
               <p class="fs-6"><?= $data['content']->p1 ?></p>
                <div class="card card-body my-3 p-4">
                    <ol class="">
                        <li class="mb-2"><?= $data['content']->ul->li1 ?></li>
                        <li class="mb-2"><?= $data['content']->ul->li2 ?></li>
                        <li class="mb-2"><?= $data['content']->ul->li3 ?></li>
                        <li class="mb-2"><?= $data['content']->ul->li4 ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?php require_once APPROOT . '/views/partials/footer.php'?>