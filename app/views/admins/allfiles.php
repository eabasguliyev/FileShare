<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 my-4">Uploaded Files</h1>
            <div class="w-100">
                <?php flash('file_remove_success') ?>
            </div>
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
                        <th>Status</th>
                        <th>Action</th>
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
                            <td>
                                <?php
                                    $status = '';
                                    switch($file->fileinfo_status){
                                        case FileHelper::FILE_ATTR_PRIVATE:
                                            $status = 'Private';
                                            break;
                                        case FileHelper::FILE_ATTR_PUBLIC:
                                            $status = 'Public';
                                            break;
                                        case FileHelper::FILE_ATTR_REMOVE:
                                            $status = 'Removed';
                                            break;
                                        case FileHelper::FILE_ATTR_INACTIVE:
                                            $status = 'Inactive';
                                            break;
                                        default:
                                            $status = 'Unknown';
                                    }
                                    echo $status;
                                ?>
                            </td>
                            <td >
                                <?php
                                    $hasAccess = $_SESSION['admin_access_status'] == AdminHelper::ADMIN_STATUS_WRITE;
                                ?>
                                <a class="<?= $hasAccess ? '' : 'disabled' ?>" href="javascript:void(0);" class="me-2">
                                    <input class="form-check-input statusCheckbox" type="checkbox" value=""
                                    data-fileinfoid='<?= $file->fileinfo_id ?>'
                                    <?= $file->fileinfo_status == FileHelper::FILE_ATTR_PUBLIC || $file->fileinfo_status == FileHelper::FILE_ATTR_PRIVATE ? 'checked' : '' ?>>
                                </a>
                                <a class="<?= $hasAccess ? '' : 'disabled' ?>" href="<?= URLROOT . '/admins/deletefile/' . $file->file_id?>"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>    
                </tbody>
            </table>
            <nav >
                <ul class="pagination">
                    <li class="page-item <?= $data['page_no'] == 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="<?= $data['page_no'] == 1 ? '#' : URLROOT . '/admins/allfiles/' . $data['page_no'] - 1 ?>">Previous</a>
                    </li>
                    <?php for($i = 1; $i <= $data['page_count']; $i++): ?>
                    <li class="page-item <?= $data['page_no'] == $i ? 'active' : '' ?>" aria-current="page">
                        <a class="page-link" href="<?= URLROOT . '/admins/allfiles/' . $i?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $data['page_no'] == $data['page_count'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $data['page_no'] == $data['page_count'] ? '#' : URLROOT . '/admins/allfiles/' . $data['page_no'] + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        let statusCheckbox = null;
        $(() => {
            statusCheckbox = $('.statusCheckbox');

            statusCheckbox.on('change', (e) => {
                const target = $(e.target);
                const stat = target.prop('checked');
                const fileInfoId = target.data('fileinfoid');

                $.post(`<?= URLROOT ?>/admins/changefilestatus/${fileInfoId}`, 
                {
                    status: stat
                }).done(e => {
                    location.reload();
                }).fail(e => {
                    console.log(msg);
                });
            });
        });
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>