<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Members</h1>
        <nav class="md-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span>Members</span></nav>
      </div>
      <div style="display:flex;gap:10px;">
        <a href="<?= base_url('membersListing') ?>" class="btn btn-light md-cta"><i class="dw dw-list"></i> All Members</a>
        <a href="<?= base_url('newMember') ?>" class="btn btn-primary md-cta"><i class="dw dw-add"></i> New Member</a>
      </div>
    </div>

    <?php if (!empty($stats['pending']) && $stats['pending'] > 0): ?>
    <div style="background:#fefce8;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i class="dw dw-clock" style="color:#d97706;font-size:1.2rem;"></i>
        <span style="font-size:.88rem;font-weight:700;color:#78350f;"><?= $stats['pending'] ?> signup request<?= $stats['pending'] !== 1 ? 's' : '' ?> awaiting review</span>
      </div>
      <a href="<?= base_url('membersListing') ?>" class="btn btn-sm" style="background:#fde68a;border:1px solid #fbbf24;color:#78350f;font-weight:700;border-radius:8px;padding:5px 14px;">Review Now →</a>
    </div>
    <?php endif; ?>

    <!-- Overview cards -->
    <div class="row mb-4">
      <div class="col-xl col-sm-6 col-6 mb-3">
        <div class="md-card">
          <div class="md-card-icon" style="background:#ede9fe;color:#6366f1;"><i class="dw dw-group"></i></div>
          <div>
            <div class="md-card-val"><?= number_format($stats['total']) ?></div>
            <div class="md-card-lbl">Total Members</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-6 col-6 mb-3">
        <div class="md-card">
          <div class="md-card-icon" style="background:#d1fae5;color:#059669;"><i class="dw dw-user-add"></i></div>
          <div>
            <div class="md-card-val"><?= number_format($stats['new_this_month']) ?>
              <?php $delta = $stats['new_this_month'] - $stats['new_last_month']; if ($delta !== 0): ?>
              <span style="font-size:.7rem;font-weight:700;color:<?= $delta > 0 ? '#059669' : '#ef4444' ?>;"><?= $delta > 0 ? '↑' : '↓' ?><?= abs($delta) ?></span>
              <?php endif; ?>
            </div>
            <div class="md-card-lbl">New This Month</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-6 col-6 mb-3">
        <div class="md-card">
          <div class="md-card-icon" style="background:#e0f2fe;color:#0284c7;"><i class="dw dw-user1"></i></div>
          <div>
            <div class="md-card-val"><?= number_format($genderData['Male']) ?></div>
            <div class="md-card-lbl">Male</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-6 col-6 mb-3">
        <div class="md-card">
          <div class="md-card-icon" style="background:#fce7f3;color:#db2777;"><i class="dw dw-user1"></i></div>
          <div>
            <div class="md-card-val"><?= number_format($genderData['Female']) ?></div>
            <div class="md-card-lbl">Female</div>
          </div>
        </div>
      </div>
      <div class="col-xl col-sm-6 col-6 mb-3">
        <div class="md-card">
          <div class="md-card-icon" style="background:#fef3c7;color:#d97706;"><i class="dw dw-calendar1"></i></div>
          <div>
            <div class="md-card-val"><?= $stats['avg_age'] > 0 ? number_format($stats['avg_age']) : '—' ?></div>
            <div class="md-card-lbl">Average Age</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
      <div class="col-lg-4 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="md-head"><h3 class="md-htitle">Gender Split</h3><p class="md-hsub">All registered members</p></div>
          <div style="padding:0 20px 20px;">
            <div id="md-gender-chart"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="md-head"><h3 class="md-htitle">Age Distribution</h3><p class="md-hsub">Members grouped by age bracket</p></div>
          <div style="padding:0 20px 20px;">
            <div id="md-age-chart"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-lg-8 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="md-head"><h3 class="md-htitle">Membership Growth</h3><p class="md-hsub">New signups over the last 12 months</p></div>
          <div style="padding:0 20px 20px;">
            <div id="md-growth-chart"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div class="md-head"><h3 class="md-htitle">Signup Source</h3><p class="md-hsub">Admin vs. mobile app</p></div>
          <div style="padding:0 20px 20px;">
            <div id="md-source-chart"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent members -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="md-head" style="display:flex;align-items:center;justify-content:space-between;">
        <div><h3 class="md-htitle">Recently Joined</h3><p class="md-hsub">Latest member records</p></div>
        <a href="<?= base_url('membersListing') ?>" style="font-size:.8rem;color:var(--accent);text-decoration:none;padding-right:20px;">View all →</a>
      </div>
      <div style="padding:8px 20px 20px;overflow-x:auto;">
        <?php if (!empty($recentMembers)): ?>
        <div class="md-recent-grid">
          <?php foreach ($recentMembers as $m):
            $full = trim(($m->firstname ?? '') . ' ' . ($m->lastname ?? ''));
            $init = strtoupper(substr($m->firstname ?: '?', 0, 1) . substr($m->lastname ?: '', 0, 1));
          ?>
          <div class="md-recent-item">
            <div class="md-avatar"><?= esc($init) ?></div>
            <div style="min-width:0;">
              <div class="md-recent-name"><?= esc($full !== '' ? $full : 'Unnamed member') ?></div>
              <div class="md-recent-email"><?= esc($m->email) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:var(--t3);padding:32px 0;">No members yet</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
