<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['new_video'] ?></h1>
        <nav class="nv-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('videos') ?>"><?= $locale['videos'] ?></a>
          <span>/</span>
          <span><?= $locale['new_video'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('videos') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow1" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form id="upload-form">

      <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

        <!-- Left column -->
        <div>

          <!-- Video details card -->
          <div class="card-box nv-card">
            <div class="nv-card-header">
              <div class="nv-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-video-camera"></i>
              </div>
              <div>
                <h3 class="nv-card-title">Video Details</h3>
                <p class="nv-card-sub">Title, description and duration</p>
              </div>
            </div>

            <div class="nv-field">
              <label class="nv-label"><?= $locale['video_title_ex'] ?> <span class="nv-required">*</span></label>
              <input type="text" class="nv-input" id="title" placeholder="Enter video title">
            </div>

            <div class="nv-field">
              <label class="nv-label"><?= $locale['vid_desc_ex'] ?></label>
              <textarea class="nv-input nv-textarea" id="description" placeholder="<?= $locale['vid_desc'] ?>" rows="3"></textarea>
            </div>

            <div class="nv-field">
              <label class="nv-label"><?= $locale['vid_dur_ex'] ?> <span class="nv-required">*</span></label>
              <div style="position:relative;">
                <span class="nv-input-icon"><i class="dw dw-clock"></i></span>
                <input type="text" class="nv-input nv-input-with-icon" id="duration" name="duration" placeholder="<?= $locale['vid_dur'] ?>">
              </div>
            </div>
          </div>

          <!-- Source type card -->
          <div class="card-box nv-card">
            <div class="nv-card-header">
              <div class="nv-card-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                <i class="dw dw-network"></i>
              </div>
              <div>
                <h3 class="nv-card-title"><?= $locale['media_type'] ?></h3>
                <p class="nv-card-sub">Choose upload or link source</p>
              </div>
            </div>

            <!-- Type selector pills -->
            <div class="nv-type-pills" id="type_pills">
              <button type="button" class="nv-type-pill active" data-val="mp4_video" onclick="setVideoType('mp4_video',this)">
                <i class="dw dw-upload"></i> Upload MP4
              </button>
              <button type="button" class="nv-type-pill" data-val="video_link" onclick="setVideoType('video_link',this)">
                <i class="dw dw-link"></i> MP4 Link
              </button>
              <button type="button" class="nv-type-pill" data-val="youtube_video" onclick="setVideoType('youtube_video',this)">
                <i class="dw dw-video"></i> YouTube
              </button>
            </div>
            <select id="media_type" style="display:none;">
              <option value="mp4_video" selected>Upload MP4 Video</option>
              <option value="video_link">mp4 video link</option>
              <option value="youtube_video">Youtube video id</option>
            </select>

            <!-- Upload section -->
            <div id="upload_div" style="margin-top:18px;">
              <div class="nv-field">
                <label class="nv-label"><?= $locale['video_cover'] ?></label>
                <div class="nv-upload-zone" id="thumb_zone_v" onclick="document.getElementById('thumbnail').click()">
                  <div class="nv-upload-placeholder" id="thumb_placeholder_v">
                    <i class="dw dw-photo-1" style="font-size:1.5rem;color:#94a3b8;margin-bottom:6px;"></i>
                    <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to add thumbnail</p>
                    <p style="font-size:.72rem;color:var(--t3);margin:3px 0 0;">JPG, PNG</p>
                  </div>
                  <img id="thumb_preview_v" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;" src="" alt="">
                </div>
                <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewFile(this,'thumb_preview_v','thumb_placeholder_v','remove_thumb_v')">
                <button type="button" id="remove_thumb_v" class="nv-remove-btn" style="display:none;" onclick="clearFile('thumbnail','thumb_preview_v','thumb_placeholder_v','remove_thumb_v')">
                  <i class="dw dw-trash" style="margin-right:4px;"></i>Remove thumbnail
                </button>
              </div>

              <div class="nv-field">
                <label class="nv-label">Video File (MP4) <span class="nv-required">*</span></label>
                <div class="nv-upload-zone nv-upload-zone-video" id="video_zone" onclick="document.getElementById('video-file').click()">
                  <div class="nv-upload-placeholder" id="video_placeholder">
                    <i class="dw dw-video-camera" style="font-size:1.5rem;color:#94a3b8;margin-bottom:6px;"></i>
                    <p style="font-size:.8rem;color:var(--t3);margin:0;">Click to select MP4 file</p>
                    <p style="font-size:.72rem;color:var(--t3);margin:3px 0 0;">MP4 — max 100 MB</p>
                  </div>
                  <div id="video_selected" style="display:none;padding:10px;text-align:center;">
                    <i class="dw dw-video-camera" style="font-size:1.5rem;color:#6366f1;"></i>
                    <p class="nv-file-name" id="video_name" style="font-size:.8rem;font-weight:600;color:var(--t1);margin:6px 0 2px;"></p>
                    <p class="nv-file-size" id="video_size" style="font-size:.72rem;color:var(--t3);margin:0;"></p>
                  </div>
                </div>
                <input type="file" id="video-file" name="video" accept=".mp4" style="display:none;" onchange="previewVideoFile(this)">
                <button type="button" id="remove_video" class="nv-remove-btn" style="display:none;" onclick="clearVideoFile()">
                  <i class="dw dw-trash" style="margin-right:4px;"></i>Remove video
                </button>
              </div>
            </div>

            <!-- Link section -->
            <div id="link_div" style="display:none;margin-top:18px;">
              <div class="nv-field">
                <label class="nv-label"><?= $locale['cover_link'] ?></label>
                <input type="url" class="nv-input" id="thumbnail_link" placeholder="<?= $locale['cover_link'] ?>">
                <p id="yt_cover_hint" style="display:none;margin:5px 0 0;font-size:.75rem;color:#6366f1;"><i class="dw dw-check-circle-2"></i> Auto-filled from YouTube video ID</p>
              </div>
              <div class="nv-field">
                <label class="nv-label" id="video-label"><?= $locale['video_lnk'] ?></label>
                <div style="position:relative;">
                  <span class="nv-input-icon"><i class="dw dw-link"></i></span>
                  <input type="text" class="nv-input nv-input-with-icon" id="media_link" placeholder="Paste URL or video ID">
                </div>
              </div>
            </div>

          </div>

        </div>

        <!-- Right column: settings -->
        <div>
          <div class="card-box nv-card">
            <div class="nv-card-header">
              <div class="nv-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-settings"></i>
              </div>
              <div>
                <h3 class="nv-card-title">Settings</h3>
                <p class="nv-card-sub">Permissions and options</p>
              </div>
            </div>

            <!-- Allow download toggle -->
            <div class="nv-setting-row">
              <div>
                <div style="font-size:.875rem;font-weight:600;color:var(--t1);"><?= $locale['allow_vid_down'] ?></div>
                <div style="font-size:.75rem;color:var(--t3);margin-top:2px;"><?= $locale['allow_vid_down_avail'] ?></div>
              </div>
              <label class="nv-toggle-switch">
                <input type="checkbox" id="download_toggle" onchange="syncDownload(this)">
                <span class="nv-toggle-slider"></span>
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

          <!-- Checklist tips -->
          <div class="card-box nv-card" style="background:#f8fafc;">
            <p style="font-size:.75rem;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.05em;margin:0 0 12px;">Before uploading</p>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                Video must be in MP4 format for file uploads
              </li>
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                Duration format: <code style="font-size:.75rem;">01:30:00</code> or <code style="font-size:.75rem;">00:45:00</code>
              </li>
              <li style="display:flex;gap:8px;align-items:flex-start;font-size:.8rem;color:var(--t2);">
                <i class="dw dw-check-circle-2" style="color:#10b981;flex-shrink:0;margin-top:1px;"></i>
                For YouTube, paste the video ID (e.g. <code style="font-size:.75rem;">dQw4w9WgXcQ</code>)
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
      <div class="nv-submit-bar">
        <a href="<?= base_url('videos') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;padding:10px 24px;">Cancel</a>
        <button id="submit" onclick="uploadNewVideo(event)" type="submit" class="btn btn-primary nv-submit-btn" style="border-radius:8px;font-weight:600;padding:10px 28px;">
          <i class="dw dw-upload" style="margin-right:6px;"></i><?= $locale['upload_new_vid'] ?>
        </button>
        <div id="loader" style="display:none;align-items:center;gap:10px;font-size:.875rem;color:var(--t3);">
          <span class="nv-spinner"></span><?= $locale['processing'] ?>
        </div>
      </div>

    </form>

  </div>
</div>

<style>
  .nv-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .nv-breadcrumb a { color:var(--t3);text-decoration:none; }
  .nv-breadcrumb a:hover { color:var(--accent); }
  .nv-breadcrumb span { margin:0 5px; }

  .nv-card { padding:22px;margin-bottom:20px; }
  .nv-card-header { display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border); }
  .nv-card-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0; }
  .nv-card-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0; }
  .nv-card-sub { font-size:.75rem;color:var(--t3);margin:2px 0 0; }

  .nv-field { margin-bottom:18px; }
  .nv-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em; }
  .nv-required { color:#ef4444; }

  .nv-input {
    width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
  }
  .nv-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nv-input::placeholder { color:var(--t3); }
  .nv-textarea { resize:vertical;min-height:80px; }
  .nv-input-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none; }
  .nv-input-with-icon { padding-left:36px; }

  /* Type pills */
  .nv-type-pills { display:flex;gap:8px;flex-wrap:wrap; }
  .nv-type-pill {
    display:inline-flex;align-items:center;gap:6px;padding:7px 14px;
    border:1.5px solid var(--border);border-radius:8px;background:#fff;
    font-size:.8rem;font-weight:600;color:var(--t2);cursor:pointer;
    transition:all .15s;
  }
  .nv-type-pill:hover { border-color:var(--accent);color:var(--accent); }
  .nv-type-pill.active { border-color:var(--accent);background:#eef2ff;color:var(--accent); }

  /* Upload zones */
  .nv-upload-zone {
    border:2px dashed var(--border);border-radius:10px;padding:24px 16px;
    text-align:center;cursor:pointer;transition:border-color .15s,background .15s;
    min-height:100px;display:flex;align-items:center;justify-content:center;overflow:hidden;
  }
  .nv-upload-zone:hover { border-color:var(--accent);background:#f8fafc; }
  .nv-upload-zone-video { min-height:80px; }
  .nv-upload-placeholder { display:flex;flex-direction:column;align-items:center; }
  .nv-remove-btn {
    display:block;width:100%;margin-top:8px;padding:7px;border:1.5px solid #fecaca;
    background:#fef2f2;color:#ef4444;border-radius:8px;font-size:.8rem;font-weight:600;
    cursor:pointer;transition:all .15s;
  }
  .nv-remove-btn:hover { background:#ef4444;color:#fff;border-color:#ef4444; }

  /* Settings */
  .nv-setting-row { display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border); }
  .nv-setting-row:last-of-type { border-bottom:none; }

  .nv-toggle-switch { position:relative;display:inline-block;width:38px;height:22px;cursor:pointer; }
  .nv-toggle-switch input { opacity:0;width:0;height:0; }
  .nv-toggle-slider {
    position:absolute;inset:0;background:#e2e8f0;border-radius:22px;
    transition:background .2s;
  }
  .nv-toggle-slider::before {
    content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;
    background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);
  }
  .nv-toggle-switch input:checked + .nv-toggle-slider { background:var(--accent); }
  .nv-toggle-switch input:checked + .nv-toggle-slider::before { transform:translateX(16px); }

  /* Submit bar */
  .nv-submit-bar { display:flex;align-items:center;justify-content:flex-end;gap:12px;padding:16px 0 8px;border-top:1px solid var(--border); }
  .nv-spinner {
    display:inline-block;width:14px;height:14px;border:2px solid #d1d5db;
    border-top-color:var(--accent);border-radius:50%;animation:nv-spin .6s linear infinite;
  }
  @keyframes nv-spin { to { transform:rotate(360deg); } }
</style>

<script>
var _videoLabels = {
  video_link    : '<?= $locale['video_lnk'] ?>',
  youtube_video : 'YouTube Video ID',
  mpd_video     : 'MPD Stream URL',
  m3u8_video    : 'M3U8 Stream URL'
};

function setVideoType(val, btn) {
  /* Update hidden select that common.js reads */
  document.getElementById('media_type').value = val;

  /* Update pill UI */
  document.querySelectorAll('.nv-type-pill').forEach(function(p){ p.classList.remove('active'); });
  btn.classList.add('active');

  var isUpload = (val === 'mp4_video');
  document.getElementById('upload_div').style.display = isUpload ? 'block' : 'none';
  document.getElementById('link_div').style.display   = isUpload ? 'none'  : 'block';

  if (!isUpload) {
    var lbl = _videoLabels[val] || '<?= $locale['video_lnk'] ?>';
    document.getElementById('video-label').textContent = lbl;
    /* Change link input type for YouTube (text for ID, url for link) */
    var ml = document.getElementById('media_link');
    ml.type = (val === 'youtube_video') ? 'text' : 'url';
    ml.placeholder = (val === 'youtube_video') ? 'Paste YouTube URL or video ID' : lbl;

    /* Wire up / tear down YouTube auto-fill */
    ml.removeEventListener('input', syncYouTubeCover);
    document.getElementById('yt_cover_hint').style.display = 'none';
    if (val === 'youtube_video') {
      ml.addEventListener('input', syncYouTubeCover);
    }
  }
}

function syncDownload(cb) {
  document.getElementById('can_download').value = cb.checked ? '0' : '1';
}

function previewFile(input, imgId, placeholderId, removeId) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var img = document.getElementById(imgId);
    img.src = e.target.result;
    img.style.display = 'block';
    document.getElementById(placeholderId).style.display = 'none';
    document.getElementById(removeId).style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}

