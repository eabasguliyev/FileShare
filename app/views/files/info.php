<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="fs-3 my-4">Download File</h1>
        <div class="w-50 d-flex flex-column">
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
                    <tr>
                        <td><?= $data['file']->file_name ?></td>
                        <td><?= property_exists($data['file'], 'username') ? $data['file']->username : 'Guest' ?></td>
                        <td><?= (new DateTime($data['file']->file_created_at))->format('d.m.Y') ?></td>
                        <td><?= formatBytes($data['file']->size) ?></td>
                        <td><?= $data['file']->download_count ?> times</td>
                    </tr>
                </tbody>
            </table>
            <div class="d-grid">
                <div class="row">
                    <div class="col-6">
                        <p class="lead fs-6 d-inline">Description: </p><span><?= $data['file']->description ?></span>
                    </div>
                    <div class="col-6">
                        <a href="#" class="text-danger float-end me-1">Report this file</a>
                    </div>
                </div>
                <div class="row mt-5">
                    <div  class="col text-center">
                        <button id="download-btn" class="btn btn-primary">Create Download Link</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="<?= URLROOT ?>/js/functions.js"></script>
    <script>
        $(() =>{
            const linkCopyEl = $('#link-copy');
            const downloadLinkEl = $('#download-link');

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
        });
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>