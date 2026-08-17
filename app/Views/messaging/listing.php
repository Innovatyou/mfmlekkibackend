<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['mail_sms'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['mail_sms'] ?></span></nav>
      </div>
      <a href="<?= base_url('newMessage') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['send_mail_sms'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Messaging</h3><p class="lt-hsub">SMS and email broadcast history</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="messaging_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th><?=$locale['list']?></th><th><?=$locale['title_subject']?></th><th><?=$locale['content']?></th><th><?=$locale['sms']?></th><th><?=$locale['email']?></th><th><?=$locale['date']?></th><th style="width:90px;"><?=$locale['action']?></th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($messages as $r): ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td><span class="msg-list-pill"><?=esc($r->listname)?></span></td>
              <td style="font-weight:600;color:var(--t1);"><?=esc($r->title)?></td>
              <td style="color:var(--t2);font-size:.82rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?=esc($r->message)?></td>
              <td><?php if($r->sms):?><span class="msg-ch-on">SMS</span><?php else:?><span class="msg-ch-off">SMS</span><?php endif;?></td>
              <td><?php if($r->email):?><span class="msg-ch-on">Email</span><?php else:?><span class="msg-ch-off">Email</span><?php endif;?></td>
              <td><span class="lt-date"><?=esc($r->date_created)?></span></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?=base_url('resendMessage/'.$r->id)?>" class="lt-ab" style="background:#ecfdf5;color:#059669;" title="<?=$locale['resend']?>"><i class="dw dw-cursor-1"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="<?=$locale['delete']?>" onclick="ltSDelConfirm('message',<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($messages)):?><div style="padding:40px;text-align:center;color:var(--t3);">No messages found</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<style>
#messaging_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#messaging_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#messaging_table tbody tr:hover td{background:#f8fafc}#messaging_table tbody tr:last-child td{border-bottom:none!important}
.msg-list-pill{display:inline-flex;align-items:center;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:#eef2ff;color:#6366f1}
.msg-ch-on{display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:.7rem;font-weight:700;background:#ecfdf5;color:#065f46}
.msg-ch-off{display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:.7rem;font-weight:700;background:#f1f5f9;color:#94a3b8}
#messaging_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#messaging_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#messaging_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#messaging_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#messaging_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#messaging_table_wrapper .paginate_button.current,#messaging_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#messaging_table'))$('#messaging_table').DataTable().destroy();
  $('#messaging_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search messages…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:7,orderable:false}]});
});
function ltSDelConfirm(type,id){swal({title:'Delete?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){var u={inbox:'deleteInbox',message:'deleteMessage',book:'deleteBook',branch:'deleteBranch',events:'deleteEvent',groups:'deleteGroup',prayer:'deletePrayer',testimony:'deleteTestimony'};document.location.href=baseURL+'/'+(u[type]||'delete')+'/'+id;});}
</script>
