<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Admin Users</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('adminListing') ?>">Admin Users</a><span>/</span><span>New Admin</span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('savenewadmin') ?>">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-6">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Account Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Full Name</label>
                <input type="text" name="name" class="nf-input" placeholder="Full Name" required autofocus>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Email Address</label>
                <input type="email" name="email" class="nf-input" placeholder="Email Address" required>
              </div>
              <div>
                <label class="nf-label">Password</label>
                <input type="password" name="password" class="nf-input" placeholder="Password" required autocomplete="new-password">
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Save New</button>
            <a href="javascript:history.back()" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
