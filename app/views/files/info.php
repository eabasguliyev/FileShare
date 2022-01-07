<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="fs-3 my-4"><?= $data['content']->title ?></h1>
        <div class="w-50 d-flex flex-column">
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
                    <tr>
                        <td><?= $data['file']->file_name ?></td>
                        <td><?= property_exists($data['file'], 'username') ? $data['file']->username : $data['content']->guest ?></td>
                        <td><?= (new DateTime($data['file']->file_created_at))->format('d.m.Y') ?></td>
                        <td><?= formatBytes($data['file']->size) ?></td>
                        <td><?= $data['file']->download_count ?> <?= $data['content']->time ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-grid">
                <div class="row">
                    <div class="col-6">
                        <p class="lead fs-6 d-inline"><?= $data['content']->desc ?>: </p><span><?= $data['file']->description ?></span>
                    </div>
                    <div class="col-6">
                        <a href="javascript:void(0);" class="text-danger float-end me-1" onclick="myModal.show()"><?= $data['content']->report ?></a>
                    </div>
                </div>
                <div class="row mt-5">
                    <div  class="col text-center">
                        <button id="download-btn" class="btn btn-primary"><?= $data['content']->button ?></button>
                    </div>
                    <div id="download-card" class="col card card-body bg-light d-none">
                        <div class="row ">
                            <div class="col-11">
                                <a href="#" target="_blank" id="download-link" class="nav-link p-0">DOWNLOAD LINK</a>
                            </div>
                            <div class="col-1 position-relative">
                                <span id="link-copy" class="bi bi-clipboard text-primary fs-5 position-absolute top-0 end-0 me-3 cursor-pointer"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Name">
                        <label for="name">Name</label>
                        <div class="invalid-feedback" id="name-fb">
                            
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Email">
                        <label for="email">Email</label>
                        <div class="invalid-feedback" id="email-fb">
                            
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Write description" id="description" style="height: 100px"></textarea>
                        <label for="description">Description</label>
                        <div class="invalid-feedback" id="description-fb">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveBtn" class="btn btn-primary">Save changes</button>
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
    <script src="<?= URLROOT ?>/js/functions.js"></script>
    <script>
        let myModal = null;
        let infoModal = null;
        let nameEl = null;
        let emailEl = null;
        let descriptionEl = null;


        $(() =>{
            nameEl = $('#name');
            emailEl = $('#email');
            descriptionEl = $('#description');
            const fileInfoId = <?= $data['file']->fileinfo_id ?>;
            const linkCopyEl = $('#link-copy');
            const downloadLinkEl = $('#download-link');
            myModal = new bootstrap.Modal($('#myModal'), {
                keyboard: false
            });
            infoModal = new bootstrap.Modal($('#infoModal'), {
                keyboard: false
            });


            linkCopyEl.on('click', function(){
                if(linkCopyEl.hasClass('bi-clipboard')){
                    linkCopyEl.removeClass('bi-clipboard');
                    linkCopyEl.addClass('bi-clipboard-check');

                    setTimeout(() => {
                        linkCopyEl.removeClass('bi-clipboard-check');
                        linkCopyEl.addClass('bi-clipboard');
                    }, 1000);
                }

                const temp = $("<input>");
                $("body").append(temp);
                temp.val(downloadLinkEl.text()).select();
                document.execCommand("copy");
                temp.remove();
            })

            $('#download-btn').on('click', function(e){
                $.post('<?= URLROOT ?>/links/create/<?= $data['file']->fileinfo_id ?>', function (link){
                    downloadLinkEl.html(link);
                    downloadLinkEl.prop('href', link)
                }).done(function (){
                    $(e.target).parent().addClass('d-none');
                    $('#download-card').removeClass('d-none');
                });
            });

            $('#saveBtn').on('click', {
                fileInfoId
            }, save);
        });

        function save(e){
            // clean up
            nameEl.removeClass('is-invalid')
            emailEl.removeClass('is-invalid')
            descriptionEl.removeClass('is-invalid')
            
            // send request to server
            const arg = e.data;
            $.post(`<?= URLROOT ?>/reports/report/`, {
                id: arg.fileInfoId,
                name: nameEl.val(),
                email: emailEl.val(),
                description: descriptionEl.val(),
            }).done(e => {
                $('.body-text').html(JSON.parse(e));
                nameEl.val('');
                emailEl.val('');
                descriptionEl.val('');
                myModal.hide();
                infoModal.show();
            }).fail(e => {
                console.log(e.responseText);
                const data = JSON.parse(e.responseText);
                for (const key in data.errors) {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}-fb`).html(data.errors[key]);
                }
            });
        }
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>