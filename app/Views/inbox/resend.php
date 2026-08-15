<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['app_inbox_notifications'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('inbox') ?>"><?= $locale['app_inbox_notifications'] ?></a><span>/</span><span><?= $locale['new_notification'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('sendnewinbox') ?>">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Notification</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['title'] ?></label>
                <input type="text" name="title" class="nf-input" placeholder="<?= $locale['inbox_title'] ?>" value="<?= esc($inbox->title) ?>" required autofocus>
              </div>
              <div>
                <label class="nf-label"><?= $locale['inbox_content'] ?></label>
                <textarea name="message" class="nf-input" rows="5" style="resize:vertical;" placeholder="<?= $locale['inbox_content'] ?>" required><?= esc($inbox->message) ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['send_new'] ?></button>
            <a href="javascript:history.back()" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);border-color:#c7d2fe;">
            <div class="nf-card-body">
              <p style="font-size:.8rem;font-weight:700;color:#4338ca;margin:0 0 6px;">Resending Notification</p>
              <p style="font-size:.78rem;color:#6366f1;margin:0;">This will send a new push notification to all app users with the content above.</p>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
