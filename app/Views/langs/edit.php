<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['languages'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['update_language'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editLangData') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $lang->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Translations</h3><p class="nf-card-sub">ID: <?= esc($lang->id) ?></p></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:14px;">
                <div class="nf-col-half">
                  <label class="nf-label">English</label>
                  <input type="text" name="english" class="nf-input" value="<?= esc($lang->english) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">French</label>
                  <input type="text" name="french" class="nf-input" value="<?= esc($lang->french) ?>" required>
                </div>
              </div>
              <div class="nf-row" style="margin-bottom:14px;">
                <div class="nf-col-half">
                  <label class="nf-label">Spanish</label>
                  <input type="text" name="spanish" class="nf-input" value="<?= esc($lang->spanish) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">German</label>
                  <input type="text" name="german" class="nf-input" value="<?= esc($lang->german) ?>" required>
                </div>
              </div>
              <div class="nf-row" style="margin-bottom:14px;">
                <div class="nf-col-half">
                  <label class="nf-label">Arabic</label>
                  <input type="text" name="arabic" class="nf-input" value="<?= esc($lang->arabic) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Portugese</label>
                  <input type="text" name="portugese" class="nf-input" value="<?= esc($lang->portugese) ?>" required>
                </div>
              </div>
              <div>
                <label class="nf-label">Portugese-BR</label>
                <input type="text" name="portugesebr" class="nf-input" value="<?= esc($lang->portugesebr) ?>" required>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_data'] ?></button>
            <a href="javascript:history.back()" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
