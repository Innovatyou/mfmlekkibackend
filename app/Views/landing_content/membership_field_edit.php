<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Form Field</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('membershipFormListing') ?>">Membership Form</a><span>/</span><span>Edit</span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>

    <?php if($item->is_core):?>
    <div class="lt-alert" style="background:#f1f5f9;color:#334155;border:1px solid #e2e8f0;">
      <i class="dw dw-information"></i>
      This is a core field (<code><?=esc($item->field_key)?></code>) tied to your Members list — its type and answer options are locked, but you can still change the label, placeholder, help text and whether it's required.
    </div>
    <?php endif;?>

    <form method="POST" action="<?= base_url('editMembershipFieldData') ?>" id="mf-form">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $item->id ?>">
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Field Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Question / Label</label>
                <input type="text" name="label" class="nf-input" value="<?= esc($item->label) ?>" required>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Field Type</label>
                  <select name="field_type" id="mf-type" class="nf-input nf-select" <?= $item->is_core ? 'disabled' : '' ?>>
                    <?php $types = ['text'=>'Short text','textarea'=>'Long text / paragraph','email'=>'Email','tel'=>'Phone number','date'=>'Date','select'=>'Dropdown (single choice)','radio'=>'Radio buttons (single choice)','checkbox'=>'Checkboxes (multiple choice)'];
                    foreach($types as $val=>$label2):?>
                      <option value="<?=$val?>" <?= $item->field_type==$val?'selected':'' ?>><?=$label2?></option>
                    <?php endforeach;?>
                  </select>
                  <?php if($item->is_core):?><input type="hidden" name="field_type" value="<?=esc($item->field_type)?>"><?php endif;?>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">Placeholder (optional)</label>
                  <input type="text" name="placeholder" class="nf-input" value="<?= esc($item->placeholder) ?>">
                </div>
              </div>
              <div id="mf-options-wrap" style="margin-bottom:16px;<?= in_array($item->field_type,['select','radio','checkbox']) ? '' : 'display:none;' ?>">
                <label class="nf-label">Answer Options</label>
                <textarea name="options" class="nf-input" rows="4" <?= $item->is_core ? 'disabled' : '' ?>><?= $item->options ? esc(implode("\n", $item->options)) : '' ?></textarea>
                <p class="nf-setting-hint">One option per line.</p>
              </div>
              <div>
                <label class="nf-label">Help Text (optional)</label>
                <input type="text" name="help_text" class="nf-input" value="<?= esc($item->help_text) ?>">
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit">Update Field</button>
            <a href="<?= base_url('membershipFormListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Settings</h3></div>
            <div class="nf-card-body">
              <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;cursor:pointer;">
                <input type="checkbox" name="required" value="1" style="width:16px;height:16px;" <?= $item->required ? 'checked' : '' ?>>
                <span style="font-size:.875rem;color:var(--t1);font-weight:600;">Required field</span>
              </label>
              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input nf-select">
                  <option value="active" <?= $item->status=='active'?'selected':'' ?>>Active — shown on form</option>
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
<script>
(function(){
  var select = document.getElementById('mf-type');
  var wrap = document.getElementById('mf-options-wrap');
  function sync(){
    var needsOptions = ['select','radio','checkbox'].indexOf(select.value) !== -1;
    wrap.style.display = needsOptions ? 'block' : 'none';
  }
  select.addEventListener('change', sync);
})();
</script>
