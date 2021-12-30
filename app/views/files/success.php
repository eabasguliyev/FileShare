<?php require_once APPROOT . '/views/partials/header.php'?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="fs-3 my-4">Upload Complete</h1>

        <div class="container w-75">
            <hr class="m-0">
            <div class="row my-2">
                <div class="col-10">
                    <a href="<?= $data['path'] ?>" class="nav-link"><?= $data['name'] ?></a>
                </div>
                <div class="col-2 mt-1">
                    <p>Size: <span class="text-black-50"><?= formatBytes($data['size']) ?></span></p>
                </div>
            </div>
            <hr class="m-0">
            <ul class="nav nav-tabs mt-3" data-active-tab = "0">
                <li class="nav-item cursor-pointer unselectable" data-item="link">
                    <p class="nav-link active" >Download Link</p>
                </li>
                <li class="nav-item cursor-pointer unselectable" data-item="html">
                    <p class="nav-link" >HTML Code</p>
                </li>
            </ul>
            <div class="card card-body bg-light mt-2">
                        <div class="row ">
                            <div id = "info-content" class="col-11">
                                <a href="<?= $data['path'] ?>" target="_blank" class="nav-link p-0"><?= $data['path'] ?></a>
                            </div>
                            <div class="col-1 position-relative">
                                <span id="link-copy" class="bi bi-clipboard text-primary fs-5 position-absolute top-0 end-0 me-3 cursor-pointer"></span>
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
            const navTabEl = $(".nav.nav-tabs");
            const infoContentEl = $('#info-content');
            const tabs = navTabEl.children();

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

                let content = ''
                if(navTabEl.data('active-tab') == 0){
                    content = infoContentEl.children()[0].innerHTML;
                }else{
                    content = infoContentEl.html();
                }
                temp.val(content).select();
                document.execCommand("copy");
                temp.remove();
            });

            $(".nav-tabs .nav-item").on('click', function(e){
                // Change tab
                const target = $(e.target);
                $($($(".nav.nav-tabs li")[navTabEl.data('active-tab')]).children()[0]).removeClass('active');
                target.addClass('active');
                
                const liEl = target.parent();
                navTabEl.data('active-tab', liEl.index())

                // Set new content
                const name = '<?= $data['name'] ?>';
                const path = '<?= $data['path'] ?>';

                const content = liEl.data('item') == 'link' ? 
                                        `<a href="${path}" target="_blank" class="nav-link p-0">${path}</a>`:
                                        `<div
                                                style="
                                                    background-color: #f8f9fa;
                                                    display: flex;
                                                    justify-content: center;
                                                    align-items: center;
                                                    padding: 5px;
                                                    width: 25%;
                                                    gap: 10px;
                                                "
                                                >
                                                <p>File Name: ${name}</p>
                                                <a
                                                    href="${path}"
                                                    style="
                                                    height: 40px;
                                                    width: 100px;
                                                    text-decoration: none;
                                                    color: white;
                                                    border: 1px solid blue;
                                                    display: flex;
                                                    justify-content: center;
                                                    align-items: center;
                                                    border-radius: 5px;
                                                    background-color: #0b5ed7;
                                                    "
                                                >
                                                    Download
                                                </a>
                                                </div>`;
                
                
                infoContentEl.html(content);
            })
        });
    </script>
<?php require_once APPROOT . '/views/partials/footer.php'?>