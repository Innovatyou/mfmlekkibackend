<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">New Service Time</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('serviceTimesListing') ?>">Service Times</a><span>/</span><span>New</span></nav>
      </div>
    </div>
    <form method="POST" action="<?= base_url('saveNewServiceTime') ?>">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Service Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Service Name</label>
                <input type="text" name="name" class="nf-input" placeholder="e.g. Sunday Worship Service" required>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Day</label>
                  <input type="text" name="day_of_week" class="nf-input" placeholder="e.g. Every Sunday" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Time</label>
                  <input type="text" name="time_label" class="nf-input" placeholder="e.g. 9:00 AM – 11:00 AM" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label">Location</label>
                <input type="text" name="location" class="nf-input" placeholder="e.g. Main Auditorium">
              </div>
              <div>
                <label class="nf-label">Description (optional)</label>
                <textarea name="description" class="nf-input" rows="2" placeholder="Short note shown under the service"></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Save Service Time</button>
            <a href="<?= base_url('serviceTimesListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Display</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Sort Order</label>
                <input type="number" name="sort_order" class="nf-input" value="0">
              </div>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" selected>Active — shown on website</option>
                  <option value="inactive">Inactive — hidden</option>
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
