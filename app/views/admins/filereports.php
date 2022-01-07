<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 my-4">Reported Files</h1>
            <div class="w-100">
                <?php flash('report_delete_success') ?>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['reports'] as $report): ?>
                        <tr data-reportid='<?= $report->report_id ?>' class="cursor-pointer">
                            <td><?= $report->report_name ?></td>
                            <td><?= $report->report_email ?></td>
                            <td><?= $report->file_name ?></td>
                            <td >
                                <?php
                                    $hasAccess = $_SESSION['admin_access_status'] == AdminHelper::ADMIN_STATUS_WRITE;
                                ?>
                                <a class="<?= $hasAccess ? '' : 'disabled' ?>" href="<?= URLROOT . '/reports/deletereport/' . $report->report_id?>"><i class="bi bi-trash"></i></a>
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
                    <li class="page-item <?= $data['page_no'] == $data['page_count'] || $data['page_count'] == 0 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $data['page_no'] == $data['page_count'] ? '#' : URLROOT . '/admins/allfiles/' . $data['page_no'] + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <h2 class="fs-5">Report Info</h2>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Name: </p><span id="report-name"></span>
                    </div>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Email: </p><span id="report-email"></span>
                    </div>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Description: </p><span id="report-desc"></span>
                    </div>
                    <hr class="m-0 mb-2">
                    <h2 class="fs-5">File Info</h2>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Upload: </p><span id="file-upload"></span>
                    </div>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">File Name: </p><span id="file-name"></span>
                    </div>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Type: </p><span id="file-type"></span>
                    </div>
                    <div class="form-floating mb-3">
                        <p class="fs-6 fw-bold d-inline">Status: </p><span id="file-status"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary <?= $hasAccess ? '' : 'disabled' ?>" href="#" id="delete-file">Delete File</a>
                    <a class="btn btn-danger <?= $hasAccess ? '' : 'disabled' ?>" href="#" id="delete-report">Delete Report</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        let myModal = null;
        $(() => {
            $('.table tr.cursor-pointer').on('click', (e) =>{
                const target = $(e.currentTarget);
                const id = target.data('reportid');
                myModal = new bootstrap.Modal($('#myModal'), {
                keyboard: false
                });


                $.post(`<?= URLROOT ?>/reports/getreport/${id}`)
                .done(e => {
                    const obj = JSON.parse(e);
                    $('#report-name').text(obj.report_name);
                    $('#report-email').text(obj.report_email);
                    $('#report-desc').text(obj.report_description);
                    $('#file-upload').text(obj.username);
                    $('#file-name').text(obj.file_name);
                    $('#file-type').text(obj.file_type);

                    let status = '';

                    switch(obj.fileinfo_status){
                        case '0':
                            status = 'Public';
                            break;
                        case '1':
                            status = 'Private';
                            break;
                        case '2':
                            status = 'Inactive';
                            break;
                        case '3':
                            status = 'Remove';
                            break;
                        case '4':
                            status = 'Active';
                            break;
                        default:
                            status = 'Unknown';
                    }

                    $('#file-status').text(status);
                    
                    $('#delete-report').prop('href', "<?= URLROOT . '/reports/deletereport/' ?>"
                                             + obj.report_id);
                    $('#delete-file').prop('href', "<?= URLROOT . '/admins/deletefile/' ?>" 
                                            + obj.file_id);
                    myModal.show();
                })
                .fail(e => {});
            })
        });
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>