function clearFile(inputId, imgId, placeholderId, removeId) {
  document.getElementById(inputId).value = '';
  document.getElementById(imgId).style.display = 'none';
  document.getElementById(placeholderId).style.display = 'flex';
  document.getElementById(removeId).style.display = 'none';
}

function previewVideoFile(input) {
  if (!input.files || !input.files[0]) return;
  var f = input.files[0];
  var mb = (f.size / 1024 / 1024).toFixed(1);
  document.getElementById('video_placeholder').style.display = 'none';
  document.getElementById('video_selected').style.display = 'block';
  document.getElementById('video_name').textContent = f.name;
  document.getElementById('video_size').textContent = mb + ' MB';
  document.getElementById('remove_video').style.display = 'block';
}

function extractYouTubeId(val) {
  val = val.trim();
  /* Full URL patterns */
  var patterns = [
    /[?&]v=([A-Za-z0-9_-]{11})/,   // ?v=ID or &v=ID
    /youtu\.be\/([A-Za-z0-9_-]{11})/,
    /embed\/([A-Za-z0-9_-]{11})/,
    /shorts\/([A-Za-z0-9_-]{11})/,
  ];
  for (var i = 0; i < patterns.length; i++) {
    var m = val.match(patterns[i]);
    if (m) return m[1];
  }
  /* Bare 11-character ID */
  if (/^[A-Za-z0-9_-]{11}$/.test(val)) return val;
  return null;
}

function syncYouTubeCover() {
  var id = extractYouTubeId(document.getElementById('media_link').value);
  var thumbEl = document.getElementById('thumbnail_link');
  var hintEl  = document.getElementById('yt_cover_hint');
  if (id) {
    thumbEl.value = 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
    hintEl.style.display = 'block';
  } else {
    thumbEl.value = '';
    hintEl.style.display = 'none';
  }
}

function clearVideoFile() {
  document.getElementById('video-file').value = '';
  document.getElementById('video_placeholder').style.display = 'flex';
  document.getElementById('video_selected').style.display = 'none';
  document.getElementById('remove_video').style.display = 'none';
}
</script>
