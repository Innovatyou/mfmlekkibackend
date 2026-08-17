<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['group_event_act'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= $locale['new_event'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('savenewgroupevent') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="groupid" value="<?= esc($groupid) ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Event Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['event_title'] ?></label>
                <input type="text" name="title" class="nf-input" placeholder="<?= $locale['event_title'] ?>" required autofocus>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['event_date'] ?></label>
                  <input type="date" name="date" class="nf-input" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['event_time'] ?></label>
                  <input type="time" name="time" class="nf-input" required>
                </div>
              </div>
            </div>
          </div>

          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['event_detail'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor" name="details"></textarea>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['save_new'] ?></button>
            <a href="javascript:history.back()" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Poster</h3><p class="nf-card-sub">Click to upload</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="ge-poster-zone" onclick="document.getElementById('ge-poster-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-image"></i></div>
                <p class="nf-upload-text">Upload poster</p>
                <p class="nf-upload-hint">PNG, JPG</p>
              </div>
              <input type="file" id="ge-poster-input" name="thumbnail" accept="image/png,image/jpeg" class="thumbs_dropify" style="display:none;" onchange="nfPreview(this,'ge-poster-zone')" required>
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
