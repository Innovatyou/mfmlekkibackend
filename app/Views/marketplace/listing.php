<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Church Marketplace</h1>
        <p class="page-subtitle">Buy, sell and share items within the church community</p>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="<?= base_url('marketplaceCategories') ?>" class="btn btn-outline-secondary">
          <i class="dw dw-list" style="margin-right:6px;"></i>Categories
        </a>
        <a href="<?= base_url('newMarketplaceListing') ?>" class="btn btn-primary">
          <i class="dw dw-add" style="margin-right:6px;"></i>New Listing
        </a>
      </div>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="mp-alert mp-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="mp-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mp-alert mp-alert-error">
        <i class="dw dw-warning-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="mp-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <!-- KPI Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:24px;">

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
          <i class="dw dw-shop"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['total']) ?></div>
        <div class="mp-stat-label">Total Listings</div>
      </div>

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
          <i class="dw dw-check-circle-2"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['active']) ?></div>
        <div class="mp-stat-label">Active</div>
      </div>

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
          <i class="dw dw-alarm-clock"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['pending']) ?></div>
        <div class="mp-stat-label">Pending Review</div>
      </div>

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#06b6d4,#6366f1);">
          <i class="dw dw-wallet1"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['sold']) ?></div>
        <div class="mp-stat-label">Sold</div>
      </div>

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#ec4899);">
          <i class="dw dw-star"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['featured']) ?></div>
        <div class="mp-stat-label">Featured</div>
      </div>

      <div class="mp-stat-card">
        <div class="mp-stat-icon" style="background:linear-gradient(135deg,#ef4444,#f97316);">
          <i class="dw dw-chat"></i>
        </div>
        <div class="mp-stat-value"><?= number_format($stats['inquiries_unread']) ?></div>
        <div class="mp-stat-label">Unread Inquiries</div>
      </div>

    </div>

    <!-- Tabs -->
    <div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:20px;">
      <button type="button" class="mp-tab mp-tab-active" onclick="switchTab('all',this)">
        All Listings
      </button>
      <button type="button" class="mp-tab" onclick="switchTab('pending',this)" id="tab_pending_btn">
        Pending Approval
        <?php if($stats['pending'] > 0): ?>
          <span class="mp-tab-badge"><?= $stats['pending'] ?></span>
        <?php endif; ?>
      </button>
    </div>

    <!-- All Listings Table -->
    <div id="tab_all" class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">All Listings</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Manage marketplace items &amp; inquiries</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="mp_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th><th>Title</th><th>Seller</th><th>Category</th>
              <th>Price</th><th>Condition</th><th>Status</th><th>Views</th><th>Date</th>
              <th style="width:80px;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <!-- Pending Approvals Table -->
    <div id="tab_pending" class="card-box" style="padding:0;overflow:hidden;display:none;">
      <?php if($stats['pending'] > 0): ?>
        <div style="padding:14px 22px;background:linear-gradient(135deg,#fef3c7,#fffbeb);border-bottom:1px solid #fde68a;display:flex;align-items:center;gap:10px;">
          <i class="dw dw-alarm-clock" style="color:#f59e0b;font-size:1.1rem;"></i>
          <span style="font-size:.875rem;font-weight:600;color:#78350f;">
            <?= $stats['pending'] ?> listing<?= $stats['pending'] != 1 ? 's' : '' ?> waiting for your review
          </span>
        </div>
      <?php endif; ?>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="mp_pending_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th><th>Title</th><th>Seller</th><th>Category</th>
              <th>Price</th><th>Submitted</th><th style="width:140px;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

  </div>
</div>

