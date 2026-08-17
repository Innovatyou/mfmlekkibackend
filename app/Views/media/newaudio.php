<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">New Audio</h1>
        <nav class="na-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('audios') ?>"><?= $locale['audios'] ?></a>
          <span>/</span>
          <span>New Audio</span>
        </nav>
      </div>
      <a href="<?= base_url('audios') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow1" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form id="upload-form">

      <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

        <!-- Left column -->
        <div>

          <!-- Audio details card -->
          <div class="card-box na-card">
            <div class="na-card-header">
              <div class="na-card-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="dw dw-music-note"></i>
              </div>
              <div>
                <h3 class="na-card-title">Audio Details</h3>
                <p class="na-card-sub">Title, description and duration</p>
              </div>
            </div>

            <div class="na-field">
              <label class="na-label"><?= $locale['audio_title_ex'] ?> <span class="na-required">*</span></label>
              <input type="text" class="na-input" id="title" placeholder="<?= $locale['audio_title'] ?>">
            </div>

            <div class="na-field">
              <label class="na-label"><?= $locale['audio_desc_ex'] ?></label>
              <textarea class="na-input na-textarea" id="description" placeholder="<?= $locale['audio_desc'] ?>" rows="3"></textarea>
            </div>

            <div class="na-field">
              <label class="na-label"><?= $locale['audio_duration_ex'] ?> <span class="na-required">*</span></label>
              <div style="position:relative;">
                <span class="na-input-icon"><i class="dw dw-clock"></i></span>
                <input type="text" class="na-input na-input-with-icon" id="duration" name="duration" placeholder="<?= $locale['audio_duration'] ?>">
              </div>
            </div>
          </div>

          <!-- Source type card -->
          <div class="card-box na-card">
            <div class="na-card-header">
              <div class="na-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-network"></i>
              </div>
              <div>
                <h3 class="na-card-title"><?= $locale['audio_type'] ?></h3>
                <p class="na-card-sub">Upload a file or provide a stream link</p>
              </div>
            </div>

            <!-- Type selector pills -->
            <div class="na-type-pills">
              <button type="button" class="na-type-pill active" data-val="0" onclick="setAudioType(0,this)">
                <i class="dw dw-upload"></i> <?= $locale['upload_audio_file'] ?>
              </button>
              <button type="button" class="na-type-pill" data-val="1" onclick="setAudioType(1,this)">
                <i class="dw dw-link"></i> <?= $locale['provide_audio_link'] ?>
              </button>
            </div>
            <select id="audio_type" style="display:none;">
              <option value="0" selected><?= $locale['upload_audio_file'] ?></option>
              <option value="1"><?= $locale['provide_audio_link'] ?></option>
            </select>

            <!-- Upload section -->
            <div id="upload_div" style="margin-top:18px;">
              <div class="na-field">
                <label class="na-label"><?= $locale['media_cov'] ?></label>
                <div class="na-upload-zone" id="thumb_zone_a" onclick="document.getElementById('thumbnail').click()">
                  <div class="na-upload-placeholder" id="thumb_placeholder_a">
                    <i class="dw dw-photo-1" style="font-size:1.5rem;color:#94a3b8;margin-bottom:6px;"></i>
                    <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to add cover art</p>
                    <p style="font-size:.72rem;color:var(--t3);margin:3px 0 0;">JPG, PNG</p>
                  </div>
                  <img id="thumb_preview_a" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;" src="" alt="">
                </div>
                <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewAudioThumb(this)">
                <button type="button" id="remove_thumb_a" class="na-remove-btn" style="display:none;" onclick="clearAudioThumb()">
                  <i class="dw dw-trash" style="margin-right:4px;"></i>Remove cover
                </button>
              </div>

              <div class="na-field">
                <label class="na-label"><?= $locale['mp3_file'] ?> <span class="na-required">*</span></label>
                <div class="na-upload-zone na-upload-zone-audio" id="audio_zone" onclick="document.getElementById('audio-file').click()">
                  <div class="na-upload-placeholder" id="audio_placeholder">
                    <i class="dw dw-music-note" style="font-size:1.5rem;color:#94a3b8;margin-bottom:6px;"></i>
                    <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to select MP3 file</p>
                    <p style="font-size:.72rem;color:var(--t3);margin:3px 0 0;">MP3 — max 100 MB</p>
                  </div>
                  <div id="audio_selected" style="display:none;padding:10px;text-align:center;">
                    <i class="dw dw-music-note" style="font-size:1.5rem;color:#f59e0b;"></i>
                    <p id="audio_name" style="font-size:.8rem;font-weight:600;color:var(--t1);margin:6px 0 2px;"></p>
                    <p id="audio_size" style="font-size:.72rem;color:var(--t3);margin:0;"></p>
                  </div>
                </div>
                <input type="file" id="audio-file" name="mp3" accept=".mp3" style="display:none;" onchange="previewAudioFile(this)">
                <button type="button" id="remove_audio" class="na-remove-btn" style="display:none;" onclick="clearAudioFile()">
                  <i class="dw dw-trash" style="margin-right:4px;"></i>Remove audio
                </button>
              </div>
            </div>

            <!-- Link section -->
            <div id="link_div" style="display:none;margin-top:18px;">
              <div class="na-field">
                <label class="na-label"><?= $locale['cover_link'] ?></label>
                <input type="url" class="na-input" id="thumbnail_link" placeholder="<?= $locale['cover_link'] ?>">
              </div>
              <div class="na-field">
                <label class="na-label"><?= $locale['audio_link'] ?> <span class="na-required">*</span></label>
                <div style="position:relative;">
                  <span class="na-input-icon"><i class="dw dw-link"></i></span>
                  <input type="url" class="na-input na-input-with-icon" id="media_link" placeholder="<?= $locale['audio_link'] ?>">
                </div>
              </div>
            </div>

          </div>

        </div>

        <!-- Right column: settings -->
        <div>
          <div class="card-box na-card">
            <div class="na-card-header">
              <div class="na-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-settings"></i>
              </div>
              <div>
                <h3 class="na-card-title">Settings</h3>
                <p class="na-card-sub">Permissions and options</p>
              </div>
            </div>

            <div class="na-setting-row">
              <div>
                <div style="font-size:.875rem;font-weight:600;color:var(--t1);"><?= $locale['allow_vid_down'] ?></div>
                <div style="font-size:.75rem;color:var(--t3);margin-top:2px;"><?= $locale['download_availability'] ?></div>
              </div>
              <label class="na-toggle-switch">
                <input type="checkbox" id="download_toggle_a" onchange="syncAudioDownload(this)">
                <span class="na-toggle-slider"></span>
              </label>
            </div>

            <!-- Hidden selects that common.js reads -->
            <select id="can_download" style="display:none;">
              <option value="0">Yes</option>
              <option value="1" selected>No</option>
            </select>
            <select id="is_free" style="display:none;">
              <option value="0" selected>Yes</option>
              <option value="1">No</option>
            </select>
            <select id="can_preview" style="display:none;">
              <option value="0">Yes</option>
              <option value="1">No</option>
            </select>
          </div>

          <!-- Tips card -->
          <div class="card-box na-card" style="background:#f8fafc;">
            <p style="font-size:.75rem;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.05em;margin:0 0 12px;">Before uploading</p>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                Audio must be in MP3 format for file uploads
              </li>
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                Duration format: <code style="font-size:.75rem;">01:30:00</code> or <code style="font-size:.75rem;">00:45:00</code>
              </li>
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                For a link upload, paste the full MP3 stream URL
              </li>
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                Max file size: 100 MB
              </li>
            </ul>
          </div>
        </div>

      </div>

      <!-- Submit bar -->
      <div class="na-submit-bar">
        <a href="<?= base_url('audios') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:10px 24px;">Cancel</a>
        <button id="submit" onclick="uploadNewAudio(event)" type="submit" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:10px 28px;">
          <i class="dw dw-upload" style="margin-right:6px;"></i><?= $locale['new_audio_upload'] ?>
        </button>
        <div id="loader" style="display:none;align-items:center;gap:10px;font-size:.875rem;color:var(--t3);">
          <span class="na-spinner"></span><?= $locale['processing'] ?>
        </div>
      </div>

    </form>

  </div>
