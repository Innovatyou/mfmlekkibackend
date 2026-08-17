<?php
$currencies = [
  'USD'=>'USD — US Dollar','GBP'=>'GBP — British Pound','EUR'=>'EUR — Euro',
  'NGN'=>'NGN — Nigerian Naira','GHS'=>'GHS — Ghanaian Cedi','KES'=>'KES — Kenyan Shilling',
  'ZAR'=>'ZAR — South African Rand','CAD'=>'CAD — Canadian Dollar','AUD'=>'AUD — Australian Dollar',
  'INR'=>'INR — Indian Rupee','JPY'=>'JPY — Japanese Yen','CNY'=>'CNY — Chinese Yuan',
  'BRL'=>'BRL — Brazilian Real','MXN'=>'MXN — Mexican Peso','CHF'=>'CHF — Swiss Franc',
  'SEK'=>'SEK — Swedish Krona','NOK'=>'NOK — Norwegian Krone','DKK'=>'DKK — Danish Krone',
  'SGD'=>'SGD — Singapore Dollar','HKD'=>'HKD — Hong Kong Dollar',
];
$remaining = max(0, (float)$partnership->pledge_amount - (float)$partnership->paid_amount);
$progress  = $partnership->pledge_amount > 0 ? min(100, round((float)$partnership->paid_amount / (float)$partnership->pledge_amount * 100)) : 0;
?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Partnership</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('partnership') ?>">Partnership</a><span>/</span><a href="<?= base_url('partnershipListing') ?>">All Partners</a><span>/</span><span>Edit</span></nav>
      </div>
      <?php if (in_array($partnership->status, ['active','overdue'])): ?>
      <a href="<?= base_url('partnerPayment/' . $partnership->id) ?>" target="_blank" class="btn btn-primary lt-cta"><i class="dw dw-wallet1"></i> Payment Page</a>
      <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>

    <?php if ($partnership->status === 'pending' && (hasPermission('partnership.edit') || isSuperAdmin())): ?>
    <div style="background:#fefce8;border:1.5px solid #fde68a;border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="dw dw-clock" style="color:#d97706;font-size:1.2rem;"></i>
        </div>
        <div>
          <div style="font-size:.9rem;font-weight:700;color:#78350f;">Pending Admin Review</div>
          <div style="font-size:.8rem;color:#92400e;margin-top:1px;">This partnership application is awaiting approval. Approve it to activate the partner account.</div>
        </div>
      </div>
      <a href="<?= base_url('approvePartnership/' . $partnership->id) ?>"
        onclick="return confirm('Approve this partnership and set it to active?')"
        class="btn btn-primary" style="background:#d97706;border-color:#d97706;padding:9px 22px;font-weight:700;border-radius:9px;white-space:nowrap;flex-shrink:0;">
        <i class="dw dw-check-circle-2"></i> Approve Partnership
      </a>
    </div>
    <?php endif; ?>

    <!-- Progress bar -->
    <div class="card-box" style="padding:18px 22px;margin-bottom:20px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;gap:12px;flex-wrap:wrap;">
        <div>
          <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);">Pledge Progress</span>
          <div style="font-size:1.1rem;font-weight:800;color:var(--t1);margin-top:2px;">
            <?= esc($partnership->currency) ?> <?= number_format((float)$partnership->paid_amount, 2) ?>
            <span style="font-size:.8rem;font-weight:500;color:var(--t3);">/ <?= number_format((float)$partnership->pledge_amount, 2) ?> pledged</span>
          </div>
        </div>
        <div style="text-align:right;">
          <?php if ($remaining <= 0): ?>
            <span style="color:#059669;font-weight:700;font-size:.9rem;">✓ Fulfilled</span>
          <?php else: ?>
            <span style="font-size:.78rem;color:var(--t3);">Remaining</span>
            <div style="font-weight:700;color:#ef4444;"><?= esc($partnership->currency) ?> <?= number_format($remaining, 2) ?></div>
          <?php endif; ?>
        </div>
        <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
        <button onclick="document.getElementById('recordPayModal').style.display='flex'" class="btn btn-primary" style="padding:8px 18px;font-weight:600;white-space:nowrap;"><i class="dw dw-add"></i> Record Payment</button>
        <?php endif; ?>
      </div>
      <div style="height:8px;background:var(--border);border-radius:99px;overflow:hidden;">
        <div style="height:100%;width:<?= $progress ?>%;background:<?= $progress >= 100 ? '#059669' : 'var(--accent)' ?>;border-radius:99px;transition:width .4s;"></div>
      </div>
      <div style="font-size:.75rem;color:var(--t3);margin-top:5px;"><?= $progress ?>% of pledge fulfilled</div>
    </div>

    <form method="POST" action="<?= base_url('editPartnershipData') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $partnership->id ?>">

      <div class="row">

        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Partner Information</h3></div>
            <div class="nf-card-body">

              <div style="margin-bottom:16px;">
                <label class="nf-label">Link to Member (optional)</label>
                <select name="member_id" class="nf-input" onchange="psAutoFill(this)">
                  <option value="">— None —</option>
                  <?php foreach ($members as $m): ?>
                  <option value="<?= $m->id ?>" data-name="<?= esc($m->firstname . ' ' . $m->lastname) ?>" data-email="<?= esc($m->email) ?>"
                    <?= (int)$partnership->member_id === (int)$m->id ? 'selected' : '' ?>>
                    <?= esc($m->firstname . ' ' . $m->lastname) ?><?= $m->email ? ' (' . esc($m->email) . ')' : '' ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-two-thirds">
                  <label class="nf-label">Partner Name <span style="color:#ef4444;">*</span></label>
                  <input type="text" name="partner_name" id="inp_name" class="nf-input" value="<?= esc($partnership->partner_name) ?>" required>
                </div>
                <div class="nf-col-third">
                  <label class="nf-label">Phone</label>
                  <input type="text" name="partner_phone" class="nf-input" value="<?= esc($partnership->partner_phone) ?>">
                </div>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Email</label>
                <input type="email" name="partner_email" id="inp_email" class="nf-input" value="<?= esc($partnership->partner_email) ?>">
              </div>

              <div>
                <label class="nf-label">Notes</label>
                <textarea name="notes" rows="3" class="nf-input" style="resize:vertical;"><?= esc($partnership->notes) ?></textarea>
              </div>
            </div>
          </div>

          <!-- Payment history -->
          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head" style="display:flex;align-items:center;justify-content:space-between;">
              <div><h3 class="nf-card-title">Payment History</h3><p class="nf-card-sub">All recorded payments for this partnership</p></div>
              <?php if (in_array($partnership->status, ['active','overdue'])): ?>
              <a href="<?= base_url('partnerPayment/' . $partnership->id) ?>" target="_blank" style="font-size:.78rem;color:var(--accent);text-decoration:none;padding-right:0;">Online Payment Page →</a>
              <?php endif; ?>
            </div>
            <div style="padding:0 20px 18px;">
              <?php if (!empty($payments)): ?>
              <table style="width:100%;border-collapse:collapse;">
                <thead><tr>
                  <th style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:var(--t3);border-bottom:2px solid var(--border);padding:8px 10px;text-align:left;background:#f8fafc;">Date</th>
                  <th style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:var(--t3);border-bottom:2px solid var(--border);padding:8px 10px;text-align:left;background:#f8fafc;">Amount</th>
                  <th style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:var(--t3);border-bottom:2px solid var(--border);padding:8px 10px;text-align:left;background:#f8fafc;">Method</th>
                  <th style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:var(--t3);border-bottom:2px solid var(--border);padding:8px 10px;text-align:left;background:#f8fafc;">Reference</th>
                  <th style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:var(--t3);border-bottom:2px solid var(--border);padding:8px 10px;text-align:left;background:#f8fafc;">By</th>
                </tr></thead>
                <tbody>
                  <?php foreach ($payments as $pay): ?>
                  <tr>
                    <td style="padding:9px 10px;border-bottom:1px solid var(--border);font-size:.82rem;color:var(--t2);"><?= date('M j, Y', strtotime($pay->created_at)) ?></td>
                    <td style="padding:9px 10px;border-bottom:1px solid var(--border);font-size:.875rem;font-weight:700;color:var(--t1);"><?= esc($pay->currency) ?> <?= number_format((float)$pay->amount, 2) ?></td>
                    <td style="padding:9px 10px;border-bottom:1px solid var(--border);font-size:.82rem;">
                      <span style="display:inline-block;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:600;background:#f1f5f9;color:var(--t2);"><?= ucfirst(esc($pay->method)) ?></span>
                    </td>
                    <td style="padding:9px 10px;border-bottom:1px solid var(--border);font-size:.75rem;color:var(--t3);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($pay->reference ?: '—') ?></td>
                    <td style="padding:9px 10px;border-bottom:1px solid var(--border);font-size:.78rem;color:var(--t3);"><?= esc($pay->recorded_by ?: '—') ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <?php else: ?>
              <p style="color:var(--t3);font-size:.85rem;padding:16px 0 4px;">No payments recorded yet.</p>
              <?php endif; ?>
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
                  <option value="<?= $t->id ?>" <?= (int)$partnership->tier_id === (int)$t->id ? 'selected' : '' ?>>
                    <?= esc($t->name) ?> (min <?= number_format((float)$t->min_amount, 0) ?>)
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Pledge Amount <span style="color:#ef4444;">*</span></label>
                <input type="number" name="pledge_amount" class="nf-input" value="<?= esc($partnership->pledge_amount) ?>" min="0" step="0.01" required>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Currency</label>
                <select name="currency" class="nf-input">
                  <?php foreach ($currencies as $code => $label): ?>
                  <option value="<?= $code ?>" <?= $partnership->currency === $code ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div style="margin-bottom:16px;">
                <label class="nf-label">Frequency</label>
                <select name="frequency" class="nf-input">
                  <?php foreach (['monthly'=>'Monthly','one-time'=>'One-time','quarterly'=>'Quarterly','annually'=>'Annually'] as $val=>$lbl): ?>
                  <option value="<?= $val ?>" <?= $partnership->frequency === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label">Start Date</label>
                  <input type="date" name="start_date" class="nf-input" value="<?= esc($partnership->start_date) ?>">
                </div>
                <div class="nf-col-half">
                  <label class="nf-label">End Date</label>
                  <input type="date" name="end_date" class="nf-input" value="<?= esc($partnership->end_date) ?>">
                </div>
              </div>

              <div>
                <label class="nf-label">Status</label>
                <select name="status" class="nf-input">
                  <?php foreach (['pending'=>'Pending Review','active'=>'Active','overdue'=>'Overdue','completed'=>'Completed','cancelled'=>'Cancelled'] as $val=>$lbl): ?>
                  <option value="<?= $val ?>" <?= $partnership->status === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div style="margin-top:20px;display:flex;flex-direction:column;gap:10px;">
            <button type="submit" class="btn btn-primary nf-submit">Save Changes</button>
            <a href="<?= base_url('partnershipListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Record Payment Modal -->
