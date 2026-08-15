<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['radio_stations'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('radio') ?>"><?= $locale['radio_stations'] ?></a><span>/</span><span><?= $locale['edit_radio'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editRadioData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $radio->id ?>">
      <div class="row">
        <div class="col-lg-7">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Radio Station Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['radio_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($radio->title) ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['radio_desc'] ?></label>
                <input type="text" name="description" class="nf-input" value="<?= esc($radio->description) ?>" required>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['radio_link'] ?></label>
                <input type="text" name="link" class="nf-input" value="<?= esc($radio->link) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['radio_status'] ?></label>
                <select name="status" class="nf-input nf-select" required>
                  <option value="0" <?= $radio->status==0?'selected':'' ?>><?= $locale['live'] ?></option>
                  <option value="1" <?= $radio->status==1?'selected':'' ?>><?= $locale['not_live'] ?></option>
                </select>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_radio'] ?></button>
            <a href="<?= base_url('radio') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['radio_cover'] ?></h3><p class="nf-card-sub">Click to change cover photo</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="radio-cover-zone" onclick="document.getElementById('radio-cover-input').click()">
                <?php if(!empty($radio->cover_photo)):?>
                <img src="<?=esc($radio->cover_photo)?>" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Cover">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-signal"></i></div>
                <p class="nf-upload-text">Upload cover photo</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="radio-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'radio-cover-zone')">
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
