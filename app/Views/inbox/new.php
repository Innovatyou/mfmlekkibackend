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
    <form method="POST" action="<?= base_url('sendnewinbox') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Notification Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['title'] ?></label>
                <input type="text" name="title" class="nf-input" placeholder="<?= $locale['title'] ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['inbox_content'] ?></label>
                <textarea name="message" rows="6" class="nf-input" style="resize:vertical;" placeholder="<?= $locale['inbox_content'] ?>" required></textarea>
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['send_new'] ?></button>
            <a href="<?= base_url('inbox') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card" style="background:linear-gradient(135deg,#eff6ff,#eef2ff);">
            <div class="nf-card-body" style="padding:24px;">
              <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="dw dw-notification" style="color:#fff;font-size:1rem;"></i></div>
                <div>
                  <p style="font-size:.85rem;font-weight:700;color:#3730a3;margin:0 0 6px;">Push Notifications</p>
                  <p style="font-size:.78rem;color:#6366f1;margin:0;line-height:1.5;">This notification will be sent to all app users' in-app inbox. Keep the title short and the message clear and concise.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<style>
.nf-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden}
.nf-card-head{padding:16px 20px 0;}.nf-card-title{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.nf-card-sub{font-size:.78rem;color:var(--t3);margin:0 0 16px;}
.nf-card-body{padding:16px 20px 20px;}
.nf-label{display:block;font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.nf-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:.875rem;color:var(--t1);outline:none;transition:border-color .15s;}
.nf-input:focus{border-color:var(--accent);}
.nf-submit{padding:10px 28px;font-weight:600;border-radius:9px;}
.nf-cancel{padding:10px 20px;font-weight:600;border-radius:9px;color:var(--t2);}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-bc span{color:var(--t3);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
</style>
