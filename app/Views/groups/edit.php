<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['groups'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= $locale['edit_details'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editGroupData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $group->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Group Details</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_title'] ?></label>
                  <input type="text" name="title" class="nf-input" value="<?= esc($group->title) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_leader_name'] ?></label>
                  <input type="text" name="leader" class="nf-input" value="<?= esc($group->leader) ?>" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['group_desc'] ?></label>
                <textarea name="description" rows="4" class="nf-input" style="resize:vertical;" required><?= esc($group->description) ?></textarea>
              </div>
              <div class="nf-row">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['group_meeting_loc'] ?></label>
                  <input type="text" name="location" class="nf-input" value="<?= esc($group->location) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['meeting_days_extra'] ?></label>
                  <input type="text" name="time" class="nf-input" value="<?= esc($group->time) ?>" required>
                </div>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update'] ?></button>
            <a href="<?= base_url('groups') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
