<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Partnership</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span>Partnership</span></nav>
      </div>
      <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
      <a href="<?= base_url('newPartnership') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i> New Partner</a>
      <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>

    <!-- Stats -->
    <?php if (!empty($stats['pending']) && $stats['pending'] > 0): ?>
    <div style="background:#fefce8;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i class="dw dw-clock" style="color:#d97706;font-size:1.2rem;"></i>
        <span style="font-size:.88rem;font-weight:700;color:#78350f;"><?= $stats['pending'] ?> partnership application<?= $stats['pending'] !== 1 ? 's' : '' ?> pending review</span>
      </div>
      <a href="<?= base_url('partnershipListing') ?>" class="btn btn-sm" style="background:#fde68a;border:1px solid #fbbf24;color:#78350f;font-weight:700;border-radius:8px;padding:5px 14px;">Review Now →</a>
    </div>
    <?php endif; ?>
    <div class="row mb-4">
      <div class="col-xl col-sm-4 col-6 mb-3">
        <div class="ps-card">
          <div class="ps-card-icon" style="background:#ede9fe;color:#6366f1;"><i class="dw dw-group"></i></div>
          <div>
            <div class="ps-card-val"><?= number_format($stats['total']) ?></div>
            <div class="ps-card-lbl">Total Partners</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-4 col-6 mb-3">
        <a href="<?= base_url('partnershipListing') ?>" style="text-decoration:none;">
        <div class="ps-card" style="<?= $stats['pending'] > 0 ? 'border-color:#fde68a;' : '' ?>">
          <div class="ps-card-icon" style="background:<?= $stats['pending'] > 0 ? '#fef3c7' : '#f1f5f9' ?>;color:<?= $stats['pending'] > 0 ? '#d97706' : '#94a3b8' ?>;"><i class="dw dw-clock"></i></div>
          <div>
            <div class="ps-card-val"><?= number_format($stats['pending']) ?></div>
            <div class="ps-card-lbl">Pending Review</div>
          </div>
        </div>
        </a>
      </div>
      <div class="col-xl col-sm-4 col-6 mb-3">
        <div class="ps-card">
          <div class="ps-card-icon" style="background:#d1fae5;color:#059669;"><i class="dw dw-check-circle-2"></i></div>
          <div>
            <div class="ps-card-val"><?= number_format($stats['active']) ?></div>
            <div class="ps-card-lbl">Active Partners</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-4 col-6 mb-3">
        <div class="ps-card">
          <div class="ps-card-icon" style="background:#fef3c7;color:#d97706;"><i class="dw dw-wallet1"></i></div>
          <div>
            <div class="ps-card-val" style="font-size:1.15rem;"><?= number_format($stats['totalPledged'], 0) ?></div>
            <div class="ps-card-lbl">Total Pledged</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-4 col-6 mb-3">
        <div class="ps-card">
          <div class="ps-card-icon" style="background:#fee2e2;color:#ef4444;"><i class="dw dw-warning-2"></i></div>
          <div>
            <div class="ps-card-val"><?= number_format($stats['overdue']) ?></div>
            <div class="ps-card-lbl">Overdue</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Recent partners -->
      <div class="col-lg-8 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="lt-head" style="display:flex;align-items:center;justify-content:space-between;">
            <div><h3 class="lt-htitle">Recent Partners</h3><p class="lt-hsub">Latest partnership records</p></div>
            <a href="<?= base_url('partnershipListing') ?>" style="font-size:.8rem;color:var(--accent);text-decoration:none;padding-right:20px;">View all →</a>
          </div>
          <div style="overflow-x:auto;">
            <table class="table mb-0" id="ps-recent-table">
              <thead><tr>
                <th>Partner</th><th>Tier</th><th>Pledge</th><th>Status</th><th>Date</th>
              </tr></thead>
              <tbody>
                <?php foreach ($recent as $r): ?>
                <tr>
                  <td style="font-weight:600;color:var(--t1);"><?= esc($r->partner_name) ?></td>
                  <td>
                    <?php if ($r->tier_name): ?>
                    <span style="display:inline-block;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:700;background:<?= esc($r->tier_color) ?>22;color:<?= esc($r->tier_color) ?>;border:1px solid <?= esc($r->tier_color) ?>44;"><?= esc($r->tier_name) ?></span>
                    <?php else: ?>—<?php endif; ?>
                  </td>
                  <td style="font-weight:600;"><?= esc($r->currency) ?> <?= number_format((float)$r->pledge_amount, 2) ?></td>
                  <td><?php
                    $badges = ['pending'=>'badge-warning','active'=>'badge-success','completed'=>'badge-info','overdue'=>'badge-danger','cancelled'=>'badge-secondary'];
                    $bc = $badges[$r->status] ?? 'badge-secondary';
                    echo '<span class="badge badge-pill '.$bc.'">'.ucfirst(esc($r->status)).'</span>';
                  ?></td>
                  <td style="color:var(--t3);font-size:.8rem;"><?= date('M j, Y', strtotime($r->created_at)) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recent)): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--t3);padding:32px;">No partnership records yet</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Partners by tier -->
      <div class="col-lg-4 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="lt-head"><h3 class="lt-htitle">By Tier</h3><p class="lt-hsub">Active partners per tier</p></div>
          <div style="padding:8px 20px 20px;">
            <?php if (!empty($byTier)): ?>
              <?php foreach ($byTier as $t): ?>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:10px;">
                  <span style="width:10px;height:10px;border-radius:50%;background:<?= esc($t->tier_color) ?>;flex-shrink:0;display:inline-block;"></span>
                  <span style="font-size:.875rem;font-weight:600;color:var(--t1);"><?= esc($t->tier_name ?? 'Unassigned') ?></span>
                </div>
                <div style="text-align:right;">
                  <div style="font-size:.875rem;font-weight:700;color:var(--t1);"><?= $t->total ?> partners</div>
                  <div style="font-size:.75rem;color:var(--t3);">$<?= number_format((float)$t->pledged, 0) ?> pledged</div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align:center;color:var(--t3);padding:32px 0;">No active partners yet</p>
            <?php endif; ?>
          </div>
        </div>

        <div style="margin-top:16px;">
          <a href="<?= base_url('partnershipListing') ?>" class="btn btn-light" style="width:100%;margin-bottom:8px;"><i class="dw dw-group"></i> Manage Partners</a>
          <a href="<?= base_url('partnershipTiers') ?>" class="btn btn-light" style="width:100%;"><i class="dw dw-settings"></i> Manage Tiers</a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.ps-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:18px 20px;display:flex;align-items:center;gap:16px;box-shadow:var(--shadow-sm);}
.ps-card-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
.ps-card-val{font-size:1.5rem;font-weight:800;color:var(--t1);line-height:1.1;}
.ps-card-lbl{font-size:.78rem;color:var(--t3);margin-top:2px;font-weight:500;}
.lt-head{padding:16px 20px 0;}
.lt-htitle{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.lt-hsub{font-size:.78rem;color:var(--t3);margin:0 0 14px;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
.lt-cta{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;}
#ps-recent-table thead th{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 16px;background:#f8fafc;}
#ps-recent-table tbody td{padding:10px 16px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle;}
#ps-recent-table tbody tr:last-child td{border-bottom:none!important;}
</style>
