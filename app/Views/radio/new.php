<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['new_radio'] ?></h1>
        <nav class="nr-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('radio') ?>"><?= $locale['radio_stations'] ?></a>
          <span>/</span>
          <span><?= $locale['new_radio'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('radio') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow1" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form method="POST" action="<?= base_url('savenewradio') ?>" enctype="multipart/form-data" id="new_radio_form">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        <!-- Left: Station details -->
        <div>
          <div class="card-box nr-card">
            <div class="nr-card-header">
              <div class="nr-card-icon" style="background:linear-gradient(135deg,#ef4444,#f97316);">
                <i class="dw dw-signal"></i>
              </div>
              <div>
                <h3 class="nr-card-title">Station Details</h3>
                <p class="nr-card-sub">Title, description and stream link</p>
              </div>
            </div>

            <div class="nr-field">
              <label class="nr-label"><?= $locale['radio_title'] ?> <span class="nr-required">*</span></label>
              <input type="text" name="title" class="nr-input" id="station_title"
                     placeholder="e.g. Church FM"
                     oninput="updateRadioPreview(this.value)" required>
            </div>

            <div class="nr-field">
              <label class="nr-label"><?= $locale['radio_desc'] ?> <span class="nr-required">*</span></label>
              <input type="text" name="description" class="nr-input"
                     placeholder="Short description of this radio station" required>
            </div>

            <div class="nr-field">
              <label class="nr-label"><?= $locale['radio_link'] ?> <span class="nr-required">*</span></label>
              <div style="position:relative;">
                <span class="nr-input-icon"><i class="dw dw-link"></i></span>
                <input type="text" name="link" class="nr-input nr-input-with-icon"
                       placeholder="Paste your radio stream URL" required>
              </div>
            </div>

            <div class="nr-field">
              <label class="nr-label"><?= $locale['radio_status'] ?> <span class="nr-required">*</span></label>
              <div class="nr-select-wrap">
                <select name="status" class="nr-select" required onchange="updateRadioStatus(this.value)">
                  <option value="0"><?= $locale['live'] ?></option>
                  <option value="1" selected><?= $locale['not_live'] ?></option>
                </select>
                <i class="dw dw-down-arrow nr-select-icon"></i>
              </div>
              <p class="nr-hint">Visible to app users when set to Live</p>
            </div>

          </div>
        </div>

        <!-- Right: Cover photo + preview -->
        <div>

          <!-- App preview card -->
          <div class="card-box nr-card" style="margin-bottom:20px;">
            <div class="nr-card-header">
              <div class="nr-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-eye"></i>
              </div>
              <div>
                <h3 class="nr-card-title">Preview</h3>
                <p class="nr-card-sub">How it appears in the app</p>
              </div>
            </div>
            <div class="nr-preview-box">
              <div class="nr-preview-thumb" id="radio_preview_thumb">
                <i class="dw dw-signal" style="font-size:2rem;color:#94a3b8;"></i>
              </div>
              <div style="padding:12px 14px;">
                <div class="nr-preview-title" id="radio_preview_title">Station Name</div>
                <div id="radio_preview_status">
                  <span class="nr-status-badge nr-status-offline"><span class="nr-status-dot nr-dot-offline"></span>Offline</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Cover photo card -->
          <div class="card-box nr-card">
            <div class="nr-card-header">
              <div class="nr-card-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="dw dw-photo-1"></i>
              </div>
              <div>
                <h3 class="nr-card-title"><?= $locale['radio_cover'] ?></h3>
                <p class="nr-card-sub">Station logo or artwork</p>
              </div>
            </div>
            <div class="nr-upload-zone" id="radio_upload_zone" onclick="document.getElementById('radio_thumb_input').click()">
              <div class="nr-upload-placeholder" id="radio_upload_placeholder">
                <i class="dw dw-upload" style="font-size:1.8rem;color:#94a3b8;margin-bottom:8px;"></i>
                <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to upload cover image</p>
                <p style="font-size:.72rem;color:var(--t3);margin:4px 0 0;">JPG, PNG — max 10 MB</p>
              </div>
              <img id="radio_thumb_preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;">
            </div>
            <input type="file" name="thumbnail" id="radio_thumb_input" accept=".jpg,.jpeg,.png"
                   style="display:none;" onchange="previewRadioThumb(this)">
            <button type="button" id="radio_remove_thumb" style="display:none;"
                    onclick="removeRadioThumb()" class="nr-remove-btn">
              <i class="dw dw-trash" style="margin-right:4px;"></i>Remove image
            </button>
          </div>

        </div>
      </div>

      <!-- Submit bar -->
      <div class="nr-submit-bar">
        <a href="<?= base_url('radio') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:10px 24px;">Cancel</a>
        <button type="submit" class="btn btn-primary" id="radio_submit_btn" style="border-radius:8px;font-weight:600;padding:10px 28px;">
          <span id="radio_submit_label"><i class="dw dw-add" style="margin-right:6px;"></i><?= $locale['save_new'] ?></span>
          <span id="radio_submit_spinner" style="display:none;">
            <span class="nr-spinner"></span>Saving…
          </span>
        </button>
      </div>

    </form>

  </div>
</div>

<style>
  .nr-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .nr-breadcrumb a { color:var(--t3);text-decoration:none; }
  .nr-breadcrumb a:hover { color:var(--accent); }
  .nr-breadcrumb span { margin:0 5px; }

  .nr-card { padding:22px;margin-bottom:20px; }
  .nr-card-header { display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border); }
  .nr-card-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0; }
  .nr-card-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0; }
  .nr-card-sub { font-size:.75rem;color:var(--t3);margin:2px 0 0; }

  .nr-field { margin-bottom:18px; }
  .nr-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em; }
  .nr-required { color:#ef4444; }
  .nr-hint { font-size:.75rem;color:var(--t3);margin:5px 0 0; }

  .nr-input {
    width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
  }
  .nr-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nr-input::placeholder { color:var(--t3); }
  .nr-input-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none; }
  .nr-input-with-icon { padding-left:36px; }

  .nr-select-wrap { position:relative; }
  .nr-select {
    width:100%;padding:10px 36px 10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;appearance:none;
    transition:border-color .15s;cursor:pointer;box-sizing:border-box;
  }
  .nr-select:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nr-select-icon { position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.7rem;pointer-events:none; }

  .nr-preview-box { border:1.5px solid var(--border);border-radius:10px;overflow:hidden; }
  .nr-preview-thumb {
    width:100%;height:120px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
    display:flex;align-items:center;justify-content:center;overflow:hidden;
  }
  .nr-preview-title { font-size:.875rem;font-weight:700;color:var(--t1);margin-bottom:6px; }

  .nr-status-badge { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600; }
  .nr-status-dot { width:6px;height:6px;border-radius:50%; }
  .nr-status-live    { background:#ecfdf5;color:#065f46; }
  .nr-dot-live       { background:#10b981; }
  .nr-status-offline { background:#f1f5f9;color:var(--t2); }
  .nr-dot-offline    { background:#94a3b8; }

  .nr-upload-zone {
    border:2px dashed var(--border);border-radius:10px;padding:28px 16px;
    text-align:center;cursor:pointer;transition:border-color .15s,background .15s;
    min-height:110px;display:flex;align-items:center;justify-content:center;overflow:hidden;
  }
  .nr-upload-zone:hover { border-color:var(--accent);background:#f8fafc; }
  .nr-upload-placeholder { display:flex;flex-direction:column;align-items:center; }
  .nr-remove-btn {
    display:block;width:100%;margin-top:8px;padding:7px;border:1.5px solid #fecaca;
    background:#fef2f2;color:#ef4444;border-radius:8px;font-size:.8rem;font-weight:600;
    cursor:pointer;transition:all .15s;
  }
  .nr-remove-btn:hover { background:#ef4444;color:#fff;border-color:#ef4444; }

  .nr-submit-bar { display:flex;justify-content:flex-end;gap:12px;padding:16px 0 8px;border-top:1px solid var(--border); }
  .nr-spinner {
    display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.4);
    border-top-color:#fff;border-radius:50%;animation:nr-spin .6s linear infinite;margin-right:8px;
  }
  @keyframes nr-spin { to { transform:rotate(360deg); } }
</style>

<script>
function updateRadioPreview(val) {
  document.getElementById('radio_preview_title').textContent = val.trim() || 'Station Name';
}

function updateRadioStatus(val) {
  var el = document.getElementById('radio_preview_status');
  if (!el) return;
  if (val === '0') {
    el.innerHTML = '<span class="nr-status-badge nr-status-live"><span class="nr-status-dot nr-dot-live"></span>Live</span>';
  } else {
    el.innerHTML = '<span class="nr-status-badge nr-status-offline"><span class="nr-status-dot nr-dot-offline"></span>Offline</span>';
  }
}

function previewRadioThumb(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var img = document.getElementById('radio_thumb_preview');
    img.src = e.target.result;
    img.style.display = 'block';
    document.getElementById('radio_upload_placeholder').style.display = 'none';
    document.getElementById('radio_preview_thumb').innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
    document.getElementById('radio_remove_thumb').style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}

function removeRadioThumb() {
  document.getElementById('radio_thumb_input').value = '';
  document.getElementById('radio_thumb_preview').style.display = 'none';
  document.getElementById('radio_upload_placeholder').style.display = 'flex';
  document.getElementById('radio_remove_thumb').style.display = 'none';
  document.getElementById('radio_preview_thumb').innerHTML = '<i class="dw dw-signal" style="font-size:2rem;color:#94a3b8;"></i>';
}

document.getElementById('new_radio_form').addEventListener('submit', function() {
  document.getElementById('radio_submit_label').style.display = 'none';
  document.getElementById('radio_submit_spinner').style.display = 'inline-flex';
  document.getElementById('radio_submit_btn').disabled = true;
});
</script>