</div>

<style>
  .na-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .na-breadcrumb a { color:var(--t3);text-decoration:none; }
  .na-breadcrumb a:hover { color:var(--accent); }
  .na-breadcrumb span { margin:0 5px; }

  .na-card { padding:22px;margin-bottom:20px; }
  .na-card-header { display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border); }
  .na-card-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0; }
  .na-card-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0; }
  .na-card-sub { font-size:.75rem;color:var(--t3);margin:2px 0 0; }

  .na-field { margin-bottom:18px; }
  .na-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em; }
  .na-required { color:#ef4444; }

  .na-input {
    width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
  }
  .na-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .na-input::placeholder { color:var(--t3); }
  .na-textarea { resize:vertical;min-height:80px; }
  .na-input-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none; }
  .na-input-with-icon { padding-left:36px; }

  .na-type-pills { display:flex;gap:8px;flex-wrap:wrap; }
  .na-type-pill {
    display:inline-flex;align-items:center;gap:6px;padding:7px 14px;
    border:1.5px solid var(--border);border-radius:8px;background:#fff;
    font-size:.8rem;font-weight:600;color:var(--t2);cursor:pointer;transition:all .15s;
  }
  .na-type-pill:hover { border-color:var(--accent);color:var(--accent); }
  .na-type-pill.active { border-color:var(--accent);background:#eef2ff;color:var(--accent); }

  .na-upload-zone {
    border:2px dashed var(--border);border-radius:10px;padding:24px 16px;
    text-align:center;cursor:pointer;transition:border-color .15s,background .15s;
    min-height:90px;display:flex;align-items:center;justify-content:center;overflow:hidden;
  }
  .na-upload-zone:hover { border-color:var(--accent);background:#f8fafc; }
  .na-upload-zone-audio { min-height:80px; }
  .na-upload-placeholder { display:flex;flex-direction:column;align-items:center; }
  .na-remove-btn {
    display:block;width:100%;margin-top:8px;padding:7px;border:1.5px solid #fecaca;
    background:#fef2f2;color:#ef4444;border-radius:8px;font-size:.8rem;font-weight:600;
    cursor:pointer;transition:all .15s;
  }
  .na-remove-btn:hover { background:#ef4444;color:#fff;border-color:#ef4444; }

  .na-setting-row { display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border); }
  .na-setting-row:last-of-type { border-bottom:none; }

  .na-toggle-switch { position:relative;display:inline-block;width:38px;height:22px;cursor:pointer; }
  .na-toggle-switch input { opacity:0;width:0;height:0; }
  .na-toggle-slider { position:absolute;inset:0;background:#e2e8f0;border-radius:22px;transition:background .2s; }
  .na-toggle-slider::before {
    content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;
    background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);
  }
  .na-toggle-switch input:checked + .na-toggle-slider { background:var(--accent); }
  .na-toggle-switch input:checked + .na-toggle-slider::before { transform:translateX(16px); }

  .na-submit-bar { display:flex;align-items:center;justify-content:flex-end;gap:12px;padding:16px 0 8px;border-top:1px solid var(--border); }
  .na-spinner {
    display:inline-block;width:14px;height:14px;border:2px solid #d1d5db;
    border-top-color:var(--accent);border-radius:50%;animation:na-spin .6s linear infinite;
  }
  @keyframes na-spin { to { transform:rotate(360deg); } }
</style>

<script>
function setAudioType(val, btn) {
  document.getElementById('audio_type').value = val;
  document.querySelectorAll('.na-type-pill').forEach(function(p){ p.classList.remove('active'); });
  btn.classList.add('active');
  var isUpload = (val == 0);
  document.getElementById('upload_div').style.display = isUpload ? 'block' : 'none';
  document.getElementById('link_div').style.display   = isUpload ? 'none'  : 'block';
}

function syncAudioDownload(cb) {
  document.getElementById('can_download').value = cb.checked ? '0' : '1';
}

function previewAudioThumb(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var img = document.getElementById('thumb_preview_a');
    img.src = e.target.result;
    img.style.display = 'block';
    document.getElementById('thumb_placeholder_a').style.display = 'none';
    document.getElementById('remove_thumb_a').style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}

function clearAudioThumb() {
  document.getElementById('thumbnail').value = '';
  document.getElementById('thumb_preview_a').style.display = 'none';
  document.getElementById('thumb_placeholder_a').style.display = 'flex';
  document.getElementById('remove_thumb_a').style.display = 'none';
}

function previewAudioFile(input) {
  if (!input.files || !input.files[0]) return;
  var f = input.files[0];
  var mb = (f.size / 1024 / 1024).toFixed(1);
  document.getElementById('audio_placeholder').style.display = 'none';
  document.getElementById('audio_selected').style.display = 'block';
  document.getElementById('audio_name').textContent = f.name;
  document.getElementById('audio_size').textContent = mb + ' MB';
  document.getElementById('remove_audio').style.display = 'block';
}

function clearAudioFile() {
  document.getElementById('audio-file').value = '';
  document.getElementById('audio_placeholder').style.display = 'flex';
  document.getElementById('audio_selected').style.display = 'none';
  document.getElementById('remove_audio').style.display = 'none';
}
</script>
