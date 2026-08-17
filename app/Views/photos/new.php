<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['new_photos'] ?></h1>
        <nav class="np-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('photos') ?>"><?= $locale['photo_gallery'] ?></a>
          <span>/</span>
          <span><?= $locale['new_photos'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('photos') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow1" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form method="POST" action="<?= base_url('savenewphoto') ?>" enctype="multipart/form-data" id="photo_upload_form">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        <!-- Left: Drop zone -->
        <div>
          <div class="card-box np-card">
            <div class="np-card-header">
              <div class="np-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-photo-1"></i>
              </div>
              <div>
                <h3 class="np-card-title">Upload Photos</h3>
                <p class="np-card-sub">Drag and drop or click to select — up to 20 photos</p>
              </div>
            </div>

            <!-- Dropzone — class "dropzone" is required by footer.php init -->
            <div id="myDrop" class="dropzone np-dropzone">
              <div class="dz-message">
                <div class="np-dz-icon">
                  <i class="dw dw-upload"></i>
                </div>
                <p class="np-dz-text"><?= $locale['drag_and_drop'] ?></p>
                <p class="np-dz-hint">JPG, PNG, GIF — max 100 MB each</p>
              </div>
              <div class="fallback">
                <input name="file[]" type="file" multiple>
              </div>
            </div>

          </div>
        </div>

        <!-- Right: Album info -->
        <div>
          <div class="card-box np-card">
            <div class="np-card-header">
              <div class="np-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-tag"></i>
              </div>
              <div>
                <h3 class="np-card-title">Album Info</h3>
                <p class="np-card-sub">Title and description for this album</p>
              </div>
            </div>

            <div class="np-field">
              <label class="np-label"><?= $locale['photo_title'] ?> <span class="np-required">*</span></label>
              <input type="text" class="np-input" id="title" name="title"
                     placeholder="<?= $locale['photo_title'] ?>" required>
            </div>

            <div class="np-field">
              <label class="np-label"><?= $locale['photo_desc'] ?></label>
              <textarea class="np-input np-textarea" id="description" name="description"
                        placeholder="<?= $locale['photo_desc'] ?>" rows="4"></textarea>
            </div>

            <div class="np-info-box">
              <i class="dw dw-information" style="color:#6366f1;flex-shrink:0;"></i>
              <span>Add your title, select photos, then click <strong>Upload</strong>.</span>
            </div>
          </div>

          <!-- Submit -->
          <div style="display:flex;flex-direction:column;gap:10px;">
            <button id="submit" onclick="uploadphotos(event)" type="submit"
                    class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:12px;width:100%;font-size:.9rem;">
              <i class="dw dw-upload" style="margin-right:8px;"></i><?= $locale['upload_photos'] ?>
            </button>
            <a href="<?= base_url('photos') ?>" class="btn btn-outline-secondary"
               style="border-radius:8px;font-weight:600;padding:11px;width:100%;text-align:center;font-size:.9rem;">
              Cancel
            </a>
          </div>
        </div>

      </div>

    </form>

  </div>
</div>

<style>
  .np-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .np-breadcrumb a { color:var(--t3);text-decoration:none; }
  .np-breadcrumb a:hover { color:var(--accent); }
  .np-breadcrumb span { margin:0 5px; }

  .np-card { padding:22px;margin-bottom:20px; }
  .np-card-header { display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border); }
  .np-card-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0; }
  .np-card-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0; }
  .np-card-sub { font-size:.75rem;color:var(--t3);margin:2px 0 0; }

  .np-field { margin-bottom:18px; }
  .np-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em; }
  .np-required { color:#ef4444; }

  .np-input {
    width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
  }
  .np-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .np-input::placeholder { color:var(--t3); }
  .np-textarea { resize:vertical;min-height:90px; }

  .np-info-box {
    display:flex;align-items:flex-start;gap:10px;padding:12px 14px;
    background:#eef2ff;border-radius:8px;font-size:.8rem;color:#3730a3;
    border:1px solid #c7d2fe;
  }

  /* Dropzone overrides */
  .np-dropzone {
    border:2px dashed var(--border) !important;
    border-radius:12px !important;
    background:#fafafa !important;
    min-height:280px !important;
    transition:border-color .15s,background .15s !important;
  }
  .np-dropzone:hover,
  .np-dropzone.dz-drag-hover { border-color:var(--accent) !important;background:#f0f4ff !important; }
  .np-dropzone .dz-message { display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:40px 20px; }
  .np-dz-icon {
    width:56px;height:56px;border-radius:14px;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:1.4rem;margin-bottom:14px;
  }
  .np-dz-text { font-size:1rem;font-weight:700;color:var(--t1);margin:0 0 6px; }
  .np-dz-hint { font-size:.8rem;color:var(--t3);margin:0; }

  /* Dropzone preview thumbnails */
  .np-dropzone .dz-preview .dz-image { border-radius:8px !important; }
  .np-dropzone .dz-preview .dz-remove { font-size:.72rem !important; }
</style>
