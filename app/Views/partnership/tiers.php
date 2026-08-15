<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Partnership Tiers</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('partnership') ?>">Partnership</a><span>/</span><span>Tiers</span></nav>
      </div>
      <a href="<?= base_url('partnershipListing') ?>" class="btn btn-light lt-cta"><i class="dw dw-group"></i> All Partners</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>

    <div class="row">

      <!-- Tiers list -->
      <div class="col-lg-8">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="lt-head"><h3 class="lt-htitle">All Tiers</h3><p class="lt-hsub">Configure partnership levels and minimum pledge amounts</p></div>
          <div style="padding:0 22px 22px;overflow-x:auto;">
            <table class="table mb-0" style="width:100%;">
              <thead><tr>
                <th style="width:40px;">#</th>
                <th>Tier Name</th>
                <th>Min Pledge</th>
                <th>Description</th>
                <th>Active Partners</th>
                <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?><th style="width:90px;">Actions</th><?php endif; ?>
              </tr></thead>
              <tbody>
                <?php $c = 1; foreach ($tiers as $t): ?>
                <tr>
                  <td class="text-muted"><?= $c ?></td>
                  <td>
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                      <span style="width:14px;height:14px;border-radius:50%;background:<?= esc($t->color) ?>;flex-shrink:0;display:inline-block;border:2px solid <?= esc($t->color) ?>44;"></span>
                      <strong style="color:var(--t1);"><?= esc($t->name) ?></strong>
                    </span>
                  </td>
                  <td style="font-weight:700;color:var(--t1);"><?= number_format((float)$t->min_amount, 2) ?></td>
                  <td style="color:var(--t2);font-size:.85rem;max-width:220px;"><?= esc($t->description) ?></td>
                  <td><span class="badge badge-pill badge-info"><?= (int)($t->partner_count ?? 0) ?></span></td>
                  <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
                  <td>
                    <div style="display:flex;gap:5px;">
                      <button class="lt-ab lt-edit" title="Edit"
                        onclick="openEditTier(<?= $t->id ?>, '<?= esc(addslashes($t->name)) ?>', '<?= esc(addslashes($t->description ?? '')) ?>', '<?= number_format((float)$t->min_amount, 2, '.', '') ?>', '<?= esc($t->color) ?>')"
                      ><i class="dw dw-edit-2"></i></button>
                      <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltTierDelConfirm(<?= $t->id ?>)"><i class="dw dw-trash"></i></a>
                    </div>
                  </td>
                  <?php endif; ?>
                </tr>
                <?php $c++; endforeach; ?>
                <?php if (empty($tiers)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--t3);padding:32px;">No tiers found. Add one on the right.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Add new tier -->
      <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
      <div class="col-lg-4">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title">Add New Tier</h3><p class="nf-card-sub">Create a new partnership level</p></div>
          <div class="nf-card-body">
            <form method="POST" action="<?= base_url('saveNewPartnershipTier') ?>">
              <?= csrf_field() ?>
              <div style="margin-bottom:14px;">
                <label class="nf-label">Tier Name <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="nf-input" placeholder="e.g. Gold, Platinum…" required>
              </div>
              <div style="margin-bottom:14px;">
                <label class="nf-label">Min Pledge Amount</label>
                <input type="number" name="min_amount" class="nf-input" placeholder="0.00" min="0" step="0.01" value="0">
              </div>
              <div style="margin-bottom:14px;">
                <label class="nf-label">Description</label>
                <textarea name="description" rows="2" class="nf-input" style="resize:vertical;" placeholder="Brief description…"></textarea>
              </div>
              <div style="margin-bottom:18px;">
                <label class="nf-label">Badge Color</label>
                <div style="display:flex;align-items:center;gap:10px;">
                  <input type="color" name="color" value="#6366f1" id="addColorPicker" style="width:42px;height:36px;border:1.5px solid var(--border);border-radius:8px;padding:2px;cursor:pointer;" oninput="document.getElementById('addColorPreview').style.background=this.value">
                  <span id="addColorPreview" style="display:inline-block;width:28px;height:28px;border-radius:6px;background:#6366f1;border:1px solid var(--border);"></span>
                  <span style="font-size:.78rem;color:var(--t3);">Tier badge color</span>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;font-weight:600;border-radius:9px;">Add Tier</button>
            </form>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<!-- Edit Tier Modal -->
