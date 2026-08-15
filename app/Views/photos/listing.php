<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['photo_gallery'] ?></h1>
        <nav class="pg-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <span><?= $locale['photo_gallery'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('newPhotos') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-add" style="margin-right:6px;"></i><?= $locale['new_photos'] ?>
      </a>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="pg-alert pg-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="pg-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="pg-alert pg-alert-danger">
        <i class="dw dw-close-circle-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="pg-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <?php if (empty($photos)): ?>
      <!-- Empty state -->
      <div class="card-box" style="padding:60px 24px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
          <i class="dw dw-photo-1" style="color:#fff;font-size:1.6rem;"></i>
        </div>
        <h3 style="font-size:1.1rem;font-weight:700;color:var(--t1);margin:0 0 8px;">No photo albums yet</h3>
        <p style="font-size:.875rem;color:var(--t3);margin:0 0 20px;">Upload your first album to get started</p>
        <a href="<?= base_url('newPhotos') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;">
          <i class="dw dw-add" style="margin-right:6px;"></i>Upload Photos
        </a>
      </div>

    <?php else: ?>
      <!-- Albums grid -->
      <div class="pg-grid">
        <?php foreach ($photos as $record): ?>
          <div class="card-box pg-album-card">

            <!-- Thumbnail strip -->
            <div class="pg-thumb-strip">
              <?php
                $thumbs = is_array($record->thumbnail) ? $record->thumbnail : [];
                $shown  = array_slice($thumbs, 0, 4);
                $extra  = count($thumbs) - count($shown);
              ?>
              <?php if (empty($shown)): ?>
                <div class="pg-thumb-placeholder">
                  <i class="dw dw-photo-1" style="font-size:1.8rem;color:#94a3b8;"></i>
                </div>
              <?php elseif (count($shown) === 1): ?>
                <img src="<?= esc($shown[0]) ?>" class="pg-thumb-single" alt="">
              <?php else: ?>
                <div class="pg-thumb-grid pg-thumb-grid-<?= min(count($shown), 4) ?>">
                  <?php foreach ($shown as $img): ?>
                    <img src="<?= esc($img) ?>" class="pg-thumb-img" alt="">
                  <?php endforeach; ?>
                  <?php if ($extra > 0): ?>
                    <div class="pg-thumb-more">+<?= $extra ?></div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Album info -->
            <div class="pg-album-body">
              <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                <div>
                  <div class="pg-album-title"><?= esc($record->title) ?></div>
                  <div class="pg-album-count"><?= count($thumbs) ?> photo<?= count($thumbs) !== 1 ? 's' : '' ?></div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0;">
                  <a href="<?= base_url('editPhoto/' . $record->id) ?>" class="pg-action-btn pg-action-edit" title="Edit">
                    <i class="dw dw-edit-2"></i>
                  </a>
                  <a href="javascript:void(0)" class="pg-action-btn pg-action-delete" title="Delete"
                     onclick="confirmDeletePhoto(<?= $record->id ?>)">
                    <i class="dw dw-trash"></i>
                  </a>
                </div>
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<style>
  .pg-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .pg-breadcrumb a { color:var(--t3);text-decoration:none; }
  .pg-breadcrumb a:hover { color:var(--accent); }
  .pg-breadcrumb span { margin:0 5px; }

  .pg-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .pg-alert i { font-size:1.1rem;flex-shrink:0; }
  .pg-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .pg-alert-danger  { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
  .pg-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }
  .pg-alert-close:hover { opacity:1; }

  /* Grid */
  .pg-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:18px;
  }

  .pg-album-card { padding:0;overflow:hidden; }

  /* Thumbnail strip */
  .pg-thumb-strip { width:100%;height:160px;position:relative;overflow:hidden;background:#f1f5f9; }
  .pg-thumb-placeholder { width:100%;height:100%;display:flex;align-items:center;justify-content:center; }
  .pg-thumb-single { width:100%;height:100%;object-fit:cover; }

  .pg-thumb-grid {
    display:grid;width:100%;height:100%;
  }
  .pg-thumb-grid-2 { grid-template-columns:1fr 1fr; }
  .pg-thumb-grid-3 { grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr; }
  .pg-thumb-grid-4 { grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr; }

  .pg-thumb-img { width:100%;height:100%;object-fit:cover;border:1px solid #fff; }
  .pg-thumb-grid-3 .pg-thumb-img:first-child { grid-row:1/3; }

  .pg-thumb-more {
    display:flex;align-items:center;justify-content:center;
    background:rgba(0,0,0,.45);color:#fff;font-size:.875rem;font-weight:700;
    width:100%;height:100%;
  }

  /* Album info */
  .pg-album-body { padding:14px 16px; }
  .pg-album-title { font-size:.9rem;font-weight:700;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
  .pg-album-count { font-size:.75rem;color:var(--t3);margin-top:2px; }

  .pg-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer;
  }
  .pg-action-edit   { background:#fffbeb;color:#d97706; }
  .pg-action-edit:hover   { background:#f59e0b;color:#fff; }
  .pg-action-delete { background:#fef2f2;color:#ef4444; }
  .pg-action-delete:hover { background:#ef4444;color:#fff; }
</style>

<script>
function confirmDeletePhoto(id) {
  swal({
    title: 'Delete album?',
    text: 'This will permanently delete the album and all its photos.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, delete'
  }, function () {
    document.location.href = (typeof baseURL !== 'undefined' ? baseURL : '') + '/deletePhoto/' + id;
  });
}
</script>
