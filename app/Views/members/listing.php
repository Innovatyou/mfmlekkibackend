<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['members'] ?></h1>
        <nav class="ml-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <span><?= $locale['members'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('newMember') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-add" style="margin-right:6px;"></i><?= $locale['new_member'] ?>
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

    <!-- Members table card -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">All Members</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Church membership registry</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="members_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['email'] ?></th>
              <th><?= $locale['first_name'] ?> &amp; <?= $locale['last_name'] ?></th>
              <th><?= $locale['last_name'] ?></th>
              <th><?= $locale['age'] ?></th>
              <th style="width:110px;"><?= $locale['action'] ?></th>
            </tr>
          </thead>
        </table>
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

  /* Table overrides */
  #members_table thead th {
    font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
    color:var(--t3);border-bottom:2px solid var(--border) !important;border-top:none !important;
    padding:10px 14px;white-space:nowrap;background:#f8fafc;
  }
  #members_table tbody td {
    padding:12px 14px;border-color:var(--border) !important;
    font-size:.875rem;vertical-align:middle;
  }
  #members_table tbody tr:hover td { background:#f8fafc; }
  #members_table tbody tr:last-child td { border-bottom:none !important; }

  .ml-member-cell { display:flex;align-items:center;gap:10px; }
  .ml-avatar {
    width:34px;height:34px;border-radius:9px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0;
  }
  .ml-member-name { font-weight:600;color:var(--t1);line-height:1.2; }
  .ml-member-email { font-size:.75rem;color:var(--t3);margin-top:1px; }

  .ml-age-badge {
    display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;
    font-size:.75rem;font-weight:600;background:#f1f5f9;color:var(--t2);
  }

  .ml-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;
  }
  .ml-action-view   { background:#eef2ff;color:#6366f1; }
  .ml-action-view:hover   { background:#6366f1;color:#fff; }
  .ml-action-edit   { background:#fffbeb;color:#d97706; }
  .ml-action-edit:hover   { background:#f59e0b;color:#fff; }
  .ml-action-delete { background:#fef2f2;color:#ef4444; }
  .ml-action-delete:hover { background:#ef4444;color:#fff; }

  /* DataTables toolbar */
  #members_table_wrapper .dataTables_filter input {
    border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;
    font-size:.875rem;outline:none;transition:border .15s;
  }
  #members_table_wrapper .dataTables_filter input:focus { border-color:var(--accent); }
  #members_table_wrapper .dataTables_length select {
    border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem;
  }
  #members_table_wrapper .dataTables_info { font-size:.8rem;color:var(--t3); }
  #members_table_wrapper .paginate_button {
    border-radius:7px !important;font-size:.82rem;font-weight:600;
  }
  #members_table_wrapper .paginate_button.current,
  #members_table_wrapper .paginate_button.current:hover {
    background:var(--accent) !important;border-color:var(--accent) !important;color:#fff !important;
  }
</style>

<script>
/* Avatar gradient map (same as other pages) */
var _grads = {A:'#6366f1,#8b5cf6',B:'#3b82f6,#6366f1',C:'#06b6d4,#3b82f6',D:'#10b981,#06b6d4',
  E:'#f59e0b,#f97316',F:'#ef4444,#f59e0b',G:'#8b5cf6,#ec4899',H:'#06b6d4,#10b981',
  I:'#6366f1,#3b82f6',J:'#f97316,#ef4444',K:'#10b981,#3b82f6',L:'#ec4899,#8b5cf6',
  M:'#3b82f6,#06b6d4',N:'#8b5cf6,#6366f1',O:'#f59e0b,#10b981',P:'#ef4444,#ec4899',
  Q:'#6366f1,#06b6d4',R:'#f97316,#f59e0b',S:'#10b981,#8b5cf6',T:'#3b82f6,#10b981',
  U:'#6366f1,#f97316',V:'#06b6d4,#6366f1',W:'#ec4899,#f59e0b',X:'#8b5cf6,#3b82f6',
  Y:'#f59e0b,#ec4899',Z:'#10b981,#6366f1'};
function _grad(name) {
  var c = (name || '?').charAt(0).toUpperCase();
  return _grads[c] || '#6366f1,#8b5cf6';
}

$(document).ready(function () {
  /* Destroy the instance initialised by common.js and reinit with richer config */
  if ($.fn.DataTable.isDataTable('#members_table')) {
    $('#members_table').DataTable().destroy();
  }

  $('#members_table').DataTable({
    processing : true,
    serverSide : true,
    pageLength : 15,
    ajax       : { url: baseURL + '/getMembers', type: 'POST' },
    dom        : "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language   : {
      search: '', searchPlaceholder: 'Search members…',
      lengthMenu: 'Show _MENU_ members',
      info: 'Showing _START_–_END_ of _TOTAL_ members',
      paginate: { previous: '‹', next: '›' },
      processing: '<div style="padding:20px;color:var(--t3);font-size:.875rem;">Loading…</div>',
      emptyTable: '<div style="padding:40px;text-align:center;color:var(--t3);">No members found</div>',
      zeroRecords: '<div style="padding:40px;text-align:center;color:var(--t3);">No matching members found</div>'
    },
    /* Raw array from server: [0]=count [1]=email [2]=firstname [3]=lastname [4]=age [5]=actions_html */
    columnDefs: [
      /* col 0 — # */
      { targets: 0, width: '50px', className: 'text-muted', orderable: false },

      /* col 1 — email: hidden, shown inside the name cell */
      { targets: 1, visible: false },

      /* col 2 — firstname → render as avatar + full name + email sub-line */
      {
        targets: 2,
        render: function (fn, type, row) {
          if (type !== 'display') return fn;
          var ln    = row[3];
          var email = row[1];
          var full  = fn + ' ' + ln;
          var init  = ((fn || '?').charAt(0) + (ln || '').charAt(0)).toUpperCase();
          var grade = _grad(fn);
          return '<div class="ml-member-cell">' +
            '<div class="ml-avatar" style="background:linear-gradient(135deg,' + grade + ');">' + init + '</div>' +
            '<div><div class="ml-member-name">' + $('<div>').text(full).html() + '</div>' +
            '<div class="ml-member-email">' + $('<div>').text(email).html() + '</div></div></div>';
        }
      },

      /* col 3 — lastname: hidden, merged into col 2 */
      { targets: 3, visible: false },

      /* col 4 — age */
      {
        targets: 4,
        className: 'text-center',
        render: function (age, type) {
          if (type !== 'display') return age || '';
          return age ? '<span class="ml-age-badge">' + age + ' yrs</span>' : '<span style="color:var(--t3);">—</span>';
        }
      },

      /* col 5 — actions: parse id from server HTML, render styled buttons */
      {
        targets: 5,
        orderable: false,
        className: 'text-center',
        render: function (html, type) {
          if (type !== 'display') return '';
          var m = html.match(/viewMember\/(\d+)/);
          if (!m) return html;
          var id   = m[1];
          var base = (typeof baseURL !== 'undefined' ? baseURL : '');
          return '<div style="display:flex;gap:5px;justify-content:center;">' +
            '<a href="' + base + '/viewMember/' + id + '" class="ml-action-btn ml-action-view" title="View"><i class="dw dw-eye"></i></a>' +
            '<a href="' + base + '/editMember/' + id + '" class="ml-action-btn ml-action-edit" title="Edit"><i class="dw dw-edit-2"></i></a>' +
            '<a href="javascript:void(0)" class="ml-action-btn ml-action-delete" title="Delete" ' +
              'onclick="confirmDeleteMember(' + id + ')"><i class="dw dw-trash"></i></a>' +
            '</div>';
        }
      }
    ]
  });
});

function confirmDeleteMember(id) {
  swal({
    title: 'Delete member?',
    text: 'This action cannot be undone.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, delete'
  }, function () {
    document.location.href = (typeof baseURL !== 'undefined' ? baseURL : '') + '/deleteMember/' + id;
  });
}
</script>
