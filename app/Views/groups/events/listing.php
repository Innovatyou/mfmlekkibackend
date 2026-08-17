<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['group_event_act'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= $locale['group_event_act'] ?></span></nav>
      </div>
      <a href="<?= base_url('newGroupEvent/'.$groupid) ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_group_event'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle"><?= $locale['group_event_act'] ?></h3></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="gevents_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['title'] ?></th>
              <th><?= $locale['thumbnail'] ?></th>
              <th><?= $locale['date'] ?></th>
              <th><?= $locale['time'] ?></th>
              <th style="width:90px;"><?= $locale['action'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $c=1; foreach($events as $record): ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $c ?></td>
              <td style="font-weight:600;color:var(--t1);"><?= esc($record->title) ?></td>
              <td>
                <?php if(!empty($record->thumbnail)): ?>
                <img src="<?= esc($record->thumbnail) ?>" style="width:44px;height:44px;border-radius:8px;object-fit:cover;" alt="">
                <?php else: ?>
                <div style="width:44px;height:44px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:var(--t3);font-size:.9rem;"><i class="dw dw-image"></i></div>
                <?php endif; ?>
              </td>
              <td style="color:var(--t2);"><?= esc($record->date) ?></td>
              <td style="color:var(--t2);"><?= esc($record->time) ?></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?= base_url('editGroupEvent/'.$record->id) ?>" class="lt-ab lt-edit" title="<?= $locale['edit'] ?>"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="<?= $locale['delete'] ?>" onclick="delete_item(event)" data-type="groupevents" data-id="<?= $record->id ?>"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
            <?php if(empty($events)): ?>
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--t3);">No events yet</td></tr>
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
.lt-ab{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer;border:none}
.lt-edit{background:#fffbeb;color:#d97706}.lt-edit:hover{background:#f59e0b;color:#fff}
.lt-del{background:#fef2f2;color:#ef4444}.lt-del:hover{background:#ef4444;color:#fff}
#gevents_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#gevents_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#gevents_table tbody tr:hover td{background:#f8fafc}
#gevents_table tbody tr:last-child td{border-bottom:none!important}
#gevents_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#gevents_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#gevents_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#gevents_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#gevents_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#gevents_table_wrapper .paginate_button.current,#gevents_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#gevents_table'))$('#gevents_table').DataTable().destroy();
  $('#gevents_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search events…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:[2],orderable:false},{targets:5,orderable:false}]});
});
</script>
