<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['new_livestream'] ?></h1>
        <nav class="nl-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('livestreams') ?>"><?= $locale['livestream_channels'] ?></a>
          <span>/</span>
          <span><?= $locale['new_livestream'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('livestreams') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow1" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form method="POST" action="<?= base_url('savenewlivestream') ?>" enctype="multipart/form-data" id="new_livestream_form">
      <?= csrf_field() ?>

      <div class="nl-main-grid">

        <!-- Left: Main details -->
        <div>

          <!-- Stream info card -->
          <div class="card-box nl-card">
            <div class="nl-card-header">
              <div class="nl-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-video-camera"></i>
              </div>
              <div>
                <h3 class="nl-card-title">Stream Details</h3>
                <p class="nl-card-sub">Title, description and stream link</p>
              </div>
            </div>

            <div class="nl-field">
              <label class="nl-label"><?= $locale['livestream_title'] ?> <span class="nl-required">*</span></label>
              <input type="text" name="title" class="nl-input" id="stream_title"
                     placeholder="e.g. Sunday Morning Service" required
                     oninput="updatePreview(this.value)">
            </div>

            <div class="nl-field">
              <label class="nl-label"><?= $locale['livestream_desc'] ?></label>
              <input type="text" name="description" class="nl-input"
                     placeholder="Brief description of this stream">
            </div>

            <div class="nl-grid-2">
              <div class="nl-field">
                <label class="nl-label"><?= $locale['livestream_src'] ?> <span class="nl-required">*</span></label>
                <div class="nl-select-wrap">
                  <select name="source" class="nl-select" required id="source_select" onchange="updateSourceHint(this.value)">
                    <option value="youtube">YouTube Live</option>
                    <option value="facebook">Facebook Live</option>
                    <option value="m3u8">M3U8 Stream</option>
                    <option value="rtmp">RTMP</option>
                  </select>
                  <i class="dw dw-down-arrow nl-select-icon"></i>
                </div>
                <p class="nl-hint" id="source_hint">Enter the YouTube Live video ID</p>
              </div>

              <div class="nl-field">
                <label class="nl-label"><?= $locale['livestream_status'] ?> <span class="nl-required">*</span></label>
                <div class="nl-select-wrap">
                  <select name="status" class="nl-select" required>
                    <option value="0"><?= $locale['live'] ?></option>
                    <option value="1" selected><?= $locale['not_live'] ?></option>
                  </select>
                  <i class="dw dw-down-arrow nl-select-icon"></i>
                </div>
                <p class="nl-hint">Visible to app users when set to Live</p>
              </div>
            </div>

            <div class="nl-field">
              <label class="nl-label"><?= $locale['livestream_link'] ?> <span class="nl-required">*</span></label>
              <div style="position:relative;">
                <span class="nl-input-icon"><i class="dw dw-link"></i></span>
                <input type="text" name="link" class="nl-input nl-input-with-icon" id="stream_link"
                       placeholder="Paste your stream URL or video ID here" required>
              </div>
            </div>

          </div>

        </div>

        <!-- Right: Cover photo + preview -->
        <div>

          <!-- Preview card -->
          <div class="card-box nl-card" style="margin-bottom:20px;">
            <div class="nl-card-header">
              <div class="nl-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-eye"></i>
              </div>
              <div>
                <h3 class="nl-card-title">Preview</h3>
                <p class="nl-card-sub">How it appears in the app</p>
              </div>
            </div>
            <div class="nl-preview-box">
              <div class="nl-preview-thumb" id="preview_thumb">
                <i class="dw dw-video-camera" style="font-size:2rem;color:#94a3b8;"></i>
              </div>
              <div style="padding:12px 14px;">
                <div class="nl-preview-title" id="preview_title">Stream Title</div>
                <div class="nl-preview-status" id="preview_status_badge">
                  <span class="nl-status-badge nl-status-offline"><span class="nl-status-dot nl-dot-offline"></span>Offline</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Cover photo card -->
          <div class="card-box nl-card">
            <div class="nl-card-header">
              <div class="nl-card-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="dw dw-photo-1"></i>
              </div>
              <div>
                <h3 class="nl-card-title">Cover Photo</h3>
                <p class="nl-card-sub">Optional thumbnail image</p>
              </div>
            </div>
            <div class="nl-upload-zone" id="upload_zone" onclick="document.getElementById('thumb_input').click()">
              <div class="nl-upload-content" id="upload_placeholder">
                <i class="dw dw-upload" style="font-size:1.8rem;color:#94a3b8;margin-bottom:8px;"></i>
                <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to upload cover photo</p>
                <p style="font-size:.72rem;color:var(--t3);margin:4px 0 0;">JPG, PNG — max 10 MB</p>
              </div>
              <img id="thumb_preview_img" class="nl-upload-preview" src="" alt="" style="display:none;">
            </div>
            <input type="file" name="thumbnail" id="thumb_input" accept=".jpg,.jpeg,.png"
                   style="display:none;" onchange="previewThumb(this)">
            <button type="button" id="remove_thumb_btn" onclick="removeThumb()" style="display:none;"
                    class="nl-remove-btn">
              <i class="dw dw-trash" style="margin-right:4px;"></i>Remove photo
            </button>
          </div>

        </div>
      </div>

      <!-- Submit bar -->
      <div class="nl-submit-bar">
        <a href="<?= base_url('livestreams') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:10px 24px;">Cancel</a>
        <button type="submit" class="btn btn-primary nl-submit-btn" id="submit_btn" style="border-radius:8px;font-weight:600;padding:10px 28px;">
          <span id="submit_label"><i class="dw dw-add" style="margin-right:6px;"></i><?= $locale['save_new'] ?></span>
          <span id="submit_spinner" style="display:none;">
            <span class="nl-spinner"></span>Saving…
          </span>
        </button>
      </div>

    </form>

  </div>
</div>

<style>
  .nl-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .nl-breadcrumb a { color:var(--t3);text-decoration:none; }
  .nl-breadcrumb a:hover { color:var(--accent); }
  .nl-breadcrumb span { margin:0 5px; }

  .nl-main-grid { display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start; }
  .nl-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:16px; }

  .nl-card { padding:22px;margin-bottom:20px; }
  .nl-card-header { display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border); }
  .nl-card-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0; }
  .nl-card-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0; }
  .nl-card-sub { font-size:.75rem;color:var(--t3);margin:2px 0 0; }

  .nl-field { margin-bottom:18px; }
  .nl-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em; }
  .nl-required { color:#ef4444; }
  .nl-hint { font-size:.75rem;color:var(--t3);margin:5px 0 0; }

  .nl-input {
    width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
  }
  .nl-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nl-input::placeholder { color:var(--t3); }

  .nl-input-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none; }
  .nl-input-with-icon { padding-left:36px; }

  .nl-select-wrap { position:relative; }
  .nl-select {
    width:100%;padding:10px 36px 10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;appearance:none;
    transition:border-color .15s;cursor:pointer;box-sizing:border-box;
  }
  .nl-select:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nl-select-icon { position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.7rem;pointer-events:none; }

  /* Preview */
  .nl-preview-box { border:1.5px solid var(--border);border-radius:10px;overflow:hidden; }
  .nl-preview-thumb {
    width:100%;height:120px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
    display:flex;align-items:center;justify-content:center;overflow:hidden;
  }
  .nl-preview-thumb img { width:100%;height:100%;object-fit:cover; }
  .nl-preview-title { font-size:.875rem;font-weight:700;color:var(--t1);margin-bottom:6px; }
  .nl-preview-status { }

  .nl-status-badge { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600; }
  .nl-status-dot { width:6px;height:6px;border-radius:50%; }
  .nl-status-live    { background:#ecfdf5;color:#065f46; }
  .nl-dot-live       { background:#10b981; }
  .nl-status-offline { background:#f1f5f9;color:var(--t2); }
  .nl-dot-offline    { background:#94a3b8; }

  /* Upload zone */
  .nl-upload-zone {
    border:2px dashed var(--border);border-radius:10px;padding:28px 16px;
    text-align:center;cursor:pointer;transition:border-color .15s,background .15s;
    position:relative;overflow:hidden;min-height:120px;
    display:flex;align-items:center;justify-content:center;
  }
  .nl-upload-zone:hover { border-color:var(--accent);background:#f8fafc; }
  .nl-upload-content { display:flex;flex-direction:column;align-items:center; }
  .nl-upload-preview { width:100%;height:120px;object-fit:cover;border-radius:8px; }
  .nl-remove-btn {
    display:block;width:100%;margin-top:8px;padding:7px;border:1.5px solid #fecaca;
    background:#fef2f2;color:#ef4444;border-radius:8px;font-size:.8rem;font-weight:600;
    cursor:pointer;transition:all .15s;
  }
  .nl-remove-btn:hover { background:#ef4444;color:#fff;border-color:#ef4444; }

  /* Submit bar */
  .nl-submit-bar {
    display:flex;justify-content:flex-end;gap:12px;padding:16px 0 8px;
    border-top:1px solid var(--border);margin-top:4px;
  }
  .nl-spinner {
    display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.4);
    border-top-color:#fff;border-radius:50%;animation:nl-spin .6s linear infinite;margin-right:8px;
  }
  @keyframes nl-spin { to { transform:rotate(360deg); } }

  @media(max-width:900px) {
    .nl-main-grid { grid-template-columns:1fr; }
  }
  @media(max-width:576px) {
    .nl-grid-2 { grid-template-columns:1fr; }
    .nl-submit-bar { flex-direction:column-reverse; }
    .nl-submit-bar .btn { width:100%;text-align:center; }
    .page-header { flex-wrap:wrap;gap:12px; }
  }
</style>

<script>
var _sourceHints = {
  youtube:  'Enter the YouTube Live video ID (e.g. dQw4w9WgXcQ)',
  facebook: 'Paste the full Facebook Live embed URL',
  m3u8:     'Paste the full .m3u8 stream URL',
  rtmp:     'Paste the RTMP stream URL'
};

function updateSourceHint(val) {
  document.getElementById('source_hint').textContent = _sourceHints[val] || '';
}

function updatePreview(val) {
  var t = document.getElementById('preview_title');
  t.textContent = val.trim() || 'Stream Title';
}

/* Live/Offline status badge sync */
document.addEventListener('DOMContentLoaded', function () {
  var statusSel = document.querySelector('select[name="status"]');
  if (statusSel) {
    statusSel.addEventListener('change', function () {
      updateStatusBadge(this.value);
    });
    updateStatusBadge(statusSel.value);
  }
});

function updateStatusBadge(val) {
  var el = document.getElementById('preview_status_badge');
  if (!el) return;
  if (val === '0') {
    el.innerHTML = '<span class="nl-status-badge nl-status-live"><span class="nl-status-dot nl-dot-live"></span>Live</span>';
  } else {
    el.innerHTML = '<span class="nl-status-badge nl-status-offline"><span class="nl-status-dot nl-dot-offline"></span>Offline</span>';
  }
}

function previewThumb(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function (e) {
    var img = document.getElementById('thumb_preview_img');
    var placeholder = document.getElementById('upload_placeholder');
    var thumb = document.getElementById('preview_thumb');
    img.src = e.target.result;
    img.style.display = 'block';
    placeholder.style.display = 'none';
    /* Update app preview thumbnail */
    thumb.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
    document.getElementById('remove_thumb_btn').style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}

function removeThumb() {
  document.getElementById('thumb_input').value = '';
  document.getElementById('thumb_preview_img').style.display = 'none';
  document.getElementById('upload_placeholder').style.display = 'flex';
  document.getElementById('remove_thumb_btn').style.display = 'none';
  document.getElementById('preview_thumb').innerHTML = '<i class="dw dw-video-camera" style="font-size:2rem;color:#94a3b8;"></i>';
}

document.getElementById('new_livestream_form').addEventListener('submit', function () {
  document.getElementById('submit_label').style.display = 'none';
  document.getElementById('submit_spinner').style.display = 'inline-flex';
  document.getElementById('submit_btn').disabled = true;
});
</script>
