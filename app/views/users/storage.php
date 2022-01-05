<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto">
            <h1 class="fs-3 mt-3">Your Storage</h1>
            <div class="alert alert-primary w-100 text-center mt-2" role="alert">
                <p class="lead d-inline">Used: </p><span><?= $data['used_size'] ?> of <?= formatBytes(FREE_STORAGE_SIZE) ?> / <?= $data['file_count'] ?> file(s)</span>
            </div>
            <form method="POST" class="w-100">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="Enter file name" value="<?= isset($data['search']) ? $data['search'] : ''?>">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                </div>
            </form>
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
                                <a href="javascript:void(0);" class="me-2" onclick="openModal(<?= $file->fileinfo_id ?>)"><i class="bi bi-pencil-square"></i></a>
                                <a href="<?= URLROOT . '/files/delete/' . $file->file_id ?>"><i class="bi bi-trash"></i></a>
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
    <!-- Modals -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="filename" placeholder="File Name">
                        <label for="name">File Name</label>
                        <div class="invalid-feedback" id="filename-fb">
                            Please provide a valid city.
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Write description" id="description" style="height: 100px"></textarea>
                        <label for="description">Description</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="checkbox">
                        <label class="form-check-label" for="checkbox">
                            Private
                        </label>
                    </div>
                    <div id="passContainer" class="form-floating mb-3 d-none">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <label for="password">New Password</label>
                        <div class="invalid-feedback" id="password-fb">
                            Please provide a valid city.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="saveBtn" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="infoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="header-title">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="body-text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        let myModal = null;
        let infoModal = null;
        let passContainer = null;
        let nameEl = null;
        let descriptionEl = null;
        let checkboxEl = null;
        let passEl = null;

        $(() =>
        {
            passContainer = $('#passContainer');
            nameEl = $('#filename');
            descriptionEl = $('#description');
            checkboxEl = $('#checkbox');
            passEl = $('#password');

            myModal = new bootstrap.Modal($('#myModal'), {
                keyboard: false
            });
            infoModal = new bootstrap.Modal($('#infoModal'), {
                keyboard: false
            });

            checkboxEl.on('change', (e) =>{
                passContainer.toggleClass('d-none');
            })

            $("#infoModal").on("hidden.bs.modal", function () {
                location.reload();
            });
        });

        function openModal(id){
            // send request to server
            $.get(`<?= URLROOT ?>/files/getFileInfo/${id}`, function( data ) {
                const obj = $.parseJSON(data);
                const isPrivate = obj.fileinfo_status == 1;
                
                nameEl.val(obj.file_name);
                descriptionEl.val(obj.description);
                checkboxEl.prop('checked', isPrivate);

                if(isPrivate){
                    passContainer.removeClass('d-none');
                }else passContainer.addClass('d-none');

                $('#saveBtn').on('click', {
                    fileInfoId: id
                }, save);
            });
            myModal.show();
        }

        function save(e){
            // clean up
            nameEl.removeClass('is-invalid')
            passEl.removeClass('is-invalid')
            
            // send request to server
            const arg = e.data;
            $.post(`<?= URLROOT ?>/files/edit/${arg.fileInfoId}`, {
                filename: nameEl.val(),
                description: descriptionEl.val(),
                isPrivate: checkboxEl.prop('checked'),
                password: passEl.val()
            }).done(e => {
                $('.body-text').html(JSON.parse(e));
                myModal.hide();
                infoModal.show();
            }).fail(e => {
                const data = JSON.parse(e.responseText);
                for (const key in data.errors) {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}-fb`).html(data.errors[key]);
                }
            });
        }
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>