<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">All Partners</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('partnership') ?>">Partnership</a><span>/</span><span>All Partners</span></nav>
      </div>
      <?php if (hasPermission('partnership.edit') || isSuperAdmin()): ?>
      <a href="<?= base_url('newPartnership') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i> New Partner</a>
      <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>

    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
          <h3 class="lt-htitle" style="display:inline-flex;align-items:center;gap:8px;">
            Partnership Records
            <?php if (!empty($pending_count) && $pending_count > 0): ?>
            <span style="background:#fef3c7;color:#92400e;font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;border:1px solid #fde68a;">
              <?= $pending_count ?> pending review
            </span>
            <?php endif; ?>
          </h3>
          <p class="lt-hsub">Manage all church partners and pledges</p>
        </div>
        <div style="padding-right:20px;">
          <a href="<?= base_url('partnershipTiers') ?>" style="font-size:.8rem;color:var(--accent);text-decoration:none;">Manage Tiers →</a>
        </div>
      </div>
      <div style="padding:0 22px 22px;overflow-x:auto;">
        <table id="ps_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th>
            <th>Partner</th>
            <th>Tier</th>
            <th>Pledged</th>
            <th>Paid</th>
            <th>Remaining</th>
            <th>Frequency</th>
            <th>Status</th>
            <th style="width:100px;">Actions</th>
          </tr></thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
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
.lt-ab{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;font-size:.85rem;text-decoration:none;transition:background .15s;}
.lt-approve{background:#fef3c7;color:#92400e;border:none;}.lt-approve:hover{background:#fde68a;color:#78350f;}
.lt-pay{background:#dcfce7;color:#16a34a;}.lt-pay:hover{background:#bbf7d0;color:#15803d;}
.lt-edit{background:#ede9fe;color:#6366f1;}.lt-edit:hover{background:#ddd6fe;color:#4f46e5;}
.lt-del{background:#fee2e2;color:#ef4444;}.lt-del:hover{background:#fecaca;color:#dc2626;}
#ps_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc;}
#ps_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle;}
#ps_table tbody tr:hover td{background:#f8fafc;}
#ps_table tbody tr:last-child td{border-bottom:none!important;}
#ps_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none;}
#ps_table_wrapper .dataTables_filter input:focus{border-color:var(--accent);}
#ps_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem;}
#ps_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3);}
#ps_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600;}
#ps_table_wrapper .paginate_button.current,#ps_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important;}
</style>

<script>
function ltPDelConfirm(id) {
  swal({ title: 'Delete?', text: 'This cannot be undone.', type: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Yes, delete' },
    function () { document.location.href = baseURL + '/deletePartnership/' + id; });
}
</script>
