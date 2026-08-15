<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Counseling &amp; Case Tracker</h1>
        <p class="page-subtitle">Confidential pastoral counseling records &amp; follow-up management</p>
      </div>
      <a href="<?= base_url('newCounselingCase') ?>" class="btn btn-primary">
        <i class="dw dw-add" style="margin-right:6px;"></i>Open New Case
      </a>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="mc-alert mc-alert-success" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#d1fae5;color:#065f46;border-radius:9px;margin-bottom:20px;">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button style="margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;color:inherit;" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mc-alert" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#fee2e2;color:#7f1d1d;border-radius:9px;margin-bottom:20px;">
        <i class="dw dw-warning-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button style="margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;color:inherit;" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <!-- Confidentiality Notice -->
    <div style="display:flex;align-items:center;gap:10px;padding:11px 16px;background:#fef3c7;border:1px solid #fcd34d;border-radius:9px;margin-bottom:22px;font-size:.84rem;color:#78350f;">
      <i class="dw dw-padlock" style="font-size:1rem;"></i>
      <span><strong>Confidential Module</strong> — All counseling records are private and restricted to authorized pastoral staff only.</span>
    </div>

    <!-- KPI Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:24px;">

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
          <i class="dw dw-files"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['total'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">Total Cases</div>
      </div>

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#3b82f6,#06b6d4);">
          <i class="dw dw-inbox"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['open'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">Open</div>
      </div>

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#f59e0b,#f97316);">
          <i class="dw dw-refresh-2"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['in_progress'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">In Progress</div>
      </div>

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#10b981,#06b6d4);">
          <i class="dw dw-check-circle-2"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['closed_month'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">Closed This Month</div>
      </div>

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#ec4899,#f59e0b);">
          <i class="dw dw-alarm-clock"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['reminders_today'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">Reminders Today</div>
      </div>

      <div class="mc-stat-card" style="background:#fff;border-radius:12px;padding:18px 16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #e2e8f0;">
        <div class="mc-stat-icon" style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:10px;background:linear-gradient(135deg,#ef4444,#f97316);">
          <i class="dw dw-warning-2"></i>
        </div>
        <div style="font-size:1.8rem;font-weight:800;color:#0f172a;line-height:1;"><?= $stats['overdue'] ?></div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">Overdue Follow-ups</div>
      </div>

    </div>

    <!-- Middle Row: Today's Reminders + Upcoming -->
    <?php if (!empty($today_reminders) || !empty($upcoming_reminders)): ?>
    <div class="cd-split-grid" style="display:grid;gap:16px;margin-bottom:24px;">

      <!-- Today -->
      <div class="card-box" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <h5 style="font-size:.9rem;font-weight:700;color:#0f172a;margin:0;">Today's Follow-ups</h5>
          <span style="background:#fef3c7;color:#78350f;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;"><?= count($today_reminders) ?></span>
        </div>
        <?php if (empty($today_reminders)): ?>
          <p style="color:#94a3b8;font-size:.85rem;text-align:center;padding:16px 0;">No reminders for today.</p>
        <?php else: ?>
          <?php foreach ($today_reminders as $r): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
              <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0;margin-top:5px;"></div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.84rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  <?= esc($r->member_name) ?>
                </div>
                <div style="font-size:.77rem;color:#94a3b8;"><?= esc($r->case_title) ?></div>
                <?php if ($r->note): ?>
                  <div style="font-size:.77rem;color:#475569;margin-top:2px;"><?= esc($r->note) ?></div>
                <?php endif; ?>
              </div>
              <a href="<?= base_url('counselingCase/' . $r->case_id) ?>" style="font-size:.78rem;color:#6366f1;white-space:nowrap;">View</a>
              <a href="<?= base_url('counselingReminderDone/' . $r->id) ?>" style="font-size:.78rem;color:#10b981;white-space:nowrap;">Done</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Upcoming 7 days -->
      <div class="card-box" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <h5 style="font-size:.9rem;font-weight:700;color:#0f172a;margin:0;">Upcoming (7 Days)</h5>
          <span style="background:#e0f2fe;color:#0c4a6e;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;"><?= count($upcoming_reminders) ?></span>
        </div>
        <?php if (empty($upcoming_reminders)): ?>
          <p style="color:#94a3b8;font-size:.85rem;text-align:center;padding:16px 0;">No upcoming reminders.</p>
        <?php else: ?>
          <?php foreach ($upcoming_reminders as $r): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
              <div style="font-size:.72rem;font-weight:700;color:#6366f1;min-width:36px;padding-top:2px;"><?= date('M j', strtotime($r->reminder_date)) ?></div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.84rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  <?= esc($r->member_name) ?>
                </div>
                <div style="font-size:.77rem;color:#94a3b8;"><?= esc($r->case_title) ?></div>
              </div>
              <a href="<?= base_url('counselingCase/' . $r->case_id) ?>" style="font-size:.78rem;color:#6366f1;">View</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
    <?php endif; ?>

    <!-- Upcoming Video Sessions -->
    <?php
      $platformColor = ['zoom'=>'#2D8CFF','google_meet'=>'#00897B','teams'=>'#6264A7','whatsapp'=>'#25D366'];
      $platformLabel = ['zoom'=>'Zoom','google_meet'=>'Google Meet','teams'=>'Microsoft Teams','whatsapp'=>'WhatsApp'];
      $platformLogo  = [
        'zoom'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 8.5v7L20 18V6l-4.5 2.5zm-11 .5a2 2 0 012-2h7a2 2 0 012 2v6a2 2 0 01-2 2h-7a2 2 0 01-2-2V9z"/></svg>',
        'google_meet' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1v-3.5l4 4v-11l-4 4z"/></svg>',
        'teams'       => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20 7h-4V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm-8-3h4v3h-4V4zm8 14H4V9h16v9z"/></svg>',
        'whatsapp'    => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26C2.157 5.45 6.592 1.016 12.044 1.016c2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
      ];
    ?>
    <?php if (!empty($upcoming_video)): ?>
    <div class="card-box" style="padding:20px;margin-bottom:24px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h5 style="font-size:.9rem;font-weight:700;color:#0f172a;margin:0;">
          <i class="dw dw-video-camera" style="color:#6366f1;margin-right:6px;"></i>Upcoming Video Sessions
        </h5>
        <span style="background:#e0e7ff;color:#4338ca;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;"><?= count($upcoming_video) ?></span>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;">
        <?php foreach ($upcoming_video as $vs):
          $plat  = $vs->meeting_platform ?? '';
          $pc    = $platformColor[$plat] ?? '#6366f1';
          $pl    = $platformLabel[$plat] ?? 'Video';
          $pico  = $platformLogo[$plat]  ?? '';
          $dt    = $vs->meeting_scheduled_at ? date('M j, Y \a\t g:i A', strtotime($vs->meeting_scheduled_at)) : '—';
          $today = $vs->meeting_scheduled_at && date('Y-m-d', strtotime($vs->meeting_scheduled_at)) === date('Y-m-d');
        ?>
          <div style="background:#f8fafc;border:1px solid <?= $today ? $pc : '#e2e8f0' ?>;border-radius:10px;padding:14px;position:relative;">
            <?php if ($today): ?>
              <span style="position:absolute;top:10px;right:10px;font-size:.68rem;font-weight:700;background:<?= $pc ?>22;color:<?= $pc ?>;padding:2px 8px;border-radius:20px;">Today</span>
            <?php endif; ?>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
              <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:.73rem;font-weight:700;background:<?= $pc ?>22;color:<?= $pc ?>;">
                <?= $pico ?><?= $pl ?>
              </span>
              <span style="font-size:.74rem;font-weight:700;color:#0369a1;"><?= $dt ?></span>
            </div>
            <div style="font-weight:600;font-size:.84rem;color:#0f172a;"><?= esc($vs->member_name) ?></div>
            <div style="font-size:.77rem;color:#94a3b8;margin-bottom:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($vs->case_title) ?></div>
            <div style="display:flex;gap:8px;">
              <a href="<?= base_url('counselingCase/' . $vs->case_id_ref) ?>"
                 style="font-size:.78rem;color:#6366f1;text-decoration:none;font-weight:600;">View Case</a>
              <?php if (!empty($vs->meeting_link)): ?>
                <a href="<?= esc($vs->meeting_link) ?>" target="_blank"
                   style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:<?= $pc ?>;color:#fff;border-radius:6px;font-size:.75rem;font-weight:600;text-decoration:none;">
                  Join <?= $pl ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Cases DataTable -->
    <div class="card-box" style="padding:20px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <h5 style="font-size:.95rem;font-weight:700;color:#0f172a;margin:0;">All Cases</h5>
        <a href="<?= base_url('newCounselingCase') ?>" class="btn btn-primary btn-sm">+ Open Case</a>
      </div>
      <div class="table-responsive">
        <table id="counseling_table" class="table" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Member</th>
              <th>Category</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Assigned To</th>
              <th>Next Follow-up</th>
              <th>Opened</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

  </div>
</div>

<style>
.mc-action-btn {
  display:inline-flex;align-items:center;justify-content:center;
  width:30px;height:30px;border-radius:7px;font-size:.85rem;
  text-decoration:none;transition:opacity .15s;
}
.mc-btn-view   { background:#e0f2fe;color:#0369a1; }
.mc-btn-delete { background:#fee2e2;color:#dc2626; }
.mc-action-btn:hover { opacity:.75; }
.cd-split-grid { grid-template-columns:1fr 1fr; }
@media(max-width:768px) {
  .cd-split-grid { grid-template-columns:1fr; }
}
</style>