<?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
<div id="editTierModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;padding:28px 30px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.2);position:relative;">
    <button onclick="closeEditTier()" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:1.3rem;color:var(--t3);cursor:pointer;line-height:1;">&times;</button>
    <h4 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0 0 20px;">Edit Tier</h4>
    <form method="POST" action="<?= base_url('updatePartnershipTierData') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="tier_id" id="editTierId">
      <div style="margin-bottom:14px;">
        <label class="nf-label">Tier Name <span style="color:#ef4444;">*</span></label>
        <input type="text" name="name" id="editTierName" class="nf-input" required>
      </div>
      <div style="margin-bottom:14px;">
        <label class="nf-label">Min Pledge Amount</label>
        <input type="number" name="min_amount" id="editTierMin" class="nf-input" min="0" step="0.01">
      </div>
      <div style="margin-bottom:14px;">
        <label class="nf-label">Description</label>
        <textarea name="description" id="editTierDesc" rows="2" class="nf-input" style="resize:vertical;"></textarea>
      </div>
      <div style="margin-bottom:20px;">
        <label class="nf-label">Badge Color</label>
        <div style="display:flex;align-items:center;gap:10px;">
          <input type="color" name="color" id="editTierColor" style="width:42px;height:36px;border:1.5px solid var(--border);border-radius:8px;padding:2px;cursor:pointer;" oninput="document.getElementById('editColorPreview').style.background=this.value">
          <span id="editColorPreview" style="display:inline-block;width:28px;height:28px;border-radius:6px;background:#6366f1;border:1px solid var(--border);"></span>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary" style="flex:1;padding:10px;font-weight:600;border-radius:9px;">Save Changes</button>
        <button type="button" onclick="closeEditTier()" class="btn btn-light" style="flex:1;padding:10px;font-weight:600;border-radius:9px;">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<style>
.nf-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.nf-card-head{padding:16px 20px 0;}.nf-card-title{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.nf-card-sub{font-size:.78rem;color:var(--t3);margin:0 0 14px;}
.nf-card-body{padding:16px 20px 20px;}
.nf-label{display:block;font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.nf-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:.875rem;color:var(--t1);outline:none;transition:border-color .15s;background:#fff;}
.nf-input:focus{border-color:var(--accent);}
.lt-head{padding:16px 20px 0;}
.lt-htitle{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.lt-hsub{font-size:.78rem;color:var(--t3);margin:0 0 14px;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-cta{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
.lt-ab{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;font-size:.85rem;text-decoration:none;transition:background .15s;border:none;cursor:pointer;}
.lt-edit{background:#ede9fe;color:#6366f1;}.lt-edit:hover{background:#ddd6fe;color:#4f46e5;}
.lt-del{background:#fee2e2;color:#ef4444;}.lt-del:hover{background:#fecaca;color:#dc2626;}
table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc;}
table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle;}
table tbody tr:hover td{background:#f8fafc;}
table tbody tr:last-child td{border-bottom:none!important;}
</style>

<script>
function openEditTier(id, name, description, minAmount, color) {
  document.getElementById('editTierId').value       = id;
  document.getElementById('editTierName').value     = name;
  document.getElementById('editTierDesc').value     = description;
  document.getElementById('editTierMin').value      = minAmount;
  document.getElementById('editTierColor').value    = color;
  document.getElementById('editColorPreview').style.background = color;
  var modal = document.getElementById('editTierModal');
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function closeEditTier() {
  document.getElementById('editTierModal').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('editTierModal').addEventListener('click', function(e) {
  if (e.target === this) closeEditTier();
});
function ltTierDelConfirm(id) {
  swal({ title: 'Delete Tier?', text: 'Partners in this tier will lose their tier assignment.', type: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Yes, delete' },
    function () { document.location.href = baseURL + '/deletePartnershipTier/' + id; });
}
</script>
