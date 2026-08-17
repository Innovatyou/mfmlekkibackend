<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['prayer_requests'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['prayer_requests'] ?></span></nav>
      </div>
      <a href="<?= base_url('newPrayer') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_prayer_request'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Prayer Requests</h3><p class="lt-hsub">Member prayer request submissions</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="prayers_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th><?=$locale['date']?></th><th><?=$locale['requester']?></th><th><?=$locale['title']?></th><th><?=$locale['status']?></th><th style="width:130px;"><?=$locale['action']?></th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($prayers as $r):
              $approved = $r->status==0 ? 1 : 0; ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td><span class="lt-date"><?=esc($r->date)?></span></td>
              <td style="font-weight:600;color:var(--t1);"><?=esc($r->requester)?></td>
              <td style="color:var(--t2);"><?=esc($r->title)?></td>
              <td><?php if($r->status==0):?><span class="lt-approved">Approved</span><?php else:?><span class="lt-pending">Pending</span><?php endif;?></td>
              <td>
                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                  <a href="<?=base_url('viewPrayer/'.$r->id)?>" class="lt-ab" style="background:#eef2ff;color:#6366f1;" title="View"><i class="dw dw-eye"></i></a>
                  <a href="<?=base_url('editPrayerStatus/'.$r->id.'/'.$approved)?>" class="lt-ab" style="<?=$r->status==1?'background:#ecfdf5;color:#059669;':'background:#fef9c3;color:#92400e;'?>" title="<?=$r->status==1?'Approve':'Disapprove'?>"><i class="dw dw-<?=$r->status==1?'check':'close'?>-circle-2"></i></a>
                  <a href="<?=base_url('editPrayer/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltSDelConfirm('prayer',<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($prayers)):?><div style="padding:40px;text-align:center;color:var(--t3);">No prayer requests found</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<style>
#prayers_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#prayers_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#prayers_table tbody tr:hover td{background:#f8fafc}#prayers_table tbody tr:last-child td{border-bottom:none!important}
.lt-approved{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:#ecfdf5;color:#065f46}
.lt-pending{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:#fffbeb;color:#92400e}
#prayers_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#prayers_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#prayers_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#prayers_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#prayers_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#prayers_table_wrapper .paginate_button.current,#prayers_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#prayers_table'))$('#prayers_table').DataTable().destroy();
  $('#prayers_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search requests…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:5,orderable:false}]});
});
function ltSDelConfirm(type,id){swal({title:'Delete?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){var u={inbox:'deleteInbox',message:'deleteMessage',book:'deleteBook',branch:'deleteBranch',events:'deleteEvent',groups:'deleteGroup',prayer:'deletePrayer',testimony:'deleteTestimony'};document.location.href=baseURL+'/'+(u[type]||'delete')+'/'+id;});}
</script>
