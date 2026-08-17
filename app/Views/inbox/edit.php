<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['app_inbox_notifications'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('inbox') ?>"><?= $locale['app_inbox_notifications'] ?></a><span>/</span><span><?= $locale['edit_notification'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editInboxData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $inbox->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Edit Notification</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['inbox_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($inbox->title) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['inbox_content'] ?></label>
                <textarea name="message" rows="6" class="nf-input" style="resize:vertical;" required><?= esc($inbox->message) ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_msg'] ?></button>
            <a href="<?= base_url('inbox') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card" style="background:linear-gradient(135deg,#eff6ff,#eef2ff);">
            <div class="nf-card-body" style="padding:24px;">
              <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="dw dw-notification" style="color:#fff;font-size:1rem;"></i></div>
                <div>
                  <p style="font-size:.85rem;font-weight:700;color:#3730a3;margin:0 0 6px;">Editing a notification</p>
                  <p style="font-size:.78rem;color:#6366f1;margin:0;line-height:1.5;">Changes here update the stored record. Use Resend from the listing to push the updated notification to app users again.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