<style>
  .mp-stat-card {
    background:var(--card-bg);border:1px solid var(--border);border-radius:var(--radius);
    padding:18px 16px;box-shadow:var(--shadow-sm);
  }
  .mp-stat-icon {
    width:40px;height:40px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-size:1.15rem;margin-bottom:12px;
  }
  .mp-stat-value { font-size:1.7rem;font-weight:800;color:var(--t1);line-height:1;margin-bottom:4px; }
  .mp-stat-label { font-size:.78rem;color:var(--t3);font-weight:500; }

  .mp-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .mp-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .mp-alert-error   { background:#fee2e2;color:#7f1d1d;border:1px solid #fca5a5; }
  .mp-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }

  #mp_table thead th {
    font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
    color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;
    padding:10px 14px;background:#f8fafc;
  }
  #mp_table tbody td {
    padding:10px 14px;border-color:var(--border)!important;
    font-size:.875rem;vertical-align:middle;
  }
  #mp_table tbody tr:hover td { background:#f8fafc; }

  /* ── Tabs ── */
  .mp-tab {
    padding:10px 20px;font-size:.875rem;font-weight:600;color:var(--t3);
    background:none;border:none;border-bottom:3px solid transparent;
    cursor:pointer;margin-bottom:-2px;transition:color .15s,border-color .15s;
    display:inline-flex;align-items:center;gap:8px;
  }
  .mp-tab:hover { color:var(--t1); }
  .mp-tab-active { color:var(--accent)!important;border-bottom-color:var(--accent)!important; }
  .mp-tab-badge {
    display:inline-flex;align-items:center;justify-content:center;
    min-width:20px;height:20px;padding:0 5px;border-radius:10px;
    background:#f59e0b;color:#fff;font-size:.7rem;font-weight:700;line-height:1;
  }

  /* ── Pending action buttons ── */
  .mp-act-btn {
    display:inline-flex;align-items:center;gap:5px;padding:5px 12px;
    border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;
    border:none;cursor:pointer;transition:opacity .15s;
  }
  .mp-act-btn:hover { opacity:.85;text-decoration:none; }
  .mp-act-approve { background:#10b981;color:#fff; }
  .mp-act-reject  { background:#ef4444;color:#fff; }
  .mp-act-view    { background:#6366f1;color:#fff; }
  .mp-act-edit    { background:#f59e0b;color:#fff; }

  /* pending table shares stat-card table styles */
  #mp_pending_table thead th {
    font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
    color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;
    padding:10px 14px;background:#f8fafc;
  }
  #mp_pending_table tbody td {
    padding:10px 14px;border-color:var(--border)!important;
    font-size:.875rem;vertical-align:middle;
  }
  #mp_pending_table tbody tr:hover td { background:#fffbeb; }
</style>

<script>
// Define switchTab immediately — not inside ready() — so onclick always resolves it
var pendingDT = null;

function switchTab(tab, btn) {
  document.querySelectorAll('.mp-tab').forEach(function(b) { b.classList.remove('mp-tab-active'); });
  btn.classList.add('mp-tab-active');
  document.getElementById('tab_all').style.display     = (tab === 'all')     ? '' : 'none';
  document.getElementById('tab_pending').style.display = (tab === 'pending') ? '' : 'none';
  if (tab === 'pending') initPendingTable();
}

function initPendingTable() {
  if (pendingDT) return;
  pendingDT = $('#mp_pending_table').DataTable({
    processing : true,
    serverSide : true,
    pageLength : 15,
    order      : [[5, 'asc']],
    ajax       : { url: baseURL + '/getPendingMarketplaceItems', type: 'POST' },
    dom        : "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language   : {
      search: '', searchPlaceholder: 'Search…',
      lengthMenu: 'Show _MENU_',
      info: 'Showing _START_–_END_ of _TOTAL_ pending',
      paginate: { previous: '‹', next: '›' },
      processing: '<div style="padding:20px;color:var(--t3);font-size:.875rem;">Loading…</div>',
      emptyTable: 'No pending listings — you\'re all caught up!',
    },
    columnDefs: [
      { targets: 0, width: '50px', className: 'text-muted', orderable: false },
      { targets: [4,5], orderable: false },
      { targets: 6, orderable: false, className: 'text-center' },
    ]
  });
}

document.addEventListener('DOMContentLoaded', function () {
  // Approve handler (delegated — rows created by DataTables)
  $(document).on('click', '.approve-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    swal({ title: 'Approve this listing?', text: 'It will become publicly visible.', icon: 'warning',
      buttons: ['Cancel', 'Approve'], dangerMode: false })
      .then(function(ok) {
        if (ok) window.location.href = baseURL + '/approveMarketplaceItem/' + id;
      });
  });

  // Reject handler (delegated)
  $(document).on('click', '.reject-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    swal({ title: 'Reject this listing?', text: 'The seller will not be notified automatically.', icon: 'warning',
      buttons: ['Cancel', 'Reject'], dangerMode: true })
      .then(function(ok) {
        if (ok) window.location.href = baseURL + '/rejectMarketplaceItem/' + id;
      });
  });

  // Generic delete handler used across the app
  window.delete_item = function(e) {
    e.preventDefault();
    var type = e.target.dataset.type || e.currentTarget.dataset.type;
    var id   = e.target.dataset.id   || e.currentTarget.dataset.id;
    if (!id) return;
    swal({ title: 'Delete this listing?', text: 'This cannot be undone.', icon: 'warning',
      buttons: ['Cancel', 'Delete'], dangerMode: true })
      .then(function(ok) {
        if (ok) window.location.href = baseURL + '/deleteMarketplaceListing/' + id;
      });
  };
});
</script>
