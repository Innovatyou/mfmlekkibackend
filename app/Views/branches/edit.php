<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['church_loc'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('branchesListing') ?>"><?= $locale['church_loc'] ?></a><span>/</span><span><?= $locale['edit_loc'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editBranchData') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $branch->id ?>">
      <div class="row">
        <div class="col-lg-7">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Location Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['branch_name'] ?></label>
                <input type="text" name="name" class="nf-input" value="<?= esc($branch->name) ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['loc_address'] ?></label>
                <input type="text" name="address" class="nf-input" value="<?= esc($branch->address) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['loc_pastor'] ?></label>
                <input type="text" name="pastor" class="nf-input" value="<?= esc($branch->pastor) ?>" required>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_loc'] ?></button>
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
                  <input type="text" name="phone" class="nf-input" value="<?= esc($branch->phone) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_email'] ?></label>
                  <input type="email" name="email" class="nf-input" value="<?= esc($branch->email) ?>">
                </div>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_latitude'] ?></label>
                  <input type="number" step="any" name="latitude" class="nf-input" value="<?= esc($branch->latitude) ?>">
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['loc_longitude'] ?></label>
                  <input type="number" step="any" name="longitude" class="nf-input" value="<?= esc($branch->longitude) ?>">
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
