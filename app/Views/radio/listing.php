<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['radio_stations'] ?></h1>
        <nav class="ml-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <span><?= $locale['radio_stations'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('newRadio') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-add" style="margin-right:6px;"></i><?= $locale['new_radio'] ?>
      </a>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="ml-alert ml-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="ml-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="ml-alert ml-alert-danger">
        <i class="dw dw-close-circle-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="ml-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <!-- Radio table card -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">Radio Stations</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Manage church radio streams</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="radio_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['title'] ?></th>
              <th><?= $locale['link'] ?></th>
              <th><?= $locale['status'] ?></th>
              <th style="width:90px;"><?= $locale['action'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; foreach ($radio as $record): ?>
            <tr>
              <td class="text-muted"><?= $count ?></td>
              <td>
                <span style="font-weight:600;color:var(--t1);"><?= esc($record->title) ?></span>
              </td>
              <td>
                <a href="<?= esc($record->link) ?>" target="_blank" rel="noopener noreferrer"
                   style="color:var(--accent);font-size:.8rem;word-break:break-all;">
                  <?= esc($record->link) ?>
                </a>
              </td>
              <td>
                <?php if ($record->status == 0): ?>
                  <span class="ml-status-badge ml-status-live"><span class="ml-status-dot ml-dot-live"></span>Live</span>
                <?php else: ?>
                  <span class="ml-status-badge ml-status-offline"><span class="ml-status-dot ml-dot-offline"></span>Offline</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <div style="display:flex;gap:5px;justify-content:center;">
                  <a href="<?= base_url('editRadio/' . $record->id) ?>" class="ml-action-btn ml-action-edit" title="Edit">
                    <i class="dw dw-edit-2"></i>
                  </a>
                  <a href="javascript:void(0)" class="ml-action-btn ml-action-delete" title="Delete"
                     onclick="confirmDeleteRadio(<?= $record->id ?>)">
                    <i class="dw dw-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php $count++; endforeach; ?>
          </tbody>
        </table>
        <?php if (empty($radio)): ?>
          <div style="padding:40px;text-align:center;color:var(--t3);font-size:.875rem;">No radio stations found</div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<style>
  .ml-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .ml-breadcrumb a { color:var(--t3);text-decoration:none; }
  .ml-breadcrumb a:hover { color:var(--accent); }
  .ml-breadcrumb span { margin:0 5px; }

  .ml-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .ml-alert i { font-size:1.1rem;flex-shrink:0; }
  .ml-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .ml-alert-danger  { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
  .ml-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }
  .ml-alert-close:hover { opacity:1; }

  #radio_table thead th {
    font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
    color:var(--t3);border-bottom:2px solid var(--border) !important;border-top:none !important;
    padding:10px 14px;white-space:nowrap;background:#f8fafc;
  }
  #radio_table tbody td {
    padding:12px 14px;border-color:var(--border) !important;
    font-size:.875rem;vertical-align:middle;
  }
  #radio_table tbody tr:hover td { background:#f8fafc; }
  #radio_table tbody tr:last-child td { border-bottom:none !important; }

  .ml-status-badge {
    display:inline-flex;align-items:center;gap:6px;padding:3px 10px;
    border-radius:20px;font-size:.75rem;font-weight:600;
  }
  .ml-status-dot { width:7px;height:7px;border-radius:50%;flex-shrink:0; }
  .ml-status-live    { background:#ecfdf5;color:#065f46; }
  .ml-dot-live       { background:#10b981; }
  .ml-status-offline { background:#f1f5f9;color:var(--t2); }
  .ml-dot-offline    { background:#94a3b8; }

  .ml-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer;
  }
  .ml-action-edit   { background:#fffbeb;color:#d97706; }
  .ml-action-edit:hover   { background:#f59e0b;color:#fff; }
  .ml-action-delete { background:#fef2f2;color:#ef4444; }
  .ml-action-delete:hover { background:#ef4444;color:#fff; }
</style>

<script>
$(document).ready(function () {
  if ($.fn.DataTable.isDataTable('#radio_table')) {
    $('#radio_table').DataTable().destroy();
  }
  $('#radio_table').DataTable({
    pageLength : 15,
    dom        : "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language   : {
      search: '', searchPlaceholder: 'Search stations…',
      lengthMenu: 'Show _MENU_ stations',
      info: 'Showing _START_–_END_ of _TOTAL_ stations',
      paginate: { previous: '‹', next: '›' }
    },
    columnDefs: [
      { targets: 0, width: '50px', orderable: false }
    ]
  });
});

function confirmDeleteRadio(id) {
  swal({
    title: 'Delete radio station?',
    text: 'This action cannot be undone.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, delete'
  }, function () {
    document.location.href = (typeof baseURL !== 'undefined' ? baseURL : '') + '/deleteRadio/' + id;
  });
}
</script>
