<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['email_sms_list'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('lists') ?>"><?= $locale['email_sms_list'] ?></a><span>/</span><span><?= esc($list->title) ?></span><span>/</span><span><?= $locale['add_member_list'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('savenewmemberslist') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= esc($list->id) ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['add_member_to_list'] ?></h3><p class="nf-card-sub"><?= esc($list->title) ?></p></div>
            <div class="nf-card-body">
              <label class="nf-label">Select Members</label>
              <select name="members[]" class="selectpicker form-control" data-size="8" multiple data-actions-box="true" data-style="btn-outline-secondary" style="width:100%;">
                <?php foreach ($members as $res): ?>
                  <option value="<?= esc($res->email) ?>"><?= esc($res->firstname . ' ' . $res->lastname . ' (' . $res->email . ')') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['add_to_list'] ?></button>
            <a href="javascript:history.back()" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
