<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['videos'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('videos') ?>"><?= $locale['videos'] ?></a><span>/</span><span><?= $locale['edit_vid_det'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form id="upload-form">
      <input type="hidden" id="id" value="<?= $video->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Video Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['video_title_ex'] ?></label>
                <input type="text" id="title" class="nf-input" value="<?= esc($video->title) ?>" placeholder="<?= $locale['video_title'] ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['vid_desc_ex'] ?></label>
                <textarea id="description" class="nf-input" rows="3" style="resize:vertical;" placeholder="<?= $locale['vid_desc'] ?>"><?= esc($video->description) ?></textarea>
              </div>
              <div>
                <label class="nf-label"><?= $locale['vid_dur_ex'] ?></label>
                <input type="text" id="duration" name="duration" class="nf-input" value="<?= esc($video->duration) ?>" placeholder="<?= $locale['vid_dur'] ?>">
              </div>
            </div>
          </div>

          <div id="link_div" class="nf-card" style="margin-top:16px;display:none;">
            <div class="nf-card-head"><h3 class="nf-card-title">Source Links</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['cover_link'] ?></label>
                <input type="url" id="thumbnail_link" class="nf-input" value="<?= esc($video->cover_photo) ?>" placeholder="<?= $locale['cover_link'] ?>">
              </div>
              <div>
                <label class="nf-label" id="video-label"><?= $locale['audio_link'] ?></label>
                <input type="url" id="media_link" class="nf-input" value="<?= esc($video->source) ?>" placeholder="<?= $locale['audio_link'] ?>">
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;align-items:center;">
            <button id="submit" onclick="updateVideo(event)" class="btn btn-primary nf-submit"><?= $locale['update_vid'] ?></button>
            <a href="<?= base_url('videos') ?>" class="btn btn-light nf-cancel">Cancel</a>
            <div id="loader" style="display:none;align-items:center;gap:10px;font-size:.85rem;color:var(--t3);">
              <div class="nf-spinner"></div> <span id="publish_hint"><?= $locale['processing'] ?></span>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Access Settings</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['allow_free_stream'] ?></label>
                <p style="font-size:.75rem;color:var(--t3);margin:0 0 8px;"><?= $locale['video_subsc'] ?></p>
                <select id="is_free" class="nf-input nf-select">
                  <option value="0" <?= $video->is_free==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                  <option value="1" <?= $video->is_free==1?'selected':'' ?>><?= $locale['no'] ?></option>
                </select>
              </div>
              <div>
                <label class="nf-label"><?= $locale['allow_vid_down'] ?></label>
                <p style="font-size:.75rem;color:var(--t3);margin:0 0 8px;"><?= $locale['allow_vid_down_avail'] ?></p>
                <select id="can_download" class="nf-input nf-select">
                  <option value="0" <?= $video->can_download==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                  <option value="1" <?= $video->can_download==1?'selected':'' ?>><?= $locale['no'] ?></option>
                </select>
              </div>
            </div>
          </div>
          <!-- Hidden selects required by common.js updateVideo() -->
          <select id="can_preview" style="display:none;">
            <option value="0" <?= $video->can_preview==0?'selected':'' ?>><?= $locale['yes'] ?></option>
            <option value="1" <?= $video->can_preview==1?'selected':'' ?>><?= $locale['no'] ?></option>
          </select>
          <input type="number" id="preview_duration" style="display:none;" value="<?= esc($video->preview_duration) ?>">
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
#loader{display:none}
.nf-spinner{width:18px;height:18px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:nf-spin .7s linear infinite;flex-shrink:0}
@keyframes nf-spin{to{transform:rotate(360deg)}}
</style>
