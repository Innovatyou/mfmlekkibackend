<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Service Time</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('serviceTimesListing') ?>">Service Times</a><span>/</span><span>Edit</span></nav>
      </div>
    </div>
    <form method="POST" action="<?= base_url('editServiceTimeData') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $item->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Service Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Service Name</label>
                <input type="text" name="name" class="nf-input" value="<?= esc($item->name) ?>" required>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Day</label>
                  <input type="text" name="day_of_week" class="nf-input" value="<?= esc($item->day_of_week) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Time</label>
                  <input type="text" name="time_label" class="nf-input" value="<?= esc($item->time_label) ?>" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Location</label>
                <input type="text" name="location" class="nf-input" value="<?= esc($item->location) ?>">
              </div>
              <div>
                <label class="nf-label">Description (optional)</label>
                <textarea name="description" class="nf-input" rows="2"><?= esc($item->description) ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Update Service Time</button>
            <a href="<?= base_url('serviceTimesListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Display</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Sort Order</label>
                <input type="number" name="sort_order" class="nf-input" value="<?= (int) $item->sort_order ?>">
              </div>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" <?= $item->status=='active'?'selected':'' ?>>Active — shown on website</option>
                  <option value="inactive" <?= $item->status=='inactive'?'selected':'' ?>>Inactive — hidden</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
