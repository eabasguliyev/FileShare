<?php require_once APPROOT . '/views/partials/header.php'?>
<div class="container">
  <div class="accordion mt-4" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingOne">
        <button
          class="accordion-button"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#panelsStayOpen-collapseOne"
          aria-expanded="true"
          aria-controls="panelsStayOpen-collapseOne"
        >
          Files
        </button>
      </h2>
      <div
        id="panelsStayOpen-collapseOne"
        class="accordion-collapse collapse show"
        aria-labelledby="panelsStayOpen-headingOne"
      >
        <div class="accordion-body">
            <ul class="list-unstyled d-flex gap-4">
                <li class="text-center">
                    <a href="<?= URLROOT ?>/files/allfiles">
                        <i class="bi bi-files fs-4 "></i>
                        <br/>
                        Uploaded Files
                    </a>
                </li>
                <li class="text-center">
                    <a href="<?= URLROOT ?>/files/reportedfiles">
                        <i class="bi bi-flag-fill fs-4"></i>
                        <br/>
                        Reported Files
                    </a>
                </li>
            </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/partials/footer.php'?>