.md-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.md-bc a{color:var(--t3);text-decoration:none;}.md-bc a:hover{color:var(--accent);}
.md-cta{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;}
.md-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow-sm);}
.md-card-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.md-card-val{font-size:1.4rem;font-weight:800;color:var(--t1);line-height:1.1;}
.md-card-lbl{font-size:.76rem;color:var(--t3);margin-top:2px;font-weight:500;}
.md-head{padding:16px 20px 4px;}
.md-htitle{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.md-hsub{font-size:.78rem;color:var(--t3);margin:0 0 8px;}
.md-recent-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:12px;}
.md-recent-item{display:flex;align-items:center;gap:10px;padding:10px;border:1px solid var(--border);border-radius:10px;}
.md-avatar{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-weight:700;font-size:.8rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.md-recent-name{font-weight:600;color:var(--t1);font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.md-recent-email{font-size:.72rem;color:var(--t3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
</style>

<script src="<?= base_url() ?>/public/assets/src/plugins/apexcharts/apexcharts.min.js"></script>
<script>
(function () {
  var palette = ['#6366f1', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];

  function render() {
    if (typeof ApexCharts === 'undefined') return;

    /* Gender donut */
    new ApexCharts(document.querySelector('#md-gender-chart'), {
      chart: { type: 'donut', height: 240 },
      series: [<?= (int) $genderData['Male'] ?>, <?= (int) $genderData['Female'] ?>, <?= (int) $genderData['Unspecified'] ?>],
      labels: ['Male', 'Female', 'Unspecified'],
      colors: ['#0284c7', '#db2777', '#94a3b8'],
      legend: { position: 'bottom', fontSize: '12px' },
      dataLabels: { enabled: false },
      stroke: { width: 0 },
    }).render();

    /* Age bar chart */
    new ApexCharts(document.querySelector('#md-age-chart'), {
      chart: { type: 'bar', height: 240, toolbar: { show: false } },
      series: [{ name: 'Members', data: <?= json_encode(array_values($ageData)) ?> }],
      xaxis: { categories: <?= json_encode(array_keys($ageData)) ?>, labels: { style: { fontSize: '11px' } } },
      colors: [palette[0]],
      plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
      dataLabels: { enabled: false },
      grid: { borderColor: '#f1f5f9' },
    }).render();

    /* Growth line chart */
    new ApexCharts(document.querySelector('#md-growth-chart'), {
      chart: { type: 'area', height: 260, toolbar: { show: false } },
      series: [{ name: 'New members', data: <?= json_encode($growthData['data']) ?> }],
      xaxis: { categories: <?= json_encode($growthData['labels']) ?>, labels: { style: { fontSize: '11px' } } },
      colors: [palette[0]],
      stroke: { curve: 'smooth', width: 2.5 },
      fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05 } },
      dataLabels: { enabled: false },
      grid: { borderColor: '#f1f5f9' },
    }).render();

    /* Signup source donut */
    var sourceLabels = <?= json_encode(array_keys($sourceData)) ?>;
    var sourceValues = <?= json_encode(array_values($sourceData)) ?>;
    new ApexCharts(document.querySelector('#md-source-chart'), {
      chart: { type: 'donut', height: 240 },
      series: sourceValues,
      labels: sourceLabels,
      colors: palette,
      legend: { position: 'bottom', fontSize: '12px' },
      dataLabels: { enabled: false },
      stroke: { width: 0 },
    }).render();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', render);
  } else {
    render();
  }
})();
</script>