<?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
<div id="recordPayModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;padding:28px 30px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.2);position:relative;">
    <button onclick="document.getElementById('recordPayModal').style.display='none';document.body.style.overflow=''" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:1.3rem;color:var(--t3);cursor:pointer;line-height:1;">&times;</button>
    <h4 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0 0 6px;">Record Payment</h4>
    <p style="font-size:.8rem;color:var(--t3);margin:0 0 20px;">Manually record a cash, bank transfer, or other offline payment.</p>
    <form method="POST" action="<?= base_url('adminRecordPayment/' . $partnership->id) ?>">
      <?= csrf_field() ?>
      <div style="margin-bottom:14px;">
        <label class="nf-label">Amount <span style="color:#ef4444;">*</span></label>
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:.85rem;font-weight:600;color:var(--t2);white-space:nowrap;"><?= esc($partnership->currency) ?></span>
          <input type="number" name="amount" class="nf-input" placeholder="0.00" min="0.01" step="0.01"
            value="<?= $remaining > 0 ? number_format($remaining, 2, '.', '') : '' ?>" required style="flex:1;">
        </div>
        <?php if ($remaining > 0): ?>
        <div style="font-size:.75rem;color:var(--t3);margin-top:4px;">Remaining balance: <?= esc($partnership->currency) ?> <?= number_format($remaining, 2) ?></div>
        <?php endif; ?>
      </div>
      <div style="margin-bottom:14px;">
        <label class="nf-label">Payment Method</label>
        <select name="method" class="nf-input">
          <option value="manual">Manual / Cash</option>
          <option value="bank_transfer">Bank Transfer</option>
          <option value="cheque">Cheque</option>
          <option value="stripe">Stripe</option>
          <option value="paystack">Paystack</option>
          <option value="paypal">PayPal</option>
          <option value="flutterwaves">Flutterwaves</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div style="margin-bottom:14px;">
        <label class="nf-label">Reference / Transaction ID</label>
        <input type="text" name="reference" class="nf-input" placeholder="Optional">
      </div>
      <div style="margin-bottom:20px;">
        <label class="nf-label">Notes</label>
        <input type="text" name="notes" class="nf-input" placeholder="Optional">
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary" style="flex:1;padding:10px;font-weight:600;border-radius:9px;">Save Payment</button>
        <button type="button" onclick="document.getElementById('recordPayModal').style.display='none'" class="btn btn-light" style="flex:1;padding:10px;font-weight:600;border-radius:9px;">Cancel</button>
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
.nf-row{display:flex;gap:16px;}.nf-col-half{flex:1;min-width:0;}
.nf-col-two-thirds{flex:2;min-width:0;}.nf-col-third{flex:1;min-width:0;}
.nf-submit{padding:10px 28px;font-weight:600;border-radius:9px;width:100%;}
.nf-cancel{padding:10px 20px;font-weight:600;border-radius:9px;color:var(--t2);text-align:center;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-cta{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;}
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
document.getElementById('recordPayModal').addEventListener('click', function(e) {
  if (e.target === this) { this.style.display='none'; document.body.style.overflow=''; }
});
</script>
