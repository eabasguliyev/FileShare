<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 my-4"><?= $data['content']->title ?></h1>
            <form method="POST" class="w-100">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="<?= $data['content']->search_input ?>" value="<?= isset($data['search']) ? $data['search'] : ''?>">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><?= $data['content']->search_btn ?></button>
                </div>
            </form>
            <p id="result-count" class="mt-4"><?= $data['content']->search_result ?>: <?= $data['total_count'] ?></p>
            <table class="table table-hover mt-2">
                <thead>
                    <tr>
                        <th><?= $data['content']->table->col0 ?></th>
                        <th><?= $data['content']->table->col1 ?></th>
                        <th><?= $data['content']->table->col2 ?></th>
                        <th><?= $data['content']->table->col3 ?></th>
                        <th><?= $data['content']->table->col4 ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['files'] as $file): ?>
                        <tr>
                            <td><a href="<?= URLROOT ?>/files/info/<?= $file->fileinfo_id ?>"><?= $file->file_name ?></a></td>
                            <td><?= property_exists($file, 'username') ? $file->username : $data['content']->guest ?></td>
                            <td><?= (new DateTime($file->file_created_at))->format('d.m.Y') ?></td>
                            <td><?= formatBytes($file->size) ?></td>
                            <td><?= $file->download_count ?> <?= $data['content']->time ?></td>
                        </tr>
                    <?php endforeach; ?>    
                </tbody>
            </table>
            <nav >
                <ul class="pagination">
                    <li class="page-item <?= $data['page_no'] == 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $data['page_no'] == 1 ? '#' : URLROOT . '/files/all/' . $data['page_no'] - 1 ?>"><?= $data['content']->pagePrev ?></a>
                    </li>
                    <?php for($i = 1; $i <= $data['page_count']; $i++): ?>
                    <li class="page-item <?= $data['page_no'] == $i ? 'active' : '' ?>" aria-current="page">
                        <a class="page-link" href="<?= URLROOT . '/files/all/' . $i?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $data['page_no'] == $data['page_count'] || $data['page_count'] == 0 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $data['page_no'] == $data['page_count'] ? '#' : URLROOT . '/files/all/' . $data['page_no'] + 1 ?>"><?= $data['content']->pageNext ?></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?php require_once APPROOT . '/views/partials/footer.php'?>