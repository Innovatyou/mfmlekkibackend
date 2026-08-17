<?php $session = session(); ?>

<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- ── Page header ── -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= esc($churchname) ?> <?= $locale['overview'] ?></h1>
        <p class="page-subtitle"><?= date('l, F j, Y') ?> &mdash; Welcome back, <strong><?= esc($session->get('name')) ?></strong></p>
      </div>
      <a href="<?= base_url() ?>/donations" class="btn btn-primary btn-sm" style="border-radius:8px;font-weight:600;padding:8px 18px;">
        <i class="dw dw-wallet1" style="margin-right:6px;"></i>View Donations
      </a>
    </div>

    <!-- ── Row 1: Count metrics ── -->
    <div class="row" style="margin-bottom:4px;">

      <!-- Branches -->
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <a href="<?= base_url() ?>/branchesListing" style="text-decoration:none;">
          <div class="dash-card">
            <div class="dash-card-icon" style="background:linear-gradient(135deg,#06b6d4,#0891b2);">
              <i class="dw dw-house-1"></i>
            </div>
            <div class="dash-card-body">
              <div class="dash-card-value"><?= number_format($branches) ?></div>
              <div class="dash-card-label"><?= $locale['all_church_locations'] ?></div>
            </div>
            <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
          </div>
        </a>
      </div>

      <!-- Members -->
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <a href="<?= base_url() ?>/membersListing" style="text-decoration:none;">
          <div class="dash-card">
            <div class="dash-card-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">
              <i class="dw dw-user1"></i>
            </div>
            <div class="dash-card-body">
              <div class="dash-card-value"><?= number_format($members) ?></div>
              <div class="dash-card-label"><?= $locale['total_members'] ?></div>
            </div>
            <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
          </div>
        </a>
      </div>

      <!-- Groups -->
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <a href="<?= base_url() ?>/groups" style="text-decoration:none;">
          <div class="dash-card">
            <div class="dash-card-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
              <i class="dw dw-group"></i>
            </div>
            <div class="dash-card-body">
              <div class="dash-card-value"><?= number_format($groups) ?></div>
              <div class="dash-card-label"><?= $locale['total_groups'] ?></div>
            </div>
            <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
          </div>
        </a>
      </div>

      <!-- Total donations count -->
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <a href="<?= base_url() ?>/donations" style="text-decoration:none;">
          <div class="dash-card">
            <div class="dash-card-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
              <i class="dw dw-wallet1"></i>
            </div>
            <div class="dash-card-body">
              <div class="dash-card-value"><?= number_format($donations) ?></div>
              <div class="dash-card-label"><?= $locale['total_donations'] ?></div>
            </div>
            <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
          </div>
        </a>
      </div>

    </div>

    <!-- ── Row 2: Donation amounts ── -->
    <div class="row mb-20">

      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="amount-card" style="--accent-c:#06b6d4;--accent-bg:#ecfeff;">
          <div class="amount-card-label"><?= $locale['weekly_donations'] ?></div>
          <div class="amount-card-value"><?= $currencycode ?> <?= number_format($donationsthisweek, 2) ?></div>
          <div class="amount-card-sub">This week</div>
          <div class="amount-card-bar" style="background:linear-gradient(90deg,#06b6d4,#0891b2);"></div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="amount-card" style="--accent-c:#8b5cf6;--accent-bg:#f5f3ff;">
          <div class="amount-card-label"><?= $locale['monthly_donations'] ?></div>
          <div class="amount-card-value"><?= $currencycode ?> <?= number_format($donationsthismonth, 2) ?></div>
          <div class="amount-card-sub"><?= date('F Y') ?></div>
          <div class="amount-card-bar" style="background:linear-gradient(90deg,#8b5cf6,#7c3aed);"></div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="amount-card" style="--accent-c:#f59e0b;--accent-bg:#fffbeb;">
          <div class="amount-card-label"><?= $locale['yearly_donations'] ?></div>
          <div class="amount-card-value"><?= $currencycode ?> <?= number_format($donationsthisyear, 2) ?></div>
          <div class="amount-card-sub"><?= date('Y') ?></div>
          <div class="amount-card-bar" style="background:linear-gradient(90deg,#f59e0b,#d97706);"></div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="amount-card" style="--accent-c:#10b981;--accent-bg:#ecfdf5;">
          <div class="amount-card-label"><?= $locale['total_donations'] ?> (All Time)</div>
          <div class="amount-card-value"><?= $currencycode ?> <?= number_format($alldonations, 2) ?></div>
          <div class="amount-card-sub">All time</div>
          <div class="amount-card-bar" style="background:linear-gradient(90deg,#10b981,#059669);"></div>
        </div>
      </div>

    </div>

    <!-- ── Member Care Intelligence ── -->
    <div style="margin-bottom:24px;">

      <!-- Section heading -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;">
            <i class="dw dw-heart"></i>
          </div>
          <div>
            <div style="font-size:.95rem;font-weight:700;color:var(--t1);">Member Care Intelligence</div>
            <div style="font-size:.76rem;color:var(--t3);">Pastoral care insights at a glance</div>
          </div>
        </div>
        <a href="<?= base_url('memberCare') ?>"
          style="font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;
            padding:6px 14px;border:1px solid var(--border);border-radius:7px;transition:background .15s;"
          onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
          Full Care Dashboard &rarr;
        </a>
      </div>

      <!-- Care stat mini-cards -->
      <div class="row" style="margin-bottom:16px;">

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('memberCare') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="dw dw-calendar1"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= $care_stats['upcoming_bdays'] ?></div>
                <div class="dash-card-label">Birthdays (7 days)</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('memberCare') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#ef4444,#f97316);">
                <i class="dw dw-alarm-clock"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($care_stats['never_cared']) ?></div>
                <div class="dash-card-label">Never Cared For</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('memberCare') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-heart"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($care_stats['recently_cared']) ?></div>
                <div class="dash-card-label">Cared (30 days)</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('memberCare') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#8b5cf6,#ec4899);">
                <i class="dw dw-check-circle-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($care_stats['total_events']) ?></div>
                <div class="dash-card-label">Care Interactions</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

      </div>

      <!-- Needs Attention + Upcoming Birthdays -->
      <div class="row">

        <!-- Needs Attention -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#ef4444;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Needs Attention</span>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">No care in 90+ days</span>
            </div>
            <?php if (empty($care_needs)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">
                All members have recent care logged
              </div>
            <?php else: ?>
              <?php foreach ($care_needs as $m): ?>
                <?php $init = strtoupper(substr($m->firstname ?? '', 0, 1) . substr($m->lastname ?? '', 0, 1)); ?>
                <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);position:relative;transition:background .12s;"
                  onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;color:#fff;font-weight:700;font-size:.78rem;
                    background:linear-gradient(135deg,#ef4444,#f97316);"><?= $init ?></div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($m->firstname . ' ' . $m->lastname) ?>
                    </div>
                    <div style="font-size:.74rem;color:var(--t3);">
                      <?= $m->last_care_at ? 'Last: ' . date('M j, Y', strtotime($m->last_care_at)) : 'No care record' ?>
                    </div>
                  </div>
                  <a href="<?= base_url('memberCareProfile/' . $m->id) ?>"
                    style="font-size:.75rem;color:var(--accent);font-weight:600;text-decoration:none;
                      padding:4px 10px;border:1px solid var(--border);border-radius:6px;white-space:nowrap;
                      position:relative;z-index:1;">
                    Care &rarr;
                  </a>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('memberCare') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">
                  View all &rarr;
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Upcoming Birthdays</span>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">Next 7 days</span>
            </div>
            <?php if (empty($care_birthdays)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">
                No birthdays in the next 7 days
              </div>
            <?php else: ?>
              <?php foreach ($care_birthdays as $b): ?>
                <?php
                  $init  = strtoupper(substr($b->firstname ?? '', 0, 1) . substr($b->lastname ?? '', 0, 1));
                  $today = ($b->days_until === 0);
                ?>
                <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);
                  <?= $today ? 'background:#ecfdf5;' : '' ?>transition:background .12s;"
                  onmouseover="this.style.background='<?= $today ? '#d1fae5' : '#f8fafc' ?>'" onmouseout="this.style.background='<?= $today ? '#ecfdf5' : 'transparent' ?>'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;color:#fff;font-weight:700;font-size:.78rem;
                    background:linear-gradient(135deg,#f59e0b,#f97316);"><?= $init ?></div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($b->firstname . ' ' . $b->lastname) ?>
                    </div>
                    <div style="font-size:.74rem;color:var(--t3);"><?= esc($b->email) ?></div>
                  </div>
                  <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.8rem;font-weight:700;color:<?= $today ? '#10b981' : '#f97316' ?>;">
                      <?= $today ? '🎂 Today!' : ($b->days_until === 1 ? 'Tomorrow' : 'In ' . $b->days_until . ' days') ?>
                    </div>
                    <div style="font-size:.72rem;color:var(--t3);"><?= esc($b->bday_date) ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('memberCare') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">
                  View all &rarr;
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Counseling & Case Tracker ── -->
    <div style="margin-bottom:24px;">

      <!-- Section heading -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#6366f1);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;">
            <i class="dw dw-open-book"></i>
          </div>
          <div>
            <div style="font-size:.95rem;font-weight:700;color:var(--t1);">Counseling &amp; Case Tracker</div>
            <div style="font-size:.76rem;color:var(--t3);">Active cases, follow-ups &amp; reminders</div>
          </div>
        </div>
        <a href="<?= base_url('counseling') ?>"
          style="font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;
            padding:6px 14px;border:1px solid var(--border);border-radius:7px;transition:background .15s;"
          onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
          Full Case Dashboard &rarr;
        </a>
      </div>

      <!-- Counseling stat mini-cards -->
      <div class="row" style="margin-bottom:16px;">

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('counseling') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                <i class="dw dw-open-book"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($counsel_stats['open']) ?></div>
                <div class="dash-card-label">Open Cases</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('counseling') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="dw dw-refresh-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($counsel_stats['in_progress']) ?></div>
                <div class="dash-card-label">In Progress</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('counseling') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $counsel_stats['reminders_today'] > 0 ? 'linear-gradient(135deg,#ef4444,#f97316)' : 'linear-gradient(135deg,#10b981,#06b6d4)' ?>;">
                <i class="dw dw-alarm-clock"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($counsel_stats['reminders_today']) ?></div>
                <div class="dash-card-label">Reminders Today</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('counseling') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $counsel_stats['overdue'] > 0 ? 'linear-gradient(135deg,#ef4444,#dc2626)' : 'linear-gradient(135deg,#8b5cf6,#6366f1)' ?>;">
                <i class="dw dw-warning-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($counsel_stats['overdue']) ?></div>
                <div class="dash-card-label">Overdue Follow-ups</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

      </div>

      <!-- Today's Reminders + Upcoming -->
      <div class="row">

        <!-- Today's Reminders -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:<?= $counsel_stats['reminders_today'] > 0 ? '#ef4444' : '#10b981' ?>;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Today's Reminders</span>
              <?php if ($counsel_stats['reminders_today'] > 0): ?>
                <span style="background:#fef2f2;color:#dc2626;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:4px;">
                  <?= $counsel_stats['reminders_today'] ?> pending
                </span>
              <?php endif; ?>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;"><?= date('M j, Y') ?></span>
            </div>
            <?php if (empty($counsel_today)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">
                No reminders scheduled for today
              </div>
            <?php else: ?>
              <?php foreach ($counsel_today as $r): ?>
                <div style="display:flex;align-items:flex-start;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .12s;"
                  onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;font-size:.85rem;margin-top:1px;">
                    <i class="dw dw-alarm-clock"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($r->member_name) ?>
                    </div>
                    <div style="font-size:.76rem;color:var(--t2);margin-top:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                      <?= esc($r->case_title) ?>
                    </div>
                    <?php if ($r->note): ?>
                      <div style="font-size:.74rem;color:var(--t3);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= esc($r->note) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <a href="<?= base_url('counselingCase/' . $r->case_id) ?>"
                    style="font-size:.75rem;color:var(--accent);font-weight:600;text-decoration:none;
                      padding:4px 10px;border:1px solid var(--border);border-radius:6px;white-space:nowrap;flex-shrink:0;">
                    View &rarr;
                  </a>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('counseling') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">
                  View all &rarr;
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Upcoming Reminders (next 7 days) -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#3b82f6;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Upcoming Follow-ups</span>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">Next 7 days</span>
            </div>
            <?php if (empty($counsel_upcoming)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">
                No follow-ups scheduled in the next 7 days
              </div>
            <?php else: ?>
              <?php foreach ($counsel_upcoming as $u): ?>
                <?php
                  $rdate    = strtotime($u->reminder_date);
                  $diff     = (int) floor(($rdate - strtotime(date('Y-m-d'))) / 86400);
                  $isToday  = ($diff === 0);
                  $overdue  = ($diff < 0);
                  $label    = $isToday ? 'Today' : ($diff === 1 ? 'Tomorrow' : ($overdue ? abs($diff) . 'd overdue' : 'In ' . $diff . 'd'));
                  $labelCol = $overdue ? '#ef4444' : ($isToday ? '#f59e0b' : 'var(--t3)');
                ?>
                <div style="display:flex;align-items:flex-start;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .12s;"
                  onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;background:linear-gradient(135deg,<?= $overdue ? '#ef4444,#f97316' : '#3b82f6,#6366f1' ?>);color:#fff;font-size:.85rem;margin-top:1px;">
                    <i class="dw dw-calendar1"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($u->member_name) ?>
                    </div>
                    <div style="font-size:.76rem;color:var(--t2);margin-top:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                      <?= esc($u->case_title) ?>
                    </div>
                  </div>
                  <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.78rem;font-weight:700;color:<?= $labelCol ?>;"><?= $label ?></div>
                    <div style="font-size:.72rem;color:var(--t3);"><?= date('M j', $rdate) ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('counseling') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">
                  View all &rarr;
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Church Marketplace ── -->
    <div style="margin-bottom:24px;">

      <!-- Section heading -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#10b981,#06b6d4);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;">
            <i class="dw dw-shopping-cart"></i>
          </div>
          <div>
            <div style="font-size:.95rem;font-weight:700;color:var(--t1);">Church Marketplace</div>
            <div style="font-size:.76rem;color:var(--t3);">Buy, sell &amp; share within the community</div>
          </div>
        </div>
        <a href="<?= base_url('marketplace') ?>"
          style="font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;
            padding:6px 14px;border:1px solid var(--border);border-radius:7px;transition:background .15s;"
          onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
          Full Marketplace &rarr;
        </a>
      </div>

      <!-- Marketplace stat mini-cards -->
      <div class="row" style="margin-bottom:16px;">

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('marketplace') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-check-circle-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($market_stats['active']) ?></div>
                <div class="dash-card-label">Active Listings</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('marketplace') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $market_stats['pending'] > 0 ? 'linear-gradient(135deg,#f59e0b,#f97316)' : 'linear-gradient(135deg,#94a3b8,#64748b)' ?>;">
                <i class="dw dw-clock"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($market_stats['pending']) ?></div>
                <div class="dash-card-label">Pending Review</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('marketplace') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-tag"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($market_stats['sold']) ?></div>
                <div class="dash-card-label">Sold Items</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('marketplace') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $market_stats['inquiries_unread'] > 0 ? 'linear-gradient(135deg,#ef4444,#f97316)' : 'linear-gradient(135deg,#10b981,#06b6d4)' ?>;">
                <i class="dw dw-chat-bubble"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($market_stats['inquiries_unread']) ?></div>
                <div class="dash-card-label">Unread Inquiries</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

      </div>

      <!-- Recent Listings + Pending Approvals -->
      <div class="row">

        <!-- Recent Listings -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#10b981;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Recent Listings</span>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">Latest 5 items</span>
            </div>
            <?php if (empty($market_recent)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">No marketplace listings yet</div>
            <?php else: ?>
              <?php foreach ($market_recent as $item): ?>
                <?php
                  $statusColor = ['active'=>'#10b981','pending'=>'#f59e0b','sold'=>'#6366f1','inactive'=>'#94a3b8'];
                  $sc = $statusColor[$item->status] ?? '#94a3b8';
                  $priceLabel = $item->is_free ? 'Free' : ($market_sym . number_format((float)$item->price, 2));
                ?>
                <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .12s;"
                  onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;background:linear-gradient(135deg,#10b981,#06b6d4);color:#fff;font-size:.85rem;">
                    <i class="dw dw-shopping-cart"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($item->title) ?>
                    </div>
                    <div style="font-size:.74rem;color:var(--t3);">
                      by <?= esc($item->seller_name) ?> &middot; <?= esc($item->category_name ?? 'Uncategorised') ?>
                    </div>
                  </div>
                  <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.82rem;font-weight:700;color:var(--t1);"><?= $priceLabel ?></div>
                    <span style="font-size:.68rem;font-weight:600;padding:1px 7px;border-radius:10px;
                      background:<?= $sc ?>22;color:<?= $sc ?>;"><?= ucfirst($item->status) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('marketplace') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">View all &rarr;</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Approvals -->
        <div class="col-xl-6 col-lg-6 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:<?= $market_stats['pending'] > 0 ? '#f59e0b' : '#10b981' ?>;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Pending Approvals</span>
              <?php if ($market_stats['pending'] > 0): ?>
                <span style="background:#fef3c7;color:#92400e;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:4px;">
                  <?= $market_stats['pending'] ?> waiting
                </span>
              <?php endif; ?>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">Need review</span>
            </div>
            <?php if (empty($market_pending)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">No items pending approval</div>
            <?php else: ?>
              <?php foreach ($market_pending as $item): ?>
                <?php
                  $priceLabel = $item->is_free ? 'Free' : ($market_sym . number_format((float)$item->price, 2));
                  $condLabel  = ['new'=>'New','used'=>'Used','refurbished'=>'Refurb'][$item->item_condition] ?? ucfirst($item->item_condition ?? '');
                ?>
                <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .12s;"
                  onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                  <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                    justify-content:center;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;font-size:.85rem;">
                    <i class="dw dw-clock"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      <?= esc($item->title) ?>
                    </div>
                    <div style="font-size:.74rem;color:var(--t3);">
                      <?= esc($item->seller_name) ?> &middot; <?= $condLabel ?> &middot; <?= esc($item->category_name ?? '—') ?>
                    </div>
                  </div>
                  <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.82rem;font-weight:700;color:var(--t1);"><?= $priceLabel ?></div>
                    <a href="<?= base_url('marketplace') ?>"
                      style="font-size:.72rem;color:#f59e0b;font-weight:600;text-decoration:none;">Review &rarr;</a>
                  </div>
                </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('marketplace') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">View all &rarr;</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Partnership ── -->
    <div style="margin-bottom:24px;">

      <!-- Section heading -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#f97316);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;">
            <i class="dw dw-handshake"></i>
          </div>
          <div>
            <div style="font-size:.95rem;font-weight:700;color:var(--t1);">Partnership</div>
            <div style="font-size:.76rem;color:var(--t3);">Pledge tracking &amp; partner overview</div>
          </div>
        </div>
        <a href="<?= base_url('partnership') ?>"
          style="font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;
            padding:6px 14px;border:1px solid var(--border);border-radius:7px;transition:background .15s;"
          onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
          Full Overview &rarr;
        </a>
      </div>

      <!-- Partnership stat mini-cards -->
      <div class="row" style="margin-bottom:16px;">

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('partnershipListing') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-group"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($partner_stats['total']) ?></div>
                <div class="dash-card-label">Total Partners</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('partnershipListing') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
                <i class="dw dw-check-circle-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($partner_stats['active']) ?></div>
                <div class="dash-card-label">Active Partners</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('partnershipListing') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $partner_stats['pending'] > 0 ? 'linear-gradient(135deg,#f59e0b,#d97706)' : 'linear-gradient(135deg,#94a3b8,#64748b)' ?>;">
                <i class="dw dw-clock"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($partner_stats['pending']) ?></div>
                <div class="dash-card-label">Pending Review</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
          <a href="<?= base_url('partnershipListing') ?>" style="text-decoration:none;">
            <div class="dash-card">
              <div class="dash-card-icon" style="background:<?= $partner_stats['overdue'] > 0 ? 'linear-gradient(135deg,#ef4444,#f97316)' : 'linear-gradient(135deg,#94a3b8,#64748b)' ?>;">
                <i class="dw dw-warning-2"></i>
              </div>
              <div class="dash-card-body">
                <div class="dash-card-value"><?= number_format($partner_stats['overdue']) ?></div>
                <div class="dash-card-label">Overdue</div>
              </div>
              <div class="dash-card-arrow"><i class="dw dw-next-button" style="font-size:.75rem;color:#cbd5e1;"></i></div>
            </div>
          </a>
        </div>

      </div>

      <!-- Recent Partners + Tier Breakdown -->
      <div class="row">

        <!-- Recent Partners -->
        <div class="col-xl-7 col-lg-7 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#6366f1;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Recent Partners</span>
              <span style="font-size:.74rem;color:var(--t3);margin-left:auto;">Latest 5</span>
            </div>
            <?php if (empty($partner_recent)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">No partnership records yet</div>
            <?php else: ?>
              <?php foreach ($partner_recent as $p):
                $statusColor = ['pending'=>'#d97706','active'=>'#10b981','overdue'=>'#ef4444','completed'=>'#6366f1','cancelled'=>'#94a3b8'];
                $sc = $statusColor[$p->status] ?? '#94a3b8';
                $init = strtoupper(substr($p->partner_name, 0, 2));
              ?>
              <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .12s;"
                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                <div style="width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
                  justify-content:center;color:#fff;font-weight:700;font-size:.75rem;
                  background:<?= $p->tier_color ? 'linear-gradient(135deg,' . esc($p->tier_color) . 'cc,' . esc($p->tier_color) . ')' : 'linear-gradient(135deg,#6366f1,#8b5cf6)' ?>;">
                  <?= esc($init) ?>
                </div>
                <div style="flex:1;min-width:0;">
                  <div style="font-size:.87rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= esc($p->partner_name) ?>
                  </div>
                  <div style="font-size:.74rem;color:var(--t3);">
                    <?php if ($p->tier_name): ?>
                      <span style="display:inline-block;padding:0 6px;border-radius:10px;font-size:.68rem;font-weight:700;
                        background:<?= esc($p->tier_color) ?>22;color:<?= esc($p->tier_color) ?>;border:1px solid <?= esc($p->tier_color) ?>44;margin-right:4px;">
                        <?= esc($p->tier_name) ?>
                      </span>
                    <?php endif; ?>
                    <?= date('M j, Y', strtotime($p->created_at)) ?>
                  </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                  <div style="font-size:.82rem;font-weight:700;color:var(--t1);">
                    <?= esc($p->currency) ?> <?= number_format((float)$p->pledge_amount, 0) ?>
                  </div>
                  <span style="font-size:.68rem;font-weight:600;padding:1px 7px;border-radius:10px;
                    background:<?= $sc ?>22;color:<?= $sc ?>;"><?= ucfirst($p->status) ?></span>
                </div>
              </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('partnershipListing') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">View all &rarr;</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Tier Breakdown -->
        <div class="col-xl-5 col-lg-5 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
            <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--border);">
              <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></div>
              <span style="font-size:.88rem;font-weight:700;color:var(--t1);">Active Partners by Tier</span>
            </div>
            <?php if (empty($partner_tiers)): ?>
              <div style="padding:28px;text-align:center;color:var(--t3);font-size:.84rem;">No active partners</div>
            <?php else: ?>
              <?php
                $maxPledged = max(array_map(fn($t) => (float)($t->pledged ?? 0), $partner_tiers)) ?: 1;
              ?>
              <?php foreach ($partner_tiers as $t):
                $tierColor   = $t->tier_color ?: '#6366f1';
                $tierName    = $t->tier_name ?: 'No Tier';
                $count       = (int)($t->total ?? 0);
                $pledged     = (float)($t->pledged ?? 0);
                $barPct      = round(($pledged / $maxPledged) * 100);
              ?>
              <div style="padding:13px 18px;border-bottom:1px solid var(--border);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                  <div style="display:flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;border-radius:50%;background:<?= esc($tierColor) ?>;display:inline-block;flex-shrink:0;"></span>
                    <span style="font-size:.86rem;font-weight:600;color:var(--t1);"><?= esc($tierName) ?></span>
                    <span style="font-size:.72rem;color:var(--t3);background:#f1f5f9;padding:1px 7px;border-radius:10px;"><?= $count ?> partner<?= $count !== 1 ? 's' : '' ?></span>
                  </div>
                  <span style="font-size:.82rem;font-weight:700;color:var(--t1);"><?= number_format($pledged, 0) ?></span>
                </div>
                <div style="height:5px;border-radius:4px;background:#f1f5f9;overflow:hidden;">
                  <div style="height:100%;width:<?= $barPct ?>%;border-radius:4px;background:<?= esc($tierColor) ?>;transition:width .4s;"></div>
                </div>
              </div>
              <?php endforeach; ?>
              <div style="padding:10px 18px;text-align:right;">
                <a href="<?= base_url('partnershipTiers') ?>" style="font-size:.78rem;color:var(--accent);font-weight:600;text-decoration:none;">Manage Tiers &rarr;</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Recent Donations table ── -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;"><?= $locale['recent_donations'] ?></h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Last 10 transactions</p>
        </div>
        <a href="<?= base_url() ?>/donations" style="font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;padding:6px 14px;border:1px solid var(--border);border-radius:7px;transition:background .15s;"
          onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
          View all &rarr;
        </a>
      </div>

      <div style="overflow-x:auto;">
        <table class="data-table table nowrap" style="margin:0;width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['reason'] ?></th>
              <th><?= $locale['email'] ?></th>
              <th><?= $locale['name'] ?></th>
              <th><?= $locale['reference'] ?></th>
              <th><?= $locale['amount'] ?></th>
              <th><?= $locale['method'] ?></th>
              <th><?= $locale['date'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; foreach ($recentdonations as $res): ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $count ?></td>
              <td><?= esc($res->reason ?: '—') ?></td>
              <td style="color:var(--accent);"><?= esc($res->email) ?></td>
              <td style="font-weight:500;"><?= esc($res->name) ?></td>
              <td style="font-family:monospace;font-size:.8rem;color:var(--t2);"><?= esc($res->reference) ?></td>
              <td style="font-weight:700;color:var(--t1);"><?= $currencycode ?> <?= number_format((float)$res->amount, 2) ?></td>
              <td>
                <?php
                  $method = strtolower($res->method ?? '');
                  $badge = 'badge-secondary';
                  if (str_contains($method, 'paystack'))    $badge = 'badge-success';
                  elseif (str_contains($method, 'flutter')) $badge = 'badge-info';
                  elseif (str_contains($method, 'stripe'))  $badge = 'badge-warning';
                  elseif (str_contains($method, 'cash'))    $badge = 'badge-secondary';
                ?>
                <span class="badge badge-pill <?= $badge ?>"><?= esc($res->method ?: 'N/A') ?></span>
              </td>
              <td style="color:var(--t3);font-size:.82rem;"><?= esc($res->date) ?></td>
            </tr>
            <?php $count++; endforeach; ?>
            <?php if (empty($recentdonations)): ?>
            <tr>
              <td colspan="8" style="text-align:center;padding:40px;color:var(--t3);">
                <i class="dw dw-wallet1" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                No donations recorded yet
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<style>
  /* ── Dashboard cards ── */
  .dash-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    transition: box-shadow .2s, transform .2s;
    cursor: pointer;
  }
  .dash-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }
  .dash-card-icon {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #fff; font-size: 1.35rem;
  }
  .dash-card-body { flex: 1; min-width: 0; }
  .dash-card-value {
    font-size: 1.6rem; font-weight: 800; color: var(--t1);
    line-height: 1.1; letter-spacing: -.02em;
  }
  .dash-card-label {
    font-size: .78rem; font-weight: 500; color: var(--t3);
    margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .dash-card-arrow { flex-shrink: 0; }

  /* ── Amount cards ── */
  .amount-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 18px 20px 14px;
    position: relative; overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }
  .amount-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
  .amount-card-label {
    font-size: .75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--t3); margin-bottom: 8px;
  }
  .amount-card-value {
    font-size: 1.45rem; font-weight: 800; color: var(--t1);
    letter-spacing: -.02em; line-height: 1.1;
  }
  .amount-card-sub {
    font-size: .75rem; color: var(--t3); margin-top: 4px;
  }
  .amount-card-bar {
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px; border-radius: 0 0 var(--radius) var(--radius);
  }

  /* ── Card entrance animation ── */
  .dash-card, .amount-card {
    animation: fadeUp .35s ease both;
  }
  .col-xl-3:nth-child(1) .dash-card,  .col-xl-3:nth-child(1) .amount-card  { animation-delay: .05s; }
  .col-xl-3:nth-child(2) .dash-card,  .col-xl-3:nth-child(2) .amount-card  { animation-delay: .10s; }
  .col-xl-3:nth-child(3) .dash-card,  .col-xl-3:nth-child(3) .amount-card  { animation-delay: .15s; }
  .col-xl-3:nth-child(4) .dash-card,  .col-xl-3:nth-child(4) .amount-card  { animation-delay: .20s; }
</style>
