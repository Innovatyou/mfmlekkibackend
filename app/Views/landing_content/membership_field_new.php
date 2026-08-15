<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">New Form Field</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('membershipFormListing') ?>">Membership Form</a><span>/</span><span>New</span></nav>
      </div>
    </div>
    <form method="POST" action="<?= base_url('saveNewMembershipField') ?>" id="mf-form">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Field Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Question / Label</label>
                <input type="text" name="label" class="nf-input" placeholder="e.g. What is your marital status?" required>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Field Type</label>
                  <select name="field_type" id="mf-type" class="nf-input nf-select">
                    <option value="text">Short text</option>
                    <option value="textarea">Long text / paragraph</option>
                    <option value="email">Email</option>
                    <option value="tel">Phone number</option>
                    <option value="date">Date</option>
                    <option value="select">Dropdown (single choice)</option>
                    <option value="radio">Radio buttons (single choice)</option>
                    <option value="checkbox">Checkboxes (multiple choice)</option>
                  </select>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Placeholder (optional)</label>
                  <input type="text" name="placeholder" class="nf-input" placeholder="Shown faintly inside the empty field">
                </div>
              </div>
              <div id="mf-options-wrap" style="margin-bottom:16px;display:none;">
                <label class="nf-label">Answer Options</label>
                <textarea name="options" class="nf-input" rows="4" placeholder="One option per line, e.g.&#10;Single&#10;Married&#10;Divorced"></textarea>
                <p class="nf-setting-hint">One option per line.</p>
              </div>
              <div>
                <label class="nf-label">Help Text (optional)</label>
                <input type="text" name="help_text" class="nf-input" placeholder="Small note shown under the field">
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Save Field</button>
            <a href="<?= base_url('membershipFormListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Settings</h3></div>
            <div class="nf-card-body">
              <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;cursor:pointer;">
                <input type="checkbox" name="required" value="1" style="width:16px;height:16px;">
                <span style="font-size:.875rem;color:var(--t1);font-weight:600;">Required field</span>
              </label>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" selected>Active — shown on form</option>
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
<script>
(function(){
  var select = document.getElementById('mf-type');
  var wrap = document.getElementById('mf-options-wrap');
  function sync(){
    var needsOptions = ['select','radio','checkbox'].indexOf(select.value) !== -1;
    wrap.style.display = needsOptions ? 'block' : 'none';
  }
  select.addEventListener('change', sync);
  sync();
})();
</script>
