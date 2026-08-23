<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['testimonies'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('testimonyListing') ?>"><?= $locale['testimonies'] ?></a><span>/</span><span>View Testimony</span></nav>
      </div>
      <?php $approved = $testimony->status == 0 ? 1 : 0; ?>
      <a href="<?= base_url('editTestimonyStatus/' . $testimony->id . '/' . $approved) ?>" class="btn <?= $testimony->status == 1 ? 'btn-success' : 'btn-warning' ?> lt-cta">
        <i class="dw dw-<?= $testimony->status == 1 ? 'check' : 'close' ?>-circle-2"></i><?= $testimony->status == 1 ? 'Approve' : 'Disapprove' ?>
      </a>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title">View Testimony</h3></div>
          <div class="nf-card-body">
            <div style="margin-bottom:16px;">
              <label class="nf-label"><?= $locale['name_testifier'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($testimony->testifier) ?>" readonly>
            </div>
            <div style="margin-bottom:16px;">
              <label class="nf-label"><?= $locale['testimony_title'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($testimony->title) ?>" readonly>
            </div>
            <div>
              <label class="nf-label"><?= $locale['testimony_content'] ?></label>
              <textarea class="editor1" name="content" readonly><?= $testimony->content ?></textarea>
            </div>
          </div>
        </div>
        <div style="margin-top:24px;">
          <a href="javascript:history.back()" class="btn btn-light nf-cancel">Back</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
