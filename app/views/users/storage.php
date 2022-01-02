<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 mt-3">Your Storage</h1>
            <div class="alert alert-primary w-100 text-center mt-2" role="alert">
                <p class="lead d-inline">Used: </p><span><?= $data['used_size'] ?> of <?= formatBytes(FREE_STORAGE_SIZE) ?> / <?= $data['file_count'] ?> file(s)</span>
            </div>
            <p id="result-count" class="mt-4">About <?= $data['files_count'] ?> result(s)</p>
            <table class="table table-hover mt-2">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th>Download</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['files'] as $file): ?>
                        <tr>
                            <td><a href="<?= URLROOT ?>/files/info/<?= $file->fileinfo_id ?>"><?= $file->file_name ?></a></td>
                            <td><?= (new DateTime($file->file_created_at))->format('d.m.Y') ?></td>
                            <td><?= formatBytes($file->size) ?></td>
                            <td><?= $file->download_count ?> times</td>
                            <td>
                                <a href="" class="me-2"><i class="bi bi-pencil-square"></i></a>
                                <a href=""><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>    
                </tbody>
            </table>
            <nav >
                <ul class="pagination">
                    <li class="page-item <?= $data['page_no'] == 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $data['page_no'] == 1 ? '#' : URLROOT . '/users/storage/' . $_SESSION['user_storage_id'] . '/' . $data['page_no'] - 1 ?>">Previous</a>
                    </li>
                    <?php for($i = 1; $i <= $data['page_count']; $i++): ?>
                    <li class="page-item <?= $data['page_no'] == $i ? 'active' : '' ?>" aria-current="page">
                        <a class="page-link" href="<?= URLROOT . '/users/storage/' . $_SESSION['user_storage_id'] . '/' . $i?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $data['page_no'] == $data['page_count'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $data['page_no'] == $data['page_count'] ? '#' : URLROOT . '/users/storage/' . $_SESSION['user_storage_id'] . '/' . $data['page_no'] + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<?php require_once APPROOT . '/views/partials/footer.php'?>