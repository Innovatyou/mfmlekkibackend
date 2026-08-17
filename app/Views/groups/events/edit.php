<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['group_event'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= $locale['edit_details'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editGroupEventData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $event->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Group Activity Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['event_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($event->title) ?>" required>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['event_date'] ?></label>
                  <input type="date" name="date" class="nf-input" value="<?= esc($event->date) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['event_time'] ?></label>
                  <input type="time" name="time" class="nf-input" value="<?= esc($event->time) ?>" required>
                </div>
              </div>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['privacy'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor" name="details"><?= $event->details ?></textarea>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update'] ?></button>
            <a href="<?= base_url('groups') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Activity Poster</h3><p class="nf-card-sub">Click to change image</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="grpevt-zone" onclick="document.getElementById('grpevt-input').click()">
                <?php if(!empty($event->thumbnail)):?>
                <img src="<?=esc($event->thumbnail)?>" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Poster">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-image"></i></div>
                <p class="nf-upload-text">Upload image</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="grpevt-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'grpevt-zone')">
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
