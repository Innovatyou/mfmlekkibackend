<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['church_loc'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('branchesListing') ?>"><?= $locale['church_loc'] ?></a><span>/</span><span><?= $locale['new_loc'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('savenewbranch') ?>">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-7">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Location Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['branch_name'] ?></label>
                <input type="text" name="name" class="nf-input" placeholder="<?= $locale['branch_name'] ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['loc_address'] ?></label>
                <input type="text" name="address" class="nf-input" placeholder="<?= $locale['loc_address'] ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['loc_pastor'] ?></label>
                <input type="text" name="pastor" class="nf-input" placeholder="<?= $locale['loc_pastor'] ?>" required>
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['save_new'] ?></button>
            <a href="<?= base_url('branchesListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Contact &amp; Coordinates</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_phone'] ?></label>
                  <input type="text" name="phone" class="nf-input" placeholder="<?= $locale['loc_phone'] ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_email'] ?></label>
                  <input type="email" name="email" class="nf-input" placeholder="<?= $locale['loc_email'] ?>">
                </div>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_latitude'] ?></label>
                  <input type="number" step="any" name="latitude" class="nf-input" placeholder="e.g. 6.5244" value="0.0">
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_longitude'] ?></label>
                  <input type="number" step="any" name="longitude" class="nf-input" placeholder="e.g. 3.3792" value="0.0">
                </div>
              </div>
            </div>
          </div>

          <div class="nf-card" style="margin-top:16px;background:linear-gradient(135deg,#f0fdfa,#ecfdf5);">
            <div class="nf-card-body" style="padding:20px;">
              <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:#0d9488;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="dw dw-location" style="color:#fff;font-size:1rem;"></i></div>
                <div>
                  <p style="font-size:.8rem;font-weight:600;color:#065f46;margin:0 0 4px;">GPS Coordinates</p>
                  <p style="font-size:.78rem;color:#0d9488;margin:0;line-height:1.5;">Latitude and longitude are used to display this location on the in-app map. Leave at 0.0 if unknown.</p>
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
