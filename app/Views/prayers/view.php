<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['prayer_requests'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('prayers') ?>"><?= $locale['prayer_requests'] ?></a><span>/</span><span><?= $locale['view_request'] ?></span></nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['view_request'] ?></h3></div>
          <div class="nf-card-body">
            <div class="nf-row" style="margin-bottom:16px;">
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['requester'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($prayer->requester) ?>" readonly>
              </div>
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['prayer_visibility'] ?></label>
                <input type="text" class="nf-input" value="<?= $prayer->public==0 ? $locale['public'] : $locale['private'] ?>" readonly>
              </div>
            </div>
            <div style="margin-bottom:16px;">
              <label class="nf-label"><?= $locale['request_title'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($prayer->title) ?>" readonly>
            </div>
            <div>
              <label class="nf-label"><?= $locale['request_content'] ?></label>
              <textarea class="editor1" name="content" readonly><?= $prayer->content ?></textarea>
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
