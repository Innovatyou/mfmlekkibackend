<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['devotionals'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('devotionalsListing') ?>"><?= $locale['devotionals'] ?></a><span>/</span><span><?= $locale['edit_details'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editDevotionalData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $devotional->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Devotional Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['devotional_date'] ?></label>
                  <input type="date" name="date" class="nf-input" value="<?= esc($devotional->date) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['devo_author'] ?></label>
                  <input type="text" name="author" class="nf-input" value="<?= esc($devotional->author) ?>" required>
                </div>
              </div>
              <div style="margin-top:16px;">
                <label class="nf-label"><?= $locale['devotional_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($devotional->title) ?>" required>
              </div>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_text'] ?></h3><p class="nf-card-sub">Bible reading passage</p></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="bible_reading"><?= $devotional->bible_reading ?></textarea>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_content'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor" name="content"><?= $devotional->content ?></textarea>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_thought'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="confession"><?= $devotional->confession ?></textarea>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['further_reading'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="studies"><?= $devotional->studies ?></textarea>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update'] ?></button>
            <a href="<?= base_url('devotionalsListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_cover'] ?></h3><p class="nf-card-sub">Click to change cover</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="devo-cover-zone" onclick="document.getElementById('devo-cover-input').click()">
                <?php if(!empty($devotional->thumbnail)):?>
                <img src="<?=esc($devotional->thumbnail)?>" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Cover">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-camera"></i></div>
                <p class="nf-upload-text">Upload cover</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="devo-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'devo-cover-zone')">
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
