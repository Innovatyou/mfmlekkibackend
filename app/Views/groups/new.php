<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['groups'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= $locale['new_group'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('savenewgroup') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Group Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_title'] ?></label>
                  <input type="text" name="title" class="nf-input" placeholder="<?= $locale['group_title'] ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_leader_name'] ?></label>
                  <input type="text" name="leader" class="nf-input" placeholder="<?= $locale['group_leader_name'] ?>" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['group_desc'] ?></label>
                <textarea name="description" rows="4" class="nf-input" style="resize:vertical;" placeholder="<?= $locale['group_desc'] ?>" required></textarea>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_meeting_loc'] ?></label>
                  <input type="text" name="location" class="nf-input" placeholder="<?= $locale['group_meeting_loc'] ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['meeting_days_extra'] ?></label>
                  <input type="text" name="time" class="nf-input" placeholder="<?= $locale['meeting_days'] ?>" required>
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['save_new'] ?></button>
            <a href="<?= base_url('groups') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card" style="background:linear-gradient(135deg,#f0fdfa,#ecfdf5);">
            <div class="nf-card-body" style="padding:24px;">
              <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:#0d9488;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="icon-copy ion-ios-people" style="color:#fff;font-size:1.1rem;"></i></div>
                <div>
                  <p style="font-size:.85rem;font-weight:700;color:#065f46;margin:0 0 6px;">Group Tips</p>
                  <ul style="font-size:.78rem;color:#0d9488;margin:0;padding-left:16px;line-height:1.7;">
                    <li>Add a descriptive title that reflects the group's purpose</li>
                    <li>Specify meeting days and times clearly</li>
                    <li>After creating, add members from the group listing</li>
                  </ul>
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
.nf-row{display:flex;gap:16px;}.nf-col-half{flex:1;min-width:0;}
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
