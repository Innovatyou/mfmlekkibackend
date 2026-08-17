<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Member Care Intelligence</h1>
        <p class="page-subtitle">Pastoral care tracking &amp; member engagement insights</p>
      </div>
      <a href="<?= base_url('membersListing') ?>" class="btn btn-primary">
        <i class="dw dw-user1" style="margin-right:6px;"></i>All Members
      </a>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="mc-alert mc-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="mc-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <!-- ── KPI Cards ── -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:16px;margin-bottom:24px;">

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
          <i class="dw dw-torso"></i>
        </div>
        <div class="mc-stat-value"><?= number_format($stats['total_members']) ?></div>
        <div class="mc-stat-label">Total Members</div>
      </div>

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
          <i class="dw dw-calendar1"></i>
        </div>
        <div class="mc-stat-value"><?= $stats['upcoming_bdays'] ?></div>
        <div class="mc-stat-label">Birthdays (14 days)</div>
      </div>

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
          <i class="dw dw-add-user"></i>
        </div>
        <div class="mc-stat-value"><?= $stats['new_members'] ?></div>
        <div class="mc-stat-label">New (30 days)</div>
      </div>

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#06b6d4,#6366f1);">
          <i class="dw dw-heart"></i>
        </div>
        <div class="mc-stat-value"><?= $stats['recently_cared'] ?></div>
        <div class="mc-stat-label">Recently Cared</div>
      </div>

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#ef4444,#f97316);">
          <i class="dw dw-alarm-clock"></i>
        </div>
        <div class="mc-stat-value"><?= $stats['never_cared'] ?></div>
        <div class="mc-stat-label">Never Cared For</div>
      </div>

      <div class="mc-stat-card">
        <div class="mc-stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#ec4899);">
          <i class="dw dw-check-circle-2"></i>
        </div>
        <div class="mc-stat-value"><?= number_format($stats['total_events']) ?></div>
        <div class="mc-stat-label">Care Interactions</div>
      </div>

    </div>

    <!-- ── Middle Row: Birthdays + Members Needing Care ── -->
    <div class="mc-split-grid" style="display:grid;gap:16px;margin-bottom:24px;">

      <!-- Upcoming Birthdays -->
      <div class="card-box" style="padding:0;overflow:hidden;">
        <div class="mc-card-header">
          <div class="mc-card-header-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
            <i class="dw dw-calendar1"></i>
          </div>
          <div>
            <div class="mc-card-header-title">Upcoming Birthdays</div>
            <div class="mc-card-header-sub">Next 14 days</div>
          </div>
        </div>
        <div style="max-height:360px;overflow-y:auto;">
          <?php if (empty($birthdays)): ?>
            <div class="mc-empty">No birthdays in the next 14 days</div>
          <?php else: ?>
            <?php foreach ($birthdays as $b): ?>
              <?php
                $init  = strtoupper(substr($b->firstname, 0, 1) . substr($b->lastname, 0, 1));
                $today = ($b->days_until === 0);
                $soon  = ($b->days_until <= 3);
              ?>
              <div class="mc-list-row <?= $today ? 'mc-bday-today' : '' ?>">
                <div class="mc-mini-avatar"><?= $init ?></div>
                <div style="flex:1;min-width:0;">
                  <div class="mc-list-name"><?= esc($b->firstname . ' ' . $b->lastname) ?></div>
                  <div class="mc-list-sub"><?= esc($b->email) ?></div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                  <div style="font-size:.8rem;font-weight:700;color:<?= $today ? '#10b981' : ($soon ? '#f97316' : 'var(--t2)') ?>;">
                    <?= $today ? '🎂 Today!' : ($b->days_until === 1 ? 'Tomorrow' : 'In ' . $b->days_until . ' days') ?>
                  </div>
                  <div style="font-size:.74rem;color:var(--t3);"><?= esc($b->bday_date) ?></div>
                </div>
                <a href="<?= base_url('memberCareProfile/' . $b->id) ?>" class="mc-row-link" title="Care Profile"></a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Members Needing Attention -->
      <div class="card-box" style="padding:0;overflow:hidden;">
        <div class="mc-card-header">
          <div class="mc-card-header-icon" style="background:linear-gradient(135deg,#ef4444,#f97316);">
            <i class="dw dw-alarm-clock"></i>
          </div>
          <div>
            <div class="mc-card-header-title">Needs Attention</div>
            <div class="mc-card-header-sub">No care event in 90+ days</div>
          </div>
        </div>
        <div style="max-height:360px;overflow-y:auto;">
          <?php if (empty($needs_care)): ?>
            <div class="mc-empty">All members have recent care logged</div>
          <?php else: ?>
            <?php foreach ($needs_care as $m): ?>
              <?php
                $init = strtoupper(substr($m->firstname, 0, 1) . substr($m->lastname, 0, 1));
                $lastDate = $m->last_care_at ? date('M j, Y', strtotime($m->last_care_at)) : 'Never';
                $neverFlag = !$m->last_care_at;
              ?>
              <div class="mc-list-row">
                <div class="mc-mini-avatar" style="background:linear-gradient(135deg,#ef4444,#f97316);"><?= $init ?></div>
                <div style="flex:1;min-width:0;">
                  <div class="mc-list-name"><?= esc($m->firstname . ' ' . $m->lastname) ?></div>
                  <div class="mc-list-sub"><?= esc($m->email) ?></div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                  <div style="font-size:.75rem;font-weight:600;color:<?= $neverFlag ? '#ef4444' : '#f97316' ?>;">
                    <?= $neverFlag ? 'No record' : $lastDate ?>
                  </div>
                  <div style="font-size:.72rem;color:var(--t3);"><?= $m->total_care_events ?> events total</div>
                </div>
                <a href="<?= base_url('memberCareProfile/' . $m->id) ?>" class="mc-row-link" title="Care Profile"></a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- ── Bottom Row: New Members + Recent Activity ── -->
    <div class="mc-split-grid" style="display:grid;gap:16px;margin-bottom:24px;">

      <!-- New Members (last 30 days) -->
      <div class="card-box" style="padding:0;overflow:hidden;">
        <div class="mc-card-header">
          <div class="mc-card-header-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
            <i class="dw dw-add-user"></i>
          </div>
          <div>
            <div class="mc-card-header-title">New Members</div>
            <div class="mc-card-header-sub">Joined in last 30 days</div>
          </div>
        </div>
        <div style="max-height:320px;overflow-y:auto;">
          <?php if (empty($new_members)): ?>
            <div class="mc-empty">No new members in the past 30 days</div>
          <?php else: ?>
            <?php foreach ($new_members as $m): ?>
              <?php
                $init = strtoupper(substr($m->firstname, 0, 1) . substr($m->lastname, 0, 1));
                $joined = $m->date_inserted ? date('M j, Y', strtotime($m->date_inserted)) : 'Recently';
              ?>
              <div class="mc-list-row">
                <div class="mc-mini-avatar" style="background:linear-gradient(135deg,#10b981,#06b6d4);"><?= $init ?></div>
                <div style="flex:1;min-width:0;">
                  <div class="mc-list-name"><?= esc($m->firstname . ' ' . $m->lastname) ?></div>
                  <div class="mc-list-sub"><?= esc($m->email) ?></div>
                </div>
                <div style="font-size:.75rem;color:var(--t3);flex-shrink:0;"><?= $joined ?></div>
                <a href="<?= base_url('memberCareProfile/' . $m->id) ?>" class="mc-row-link" title="Care Profile"></a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Care Activity -->
      <div class="card-box" style="padding:0;overflow:hidden;">
        <div class="mc-card-header">
          <div class="mc-card-header-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
            <i class="dw dw-clock"></i>
          </div>
          <div>
            <div class="mc-card-header-title">Recent Care Activity</div>
            <div class="mc-card-header-sub">Latest interactions logged</div>
          </div>
        </div>
        <div style="max-height:320px;overflow-y:auto;">
          <?php if (empty($recent_activity)): ?>
            <div class="mc-empty">No care activity logged yet</div>
          <?php else: ?>
            <?php
              $typeIcon = ['call'=>'dw-phone','visit'=>'dw-home','email'=>'dw-email','prayer'=>'dw-open-book','message'=>'dw-chat','other'=>'dw-heart'];
              $typeColor = ['call'=>'#6366f1','visit'=>'#10b981','email'=>'#f59e0b','prayer'=>'#8b5cf6','message'=>'#06b6d4','other'=>'#ec4899'];
            ?>
            <?php foreach ($recent_activity as $a): ?>
              <?php
                $t    = $a->event_type ?: 'other';
                $icon = $typeIcon[$t] ?? 'dw-heart';
                $col  = $typeColor[$t] ?? '#6366f1';
              ?>
              <div class="mc-list-row">
                <div class="mc-type-dot" style="background:<?= $col ?>;color:#fff;">
                  <i class="dw <?= $icon ?>" style="font-size:.8rem;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                  <div class="mc-list-name"><?= esc($a->firstname . ' ' . $a->lastname) ?></div>
                  <div class="mc-list-sub"><?= ucfirst(esc($t)) ?> · by <?= esc($a->created_by) ?></div>
                  <?php if ($a->note): ?>
                    <div style="font-size:.78rem;color:var(--t2);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px;"><?= esc($a->note) ?></div>
                  <?php endif; ?>
                </div>
                <div style="font-size:.72rem;color:var(--t3);flex-shrink:0;"><?= date('M j', strtotime($a->created_at)) ?></div>
                <a href="<?= base_url('memberCareProfile/' . $a->member_id) ?>" class="mc-row-link" title="View"></a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- ── Full Member Care Table ── -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">All Members — Care Overview</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Engagement scores &amp; last care date</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="care_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>Email</th>
              <th>Member</th>
              <th>Last Name</th>
              <th>Score</th>
              <th>Grade</th>
              <th>Events</th>
              <th>Last Care</th>
              <th style="width:100px;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

  </div>
