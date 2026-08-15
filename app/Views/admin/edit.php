<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Admin Users</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('admin/users') ?>">Admin Users</a><span>/</span><span>Edit Details</span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form autocomplete="off" method="POST" action="<?= base_url('editadmindata') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $admin->id ?>">
      <div class="row">
        <div class="col-lg-6">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Account Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Full Name</label>
                <input type="text" name="name" class="nf-input" value="<?= esc($admin->fullname) ?>" placeholder="Full Name" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Email Address</label>
                <input type="email" name="email" class="nf-input" value="<?= esc($admin->email) ?>" placeholder="Email Address" required>
              </div>
              <div>
                <label class="nf-label">New Password <span style="font-weight:400;text-transform:none;color:var(--t3);">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="nf-input" placeholder="New password" autocomplete="new-password">
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Update Details</button>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
