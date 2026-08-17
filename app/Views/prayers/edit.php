<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['prayer_requests'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('prayersListing') ?>"><?= $locale['prayer_requests'] ?></a><span>/</span><span><?= $locale['edit_prayer'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editprayerdata') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $prayer->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Edit Prayer Request</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['requester'] ?></label>
                  <input type="text" name="requester" class="nf-input" value="<?= esc($prayer->requester) ?>">
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['prayer_visibility'] ?></label>
                  <select name="public" class="nf-input nf-select">
                    <option value="0" <?= $prayer->public==0?'selected':'' ?>><?= $locale['public'] ?></option>
                    <option value="1" <?= $prayer->public==1?'selected':'' ?>><?= $locale['private'] ?></option>
                  </select>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['request_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($prayer->title) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['request_content'] ?></label>
                <textarea class="editor1" name="content"><?= $prayer->content ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_prayer'] ?></button>
            <a href="<?= base_url('prayersListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
