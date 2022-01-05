<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="fs-3 my-4">Upload File</h1>
        <form id="form" class="w-50 d-flex flex-column">
            <div class="progress d-none">
                <div class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
            </div>
            <div class="card card-body bg-light ">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <p>1 file max up to <span class="text-danger"><?= formatBytes(FREE_FILE_SIZE, 2)?></span></p>
                    <div class="upload-file">
                        <label for="custom-input-file" id="custom-label-file" class="btn btn-success btn-block my-2 mt-3"><i class="bi bi-file-earmark-fill me-1"></i>Choose File</label>
                        <input type="file" class="d-none" name="uploaded_file" id="custom-input-file">
                    </div>
                    <div class="input-group d-none uploaded-file py-4 container">
                        <div class="row w-100 bg-white mb-2 ms-1 p-2 mx-0">
                            <p class="col-7 file-name px-0 mt-1">file name</p>
                            <div class="col-5 row file-format px-0 mx-0 mt-1">
                                <p class="col-5 file-size px-0 mx-0 mt-1 text-black-50">size</p>
                                <p class="col-6 file-type px-0 mx-0 mt-1 text-black-50">type</p>
                                <div class="col-1 position-relative px-0 mx-0 text-end">
                                    <span class="bi bi-trash-fill text-danger fs-4 file-cancel cursor-pointer"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group ms-1">
                            <span class="input-group-text">Description</span>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="row w-50 mt-2">
                            <div class="col-4">
                                <div class="form-check ms-1 mt-2">
                                    <input name="isPrivate" class="form-check-input" type="checkbox" id="private">
                                    <label class="form-check-label unselectable cursor-pointer" for="private">Private</label>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <input name="password" type="text" id="password"  class="form-control d-none" placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="card card-body bg-light mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms">
                    <label class="form-check-label unselectable cursor-pointer" for="terms">I have read and agree to the</label>
                    <a href="#" class="nav-link d-inline px-1">Terms of service</a>
                </div>
            </div>
            <button class="btn btn-primary px-4 mt-2" id="submit-file" type="submit"><i class="bi bi-arrow-up-square text-white me-2"></i>Upload</button>
        </form>
    </div>
    <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="header-title"></h5>
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
        $(() =>{
            const inputFile = $('#custom-input-file');
            const privateEl = $('#private');
            const passwordEl = $('#password');
            const termsEl = $('#terms');
            const myModal = new bootstrap.Modal($('#myModal'), {
                keyboard: false
            });

            let isStarted = false;

            privateEl.prop('checked', false);
            termsEl.prop('checked', false);

            inputFile.on('input', function(e){
                if(e.target.value != ''){
                    $('.upload-file').addClass('d-none');
                    $('.uploaded-file').removeClass('d-none');
                    $('.progress').removeClass('d-none');

                    const file = inputFile.prop('files')[0];

                    $('.file-name').html(file.name);
                    $('.file-size').html(formatBytes(file.size));
                    $('.file-type').html(file.type);

                    if(inputFile.prop('files')[0].size > <?= FREE_FILE_SIZE ?>)
                    {
                        cancelFile();
                        text = 'File size is too large. File size must be max ' + formatBytes(<?= FREE_FILE_SIZE ?>);
                        openModal('Info', text);
                    }
                }
            });

            $('.file-cancel').on('click', cancelFile);

            privateEl.change(function() {
                passwordEl.toggleClass('d-none');
            });

            const form = document.getElementById('form');
            const progressBar = document.getElementsByClassName('progress-bar')[0];

            form.addEventListener('submit', uploadFile);

            function uploadFile(e){
                e.preventDefault();

                const result = uploadValidation();

                if(result[0]){
                    openModal('Info', result[1]);
                    return;
                }

                if(isStarted)
                    return;

                resetProgressBar();
                isStarted = true;

                const xhr = new XMLHttpRequest();

                xhr.open('POST', 'upload');

                
                xhr.upload.addEventListener('progress', e =>{
                    const percent = e.lengthComputable ? (e.loaded / e.total) * 100 : 0;

                    progressBar.style.width = percent.toFixed(2) + '%';
                    progressBar.textContent = percent.toFixed(2) + '%';

                    if(!isStarted)
                        xhr.abort();
                });

                xhr.onreadystatechange = function () {
                    progressBar.textContent = 'Uploaded';
                    progressBar.style.backgroundColor = '#157347';

                    if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                        const id = JSON.parse(xhr.responseText);
                        setTimeout(() => {
                            location.href = `<?= URLROOT?>/files/success/${id}`;
                        }, 1000);   
                    }else if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 406){
                        const msg = JSON.parse(xhr.responseText);
                        openModal('Info', msg);
                        isStarted = false;
                    }
                };

                xhr.send(new FormData(form));
            }

            function openModal(title, body){
                $('.modal-header .header-title').html(title);
                $('.modal-body .body-text').html(body);
                myModal.show();
            }

            function uploadValidation(){
                let status = false;
                let text = '';
                
                if(!inputFile.val()){
                    status = true;
                    text = "You must be select at least 1 file.";
                }

                if(!termsEl.prop('checked') && !status){
                    status = true;
                    text = 'If you want to upload file, you must be sign terms of service.';
                }

                if(privateEl.prop('checked') && passwordEl.val().trim().length < 4){
                    status = true;
                    text = 'Password length must be at least 4 characters.';
                }
                
                return [ status, text ];
            }

            function resetProgressBar(){
                progressBar.textContent = '0.00%';
                progressBar.style.width = '0%';
                progressBar.style.backgroundColor = '#0D6EFD';
            }

            function cancelFile(){
                inputFile.val('');
                inputFile.prop('files', null)
                $('.upload-file').removeClass('d-none');
                $('.uploaded-file').addClass('d-none');
                $('.progress').addClass('d-none');
                
                if(privateEl.prop('checked')){
                    privateEl.prop('checked', false);
                    passwordEl.addClass('d-none');
                    passwordEl.val('');
                }
                
                resetProgressBar();

                isStarted = false;
            }
        });
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>