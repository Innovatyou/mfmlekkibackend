<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">New Partnership</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('partnership') ?>">Partnership</a><span>/</span><a href="<?= base_url('partnershipListing') ?>">All Partners</a><span>/</span><span>New</span></nav>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>

    <form method="POST" action="<?= base_url('saveNewPartnership') ?>">
      <?= csrf_field() ?>
      <div class="row">

        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Partner Information</h3></div>
            <div class="nf-card-body">

              <div style="margin-bottom:16px;">
                <label class="nf-label">Link to Member (optional)</label>
                <select name="member_id" class="nf-input" id="member_select" onchange="psAutoFill(this)">
                  <option value="">— Select existing member —</option>
                  <?php foreach ($members as $m): ?>
                  <option value="<?= $m->id ?>" data-name="<?= esc($m->firstname . ' ' . $m->lastname) ?>" data-email="<?= esc($m->email) ?>">
                    <?= esc($m->firstname . ' ' . $m->lastname) ?><?= $m->email ? ' (' . esc($m->email) . ')' : '' ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-two-thirds">
                  <label class="nf-label">Partner Name <span style="color:#ef4444;">*</span></label>
                  <input type="text" name="partner_name" id="inp_name" class="nf-input" placeholder="Full name" required>
                </div>
                <div class="nf-col-third">
                  <label class="nf-label">Phone</label>
                  <input type="text" name="partner_phone" class="nf-input" placeholder="+1 000 000 0000">
                </div>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Email</label>
                <input type="email" name="partner_email" id="inp_email" class="nf-input" placeholder="partner@example.com">
              </div>

              <div style="margin-bottom:0;">
                <label class="nf-label">Notes</label>
                <textarea name="notes" rows="3" class="nf-input" style="resize:vertical;" placeholder="Optional notes about this partnership…"></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Pledge Details</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label">Partnership Tier</label>
                <select name="tier_id" class="nf-input">
                  <option value="">— Select tier —</option>
                  <?php foreach ($tiers as $t): ?>
                  <option value="<?= $t->id ?>"><?= esc($t->name) ?> (min <?= number_format((float)$t->min_amount, 0) ?>)</option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-two-thirds">
                  <label class="nf-label">Pledge Amount <span style="color:#ef4444;">*</span></label>
                  <input type="number" name="pledge_amount" class="nf-input" placeholder="0.00" min="0" step="0.01" required>
                </div>
                <div class="nf-col-third">
                  <label class="nf-label">Currency</label>
                  <select name="currency" class="nf-input">
                    <option value="USD">USD — US Dollar</option>
                    <option value="GBP">GBP — British Pound</option>
                    <option value="EUR">EUR — Euro</option>
                    <option value="NGN">NGN — Nigerian Naira</option>
                    <option value="GHS">GHS — Ghanaian Cedi</option>
                    <option value="KES">KES — Kenyan Shilling</option>
                    <option value="ZAR">ZAR — South African Rand</option>
                    <option value="CAD">CAD — Canadian Dollar</option>
                    <option value="AUD">AUD — Australian Dollar</option>
                    <option value="INR">INR — Indian Rupee</option>
                    <option value="JPY">JPY — Japanese Yen</option>
                    <option value="CNY">CNY — Chinese Yuan</option>
                    <option value="BRL">BRL — Brazilian Real</option>
                    <option value="MXN">MXN — Mexican Peso</option>
                    <option value="CHF">CHF — Swiss Franc</option>
                    <option value="SEK">SEK — Swedish Krona</option>
                    <option value="NOK">NOK — Norwegian Krone</option>
                    <option value="DKK">DKK — Danish Krone</option>
                    <option value="SGD">SGD — Singapore Dollar</option>
                    <option value="HKD">HKD — Hong Kong Dollar</option>
                  </select>
                </div>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Amount Paid So Far</label>
                <input type="number" name="paid_amount" class="nf-input" placeholder="0.00" min="0" step="0.01" value="0">
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Frequency</label>
                <select name="frequency" class="nf-input">
                  <option value="monthly">Monthly</option>
                  <option value="one-time">One-time</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="annually">Annually</option>
                </select>
              </div>

              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Start Date</label>
                  <input type="date" name="start_date" class="nf-input" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">End Date</label>
                  <input type="date" name="end_date" class="nf-input">
                </div>
              </div>

              <div style="margin-bottom:0;background:#fefce8;border:1px solid #fde68a;border-radius:9px;padding:10px 14px;display:flex;align-items:flex-start;gap:10px;">
                <i class="dw dw-information" style="color:#d97706;font-size:1.1rem;flex-shrink:0;margin-top:1px;"></i>
                <div style="font-size:.82rem;color:#78350f;line-height:1.5;">
                  <strong>Pending Review:</strong> New partnership applications are placed under review before becoming active. Approve them from the All Partners listing.
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top:20px;display:flex;flex-direction:column;gap:10px;">
            <button type="submit" class="btn btn-primary nf-submit">Save Partnership</button>
            <a href="<?= base_url('partnershipListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>

<style>
.nf-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.nf-card-head{padding:16px 20px 0;}.nf-card-title{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.nf-card-body{padding:16px 20px 20px;}
.nf-label{display:block;font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.nf-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:.875rem;color:var(--t1);outline:none;transition:border-color .15s;background:#fff;}
.nf-input:focus{border-color:var(--accent);}
.nf-row{display:flex;gap:16px;}.nf-col-half{flex:1;min-width:0;}
.nf-col-two-thirds{flex:2;min-width:0;}.nf-col-third{flex:1;min-width:0;}
.nf-submit{padding:10px 28px;font-weight:600;border-radius:9px;width:100%;}
.nf-cancel{padding:10px 20px;font-weight:600;border-radius:9px;color:var(--t2);text-align:center;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
</style>

<script>
function psAutoFill(sel) {
  var opt = sel.options[sel.selectedIndex];
  if (opt.value) {
    document.getElementById('inp_name').value  = opt.getAttribute('data-name')  || '';
    document.getElementById('inp_email').value = opt.getAttribute('data-email') || '';
  }
}
</script>
