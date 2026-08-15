<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Admin Users</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span>Admin Users</span></nav>
      </div>
      <?php if(hasPermission('users.create') || isSuperAdmin()): ?>
      <a href="<?= base_url('newAdmin') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i>New Admin User</a>
      <?php endif; ?>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">All Admin Users</h3><p class="lt-hsub">Accounts with backend access</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="admin_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th style="width:90px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $c=1; foreach($userRecords as $record): ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $c ?></td>
              <td style="font-weight:600;color:var(--t1);"><?= esc($record->fullname) ?></td>
              <td style="color:var(--t2);"><?= esc($record->email) ?></td>
              <td>
                <?php if(hasPermission('users.edit') || hasPermission('users.delete') || isSuperAdmin()): ?>
                <div style="display:flex;gap:5px;">
                  <?php if(hasPermission('users.edit') || isSuperAdmin()): ?>
                  <a href="<?= base_url('editAdmin/'.$record->id) ?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <?php endif; ?>
                  <?php if(hasPermission('users.delete') || isSuperAdmin()): ?>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="delete_item(event)" data-type="admin" data-id="<?= $record->id ?>"><i class="dw dw-trash"></i></a>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.lt-cta{border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;display:inline-flex;align-items:center;gap:6px}
.lt-head{display:flex;flex-direction:column;padding:18px 22px;border-bottom:1px solid var(--border)}
.lt-htitle{font-size:1rem;font-weight:700;color:var(--t1);margin:0}
.lt-hsub{font-size:.8rem;color:var(--t3);margin:2px 0 0}
.lt-ab{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer;border:none}
.lt-edit{background:#fffbeb;color:#d97706}.lt-edit:hover{background:#f59e0b;color:#fff}
.lt-del{background:#fef2f2;color:#ef4444}.lt-del:hover{background:#ef4444;color:#fff}
#admin_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#admin_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#admin_table tbody tr:hover td{background:#f8fafc}
#admin_table tbody tr:last-child td{border-bottom:none!important}
#admin_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#admin_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#admin_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#admin_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#admin_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#admin_table_wrapper .paginate_button.current,#admin_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#admin_table'))$('#admin_table').DataTable().destroy();
  $('#admin_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search admins…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:3,orderable:false}]});
});
</script>