</div>

<style>
  /* ── Stat Cards ── */
  .mc-stat-card {
    background:var(--card-bg);border:1px solid var(--border);border-radius:var(--radius);
    padding:18px 16px;box-shadow:var(--shadow-sm);position:relative;overflow:hidden;
  }
  .mc-stat-icon {
    width:40px;height:40px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-size:1.15rem;margin-bottom:12px;
  }
  .mc-stat-value {
    font-size:1.7rem;font-weight:800;color:var(--t1);line-height:1;margin-bottom:4px;
  }
  .mc-stat-label { font-size:.78rem;color:var(--t3);font-weight:500; }

  /* ── Card Header ── */
  .mc-card-header {
    display:flex;align-items:center;gap:12px;
    padding:16px 20px;border-bottom:1px solid var(--border);
  }
  .mc-card-header-icon {
    width:36px;height:36px;border-radius:9px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;
  }
  .mc-card-header-title { font-size:.95rem;font-weight:700;color:var(--t1); }
  .mc-card-header-sub   { font-size:.76rem;color:var(--t3); }

  /* ── List Rows ── */
  .mc-list-row {
    display:flex;align-items:center;gap:12px;padding:12px 20px;
    border-bottom:1px solid var(--border);position:relative;
    transition:background .12s;
  }
  .mc-list-row:last-child { border-bottom:none; }
  .mc-list-row:hover { background:#f8fafc; }
  .mc-list-row.mc-bday-today { background:#ecfdf5; }
  .mc-row-link {
    position:absolute;inset:0;display:block;text-decoration:none;
  }
  .mc-list-name { font-size:.88rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
  .mc-list-sub  { font-size:.76rem;color:var(--t3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }

  .mc-mini-avatar {
    width:34px;height:34px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;
    justify-content:center;color:#fff;font-weight:700;font-size:.78rem;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    position:relative;z-index:1;
  }
  .mc-type-dot {
    width:32px;height:32px;border-radius:8px;flex-shrink:0;display:flex;
    align-items:center;justify-content:center;position:relative;z-index:1;
  }

  /* ── Empty State ── */
  .mc-empty {
    padding:32px 20px;text-align:center;font-size:.85rem;color:var(--t3);
  }

  /* ── Alerts ── */
  .mc-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .mc-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .mc-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }

  /* ── Table Action Buttons ── */
  .mc-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;
  }
  .mc-btn-view { background:#fce7f3;color:#db2777; }
  .mc-btn-view:hover { background:#db2777;color:#fff; }
  .mc-btn-info { background:#eef2ff;color:#6366f1; }
  .mc-btn-info:hover { background:#6366f1;color:#fff; }

  /* ── Score Bar ── */
  .mc-score-bar {
    display:flex;align-items:center;gap:8px;
  }
  .mc-score-track {
    flex:1;height:6px;background:#f1f5f9;border-radius:4px;overflow:hidden;
  }
  .mc-score-fill {
    height:100%;border-radius:4px;transition:width .4s;
  }

  /* ── Engagement Badge ── */
  .mc-badge {
    display:inline-block;padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700;
  }
  .mc-badge-high    { background:#d1fae5;color:#065f46; }
  .mc-badge-medium  { background:#fef3c7;color:#78350f; }
  .mc-badge-low     { background:#ffedd5;color:#9a3412; }
  .mc-badge-none    { background:#fee2e2;color:#7f1d1d; }

  /* DataTables overrides */
  #care_table thead th {
    font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
    color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;
    padding:10px 14px;white-space:nowrap;background:#f8fafc;
  }
  #care_table tbody td {
    padding:10px 14px;border-color:var(--border)!important;
    font-size:.875rem;vertical-align:middle;
  }
  #care_table tbody tr:hover td { background:#f8fafc; }
  .mc-split-grid { grid-template-columns:1fr 1fr; }
  @media(max-width:768px) {
    .mc-split-grid { grid-template-columns:1fr; }
  }
</style>

<script>
var _grads = {A:'#6366f1,#8b5cf6',B:'#3b82f6,#6366f1',C:'#06b6d4,#3b82f6',D:'#10b981,#06b6d4',
  E:'#f59e0b,#f97316',F:'#ef4444,#f59e0b',G:'#8b5cf6,#ec4899',H:'#06b6d4,#10b981',
  I:'#6366f1,#3b82f6',J:'#f97316,#ef4444',K:'#10b981,#3b82f6',L:'#ec4899,#8b5cf6',
  M:'#3b82f6,#06b6d4',N:'#8b5cf6,#6366f1',O:'#f59e0b,#10b981',P:'#ef4444,#ec4899',
  Q:'#6366f1,#06b6d4',R:'#f97316,#f59e0b',S:'#10b981,#8b5cf6',T:'#3b82f6,#10b981',
  U:'#6366f1,#f97316',V:'#06b6d4,#6366f1',W:'#ec4899,#f59e0b',X:'#8b5cf6,#3b82f6',
  Y:'#f59e0b,#ec4899',Z:'#10b981,#6366f1'};
function _grad(n){ var c=(n||'?').charAt(0).toUpperCase(); return _grads[c]||'#6366f1,#8b5cf6'; }

var _gradeColors = {high:'#10b981',medium:'#f59e0b',low:'#f97316',none:'#ef4444'};
var _gradeLabel  = {high:'Active',medium:'Moderate',low:'Low',none:'Inactive'};

$(document).ready(function(){
  $('#care_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: baseURL + '/getMemberCareList',
      type: 'POST',
      data: function(d){ d[csrfName] = csrfHash; },
      dataSrc: function(json){
        if(json && json.csrfName){ csrfName = json.csrfName; csrfHash = json.csrfHash; }
        return json.data || [];
      }
    },
    columns: [
      { title:'#', width:'40px' },
      { title:'Email' },
      { title:'First Name' },
      { title:'Last Name' },
      {
        title:'Score',
        render: function(d){
          return '<div class="mc-score-bar"><span style="font-size:.8rem;font-weight:700;color:var(--t1);width:26px;text-align:right;">' + d + '</span>'
               + '<div class="mc-score-track"><div class="mc-score-fill" style="width:'+d+'%;background:#6366f1;"></div></div></div>';
        }
      },
      {
        title:'Grade',
        render: function(d){
          var c = _gradeColors[d]||'#ef4444', l = _gradeLabel[d]||'Inactive';
          return '<span class="mc-badge mc-badge-'+d+'" style="color:'+c+';">'+l+'</span>';
        }
      },
      { title:'Events', width:'70px' },
      { title:'Last Care' },
      { title:'Actions', orderable:false, searchable:false, width:'100px' }
    ],
    order: [[7,'asc']],
    pageLength: 25,
    language: { processing:'<i class="dw dw-refresh-2" style="font-size:1.4rem;color:#6366f1;"></i>' }
  });
});
</script>
