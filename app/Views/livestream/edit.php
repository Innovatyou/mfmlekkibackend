<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['livestream_channels'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('livestreams') ?>"><?= $locale['livestream_channels'] ?></a><span>/</span><span><?= $locale['edit_livestream'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editLivestreamData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $livestream->id ?>">
      <div class="row">
        <div class="col-lg-7">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Stream Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['livestream_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($livestream->title) ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['livestream_desc'] ?></label>
                <input type="text" name="description" class="nf-input" value="<?= esc($livestream->description) ?>">
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['livestream_src'] ?></label>
                  <select name="source" class="nf-input nf-select" required>
                    <option value="youtube" <?= $livestream->source=='youtube'?'selected':'' ?>>YouTube Live</option>
                    <option value="facebook" <?= $livestream->source=='facebook'?'selected':'' ?>>Facebook Live</option>
                    <option value="m3u8" <?= $livestream->source=='m3u8'?'selected':'' ?>>M3U8</option>
                    <option value="rtmp" <?= $livestream->source=='rtmp'?'selected':'' ?>>RTMP</option>
                  </select>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['livestream_status'] ?></label>
                  <select name="status" class="nf-input nf-select" required>
                    <option value="0" <?= $livestream->status==0?'selected':'' ?>><?= $locale['live'] ?></option>
                    <option value="1" <?= $livestream->status==1?'selected':'' ?>><?= $locale['not_live'] ?></option>
                  </select>
                </div>
              </div>
              <div>
                <label class="nf-label"><?= $locale['livestream_link'] ?></label>
                <input type="text" name="link" class="nf-input" value="<?= esc($livestream->link) ?>" required>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_live'] ?></button>
            <a href="<?= base_url('livestreams') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['livestream_cover_ex'] ?></h3><p class="nf-card-sub">Click to change cover photo</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="ls-cover-zone" onclick="document.getElementById('ls-cover-input').click()">
                <?php if(!empty($livestream->cover_photo)):?>
                <img src="<?=esc($livestream->cover_photo)?>" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Cover">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-camera"></i></div>
                <p class="nf-upload-text">Upload cover photo</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="ls-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'ls-cover-zone')">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<script>
function nfPreview(input,zoneId){var z=document.getElementById(zoneId);if(input.files&&input.files[0]){var r=new FileReader();r.onload=function(e){z.innerHTML='<img src="'+e.target.result+'" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Preview">';};r.readAsDataURL(input.files[0]);}}
</script>
