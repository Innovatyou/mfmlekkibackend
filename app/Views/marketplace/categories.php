<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <div class="page-header">
      <div>
        <h1 class="page-title">Marketplace Categories</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="margin:0;padding:0;background:none;font-size:.82rem;">
            <li class="breadcrumb-item"><a href="<?= base_url('marketplaceListing') ?>">Marketplace</a></li>
            <li class="breadcrumb-item active">Categories</li>
          </ol>
        </nav>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

      <!-- Categories List -->
      <div class="card-box" style="padding:0;overflow:hidden;">
        <div style="padding:18px 22px;border-bottom:1px solid var(--border);">
          <h5 style="margin:0;font-size:.95rem;font-weight:700;color:var(--t1);">
            All Categories <span style="font-size:.8rem;font-weight:400;color:var(--t3);">(<?= count($categories) ?>)</span>
          </h5>
        </div>
        <?php if (empty($categories)): ?>
          <div style="padding:40px;text-align:center;font-size:.875rem;color:var(--t3);">No categories yet.</div>
        <?php else: ?>
          <table class="table" style="margin:0;">
            <thead style="background:#f8fafc;">
              <tr>
                <th style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);padding:10px 18px;border-top:none;">#</th>
                <th style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);padding:10px 18px;border-top:none;">Name</th>
                <th style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);padding:10px 18px;border-top:none;">Description</th>
                <th style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);padding:10px 18px;border-top:none;width:80px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; foreach ($categories as $cat): ?>
                <tr>
                  <td style="padding:12px 18px;font-size:.875rem;color:var(--t3);vertical-align:middle;"><?= $i++ ?></td>
                  <td style="padding:12px 18px;font-size:.875rem;font-weight:600;color:var(--t1);vertical-align:middle;"><?= esc($cat->name) ?></td>
                  <td style="padding:12px 18px;font-size:.875rem;color:var(--t2);vertical-align:middle;"><?= esc($cat->description ?: '—') ?></td>
                  <td style="padding:12px 18px;vertical-align:middle;text-align:right;">
                    <a href="<?= base_url('deleteMarketplaceCategory/' . $cat->id) ?>"
                       onclick="return confirm('Delete this category?')"
                       style="color:#ef4444;font-size:.8rem;text-decoration:none;">
                      <i class="dw dw-delete-3"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <!-- Add Category Form -->
      <div class="card-box" style="padding:24px;">
        <h5 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 18px;">Add Category</h5>
        <form action="<?= base_url('saveNewMarketplaceCategory') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-group">
            <label>Category Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Kids & Baby" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" class="form-control" placeholder="Optional short description">
          </div>
          <button type="submit" class="btn btn-primary btn-block">
            <i class="dw dw-add" style="margin-right:6px;"></i>Add Category
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
