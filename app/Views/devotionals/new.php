<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['devotionals'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('devotionalsListing') ?>"><?= $locale['devotionals'] ?></a><span>/</span><span><?= $locale['add_new'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('saveNewDevotional') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Devotional Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['devotional_date'] ?></label>
                  <input type="date" name="date" class="nf-input" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['devo_author'] ?></label>
                  <input type="text" name="author" class="nf-input" placeholder="<?= $locale['devo_author'] ?>" required>
                </div>
              </div>
              <div style="margin-top:16px;">
                <label class="nf-label"><?= $locale['devotional_title'] ?></label>
                <input type="text" name="title" class="nf-input" placeholder="<?= $locale['devotional_title'] ?>" required>
              </div>
            </div>
          </div>

          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_text'] ?></h3><p class="nf-card-sub">Bible reading passage</p></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="bible_reading"><?= $locale['devo_text'] ?></textarea>
            </div>
          </div>

          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_content'] ?></h3><p class="nf-card-sub">Main devotional content</p></div>
            <div class="nf-card-body">
              <textarea class="editor" name="content"><?= $locale['add_devo_content'] ?></textarea>
            </div>
          </div>

          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_thought'] ?></h3><p class="nf-card-sub">Thought / confession</p></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="confession"><?= $locale['add_devo_thought'] ?></textarea>
            </div>
          </div>

          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['further_reading'] ?></h3><p class="nf-card-sub">Study references</p></div>
            <div class="nf-card-body">
              <textarea class="editor1" name="studies"><?= $locale['add_further_reading'] ?></textarea>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['save_new'] ?></button>
            <a href="<?= base_url('devotionalsListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['devo_cover'] ?></h3><p class="nf-card-sub">Devotional cover image</p></div>
            <div class="nf-card-body">
              <div class="nf-upload-zone" id="devo-cover-zone" onclick="document.getElementById('devo-cover-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-camera"></i></div>
                <p class="nf-upload-text">Click to upload cover image</p>
                <p class="nf-upload-hint">PNG, JPG up to 5MB</p>
              </div>
              <input type="file" id="devo-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreviewImg(this,'devo-cover-zone')">
            </div>
          </div>
          <div class="nf-card" style="margin-top:16px;background:linear-gradient(135deg,#eef2ff,#f5f3ff);">
            <div class="nf-card-body" style="padding:20px;">
              <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="dw dw-information" style="color:#fff;font-size:1rem;"></i></div>
                <div>
                  <p style="font-size:.8rem;font-weight:600;color:#4338ca;margin:0 0 4px;">Publishing Tips</p>
                  <p style="font-size:.78rem;color:#6366f1;margin:0;line-height:1.5;">Use a clear cover image. The Bible reading field is for the scripture passage. Main content is the full devotional message.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<style>
.nf-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden}
.nf-card-head{padding:16px 20px 0;}.nf-card-title{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.nf-card-sub{font-size:.78rem;color:var(--t3);margin:0 0 16px;}
.nf-card-body{padding:16px 20px 20px;}
.nf-label{display:block;font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.nf-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:.875rem;color:var(--t1);outline:none;transition:border-color .15s;}
.nf-input:focus{border-color:var(--accent);}
.nf-row{display:flex;gap:16px;}.nf-col-half{flex:1;min-width:0;}
.nf-upload-zone{border:2px dashed var(--border);border-radius:10px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;}
.nf-upload-zone:hover{border-color:var(--accent);background:#f8f7ff;}
.nf-upload-icon{font-size:2rem;color:var(--t3);margin-bottom:8px;}
.nf-upload-text{font-size:.85rem;font-weight:600;color:var(--t2);margin:0 0 4px;}
.nf-upload-hint{font-size:.75rem;color:var(--t3);margin:0;}
.nf-submit{padding:10px 28px;font-weight:600;border-radius:9px;}
.nf-cancel{padding:10px 20px;font-weight:600;border-radius:9px;color:var(--t2);}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-bc span{color:var(--t3);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
</style>
<script>
function nfPreviewImg(input, zoneId) {
  var zone = document.getElementById(zoneId);
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      zone.innerHTML = '<img src="'+e.target.result+'" style="max-height:140px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Preview">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
