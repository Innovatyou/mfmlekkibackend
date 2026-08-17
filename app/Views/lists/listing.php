<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['email_sms_list'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['email_sms_list'] ?></span></nav>
      </div>
      <a href="<?= base_url('newList') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_list'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle"><?= $locale['email_sms_list'] ?></h3><p class="lt-hsub">Mailing and SMS contact lists</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="lists_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['title'] ?></th>
              <th><?= $locale['members'] ?></th>
              <th><?= $locale['date'] ?></th>
              <th style="width:110px;"><?= $locale['action'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $c=1; foreach($lists as $record): ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $c ?></td>
              <td style="font-weight:600;color:var(--t1);"><?= esc($record->title) ?></td>
              <td>
                <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#dbeafe;color:#1e40af;"><?= esc($record->count) ?> members</span>
              </td>
              <td style="color:var(--t3);font-size:.82rem;"><?= esc($record->date) ?></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?= base_url('addMemberstoList/'.$record->id) ?>" class="lt-ab" style="background:#f0fdf4;color:#16a34a;" title="<?= $locale['add_members'] ?>"><i class="dw dw-add"></i></a>
                  <a href="<?= base_url('viewListMembers/'.$record->id) ?>" class="lt-ab" style="background:#eff6ff;color:#2563eb;" title="<?= $locale['view_members'] ?>"><i class="dw dw-user1"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="<?= $locale['delete'] ?>" onclick="delete_item(event)" data-type="lists" data-id="<?= $record->id ?>"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
            <?php if(empty($lists)): ?>
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--t3);">No lists yet</td></tr>
            <?php endif; ?>
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
.lt-del{background:#fef2f2;color:#ef4444}.lt-del:hover{background:#ef4444;color:#fff}
#lists_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#lists_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#lists_table tbody tr:hover td{background:#f8fafc}
#lists_table tbody tr:last-child td{border-bottom:none!important}
#lists_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#lists_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#lists_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#lists_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#lists_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#lists_table_wrapper .paginate_button.current,#lists_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#lists_table'))$('#lists_table').DataTable().destroy();
  $('#lists_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search lists…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:4,orderable:false}]});
});
</script>
