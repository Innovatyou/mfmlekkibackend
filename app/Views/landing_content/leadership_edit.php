<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Leader</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('leadershipListing') ?>">Leadership</a><span>/</span><span>Edit</span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editLeaderData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $item->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Leader Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Full Name</label>
                  <input type="text" name="name" class="nf-input" value="<?= esc($item->name) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Role / Title</label>
                  <input type="text" name="role_title" class="nf-input" value="<?= esc($item->role_title) ?>" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Email (optional)</label>
                <input type="email" name="email" class="nf-input" value="<?= esc($item->email) ?>">
              </div>
              <div>
                <label class="nf-label">Short Bio</label>
                <textarea name="bio" class="nf-input" rows="4"><?= esc($item->bio) ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Update Leader</button>
            <a href="<?= base_url('leadershipListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card" style="margin-bottom:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title">Photo</h3></div>
            <div class="nf-card-body">
              <?php if(!empty($item->photo)):?>
                <div style="text-align:center;margin-bottom:12px;">
                  <img src="<?=esc($item->photo)?>" style="width:80px;height:80px;border-radius:16px;object-fit:cover;">
                </div>
              <?php endif;?>
              <label class="nf-upload-zone" style="display:block;" onclick="document.getElementById('ld-photo-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-upload"></i></div>
                <p class="nf-upload-text">Click to replace photo</p>
                <p class="nf-upload-hint">JPG, PNG or WEBP</p>
              </label>
              <input type="file" name="photo" id="ld-photo-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;" onchange="document.getElementById('ld-file-name').textContent=this.files[0]?this.files[0].name:'';">
              <p id="ld-file-name" style="font-size:.75rem;color:var(--t3);margin:6px 0 0;text-align:center;"></p>
            </div>
          </div>
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Display</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Sort Order</label>
                <input type="number" name="sort_order" class="nf-input" value="<?= (int) $item->sort_order ?>">
              </div>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" <?= $item->status=='active'?'selected':'' ?>>Active — shown on website</option>
                  <option value="inactive" <?= $item->status=='inactive'?'selected':'' ?>>Inactive — hidden</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
