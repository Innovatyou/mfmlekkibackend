<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['articles'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('articlesListing') ?>"><?= $locale['articles'] ?></a><span>/</span><span><?= $locale['edit_article'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editArticleData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $article->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Article Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['article_date'] ?></label>
                  <input type="date" name="date" class="nf-input" value="<?= esc($article->date) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['article_writer'] ?></label>
                  <input type="text" name="author" class="nf-input" value="<?= esc($article->author) ?>" required>
                </div>
              </div>
              <div>
                <label class="nf-label"><?= $locale['article_title'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($article->title) ?>" required>
              </div>
            </div>
          </div>
          <div class="nf-card" style="margin-top:20px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['article_content'] ?></h3></div>
            <div class="nf-card-body">
              <textarea class="editor" name="content"><?= $article->content ?></textarea>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['updates'] ?></button>
            <a href="<?= base_url('articlesListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['article_cover'] ?></h3><p class="nf-card-sub">Click to change cover image</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="art-cover-zone" onclick="document.getElementById('art-cover-input').click()">
                <?php if(!empty($article->thumbnail)):?>
                <img src="<?=esc($article->thumbnail)?>" style="max-height:130px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Current cover">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-image"></i></div>
                <p class="nf-upload-text">Click to upload</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="art-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'art-cover-zone')">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<script>
function nfPreview(input,zoneId){var z=document.getElementById(zoneId);if(input.files&&input.files[0]){var r=new FileReader();r.onload=function(e){z.innerHTML='<img src="'+e.target.result+'" style="max-height:130px;max-width:100%;border-radius:8px;object-fit:cover;" alt="Preview">';};r.readAsDataURL(input.files[0]);}}
</script>
