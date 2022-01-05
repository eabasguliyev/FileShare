<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 my-4">Uploaded Files</h1>
            <form method="POST" class="w-100">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="Enter file name" value="<?= isset($data['search']) ? $data['search'] : ''?>">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                </div>
            </form>
            <p id="result-count" class="mt-4">About <?= $data['total_count'] ?> result(s)</p>
            <table class="table table-hover mt-2">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Upload</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['files'] as $file): ?>
                        <tr>
                            <td><a href="<?= URLROOT ?>/files/info/<?= $file->fileinfo_id ?>"><?= $file->file_name ?></a></td>
                            <td><?= property_exists($file, 'username') ? $file->username : 'Guest' ?></td>
                            <td><?= (new DateTime($file->file_created_at))->format('d.m.Y') ?></td>
                            <td><?= formatBytes($file->size) ?></td>
                            <td><?= $file->download_count ?> times</td>
                        </tr>
                    <?php endforeach; ?>    
                </tbody>
            </table>
            <nav >
                <ul class="pagination">
                    <li class="page-item <?= $data['page_no'] == 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $data['page_no'] == 1 ? '#' : URLROOT . '/files/all/' . $data['page_no'] - 1 ?>">Previous</a>
                    </li>
                    <?php for($i = 1; $i <= $data['page_count']; $i++): ?>
                    <li class="page-item <?= $data['page_no'] == $i ? 'active' : '' ?>" aria-current="page">
                        <a class="page-link" href="<?= URLROOT . '/files/all/' . $i?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $data['page_no'] == $data['page_count'] || $data['page_count'] == 0 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $data['page_no'] == $data['page_count'] ? '#' : URLROOT . '/files/all/' . $data['page_no'] + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<?php require_once APPROOT . '/views/partials/footer.php'?>