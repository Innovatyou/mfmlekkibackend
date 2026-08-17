<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">New Leader</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('leadershipListing') ?>">Leadership</a><span>/</span><span>New</span></nav>
      </div>
    </div>
    <form method="POST" action="<?= base_url('saveNewLeader') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Leader Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Full Name</label>
                  <input type="text" name="name" class="nf-input" placeholder="e.g. Rev. John Doe" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Role / Title</label>
                  <input type="text" name="role_title" class="nf-input" placeholder="e.g. Senior Pastor" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Email (optional)</label>
                <input type="email" name="email" class="nf-input" placeholder="pastor@church.org">
              </div>
              <div>
                <label class="nf-label">Short Bio</label>
                <textarea name="bio" class="nf-input" rows="4" placeholder="A few sentences about this leader"></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Save Leader</button>
            <a href="<?= base_url('leadershipListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card" style="margin-bottom:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title">Photo</h3></div>
            <div class="nf-card-body">
              <label class="nf-upload-zone" style="display:block;" onclick="document.getElementById('ld-photo-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-upload"></i></div>
                <p class="nf-upload-text">Click to upload photo</p>
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
                <input type="number" name="sort_order" class="nf-input" value="0">
              </div>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" selected>Active — shown on website</option>
                  <option value="inactive">Inactive — hidden</option>
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
