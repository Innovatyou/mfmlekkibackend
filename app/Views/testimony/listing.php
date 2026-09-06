<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['testimonies'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['testimonies'] ?></span></nav>
      </div>
      <a href="<?= base_url('newTestimony') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_testimony'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Testimonies</h3><p class="lt-hsub">Member testimonial submissions</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="testimony_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th><?=$locale['date']?></th><th><?=$locale['testifier']?></th><th><?=$locale['title']?></th><th><?=$locale['status']?></th><th style="width:110px;"><?=$locale['action']?></th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($testimonies as $r):
              $approved=$r->status==0?1:0; ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td><span class="lt-date"><?=esc($r->date)?></span></td>
              <td><a href="javascript:void(0)" class="rq-clickable" onclick="showTestimonyModal(<?= (int) $r->id ?>)"><?=esc($r->testifier)?></a></td>
              <td style="color:var(--t2);"><?=esc($r->title)?></td>
              <td><?php if($r->status==0):?><span class="lt-approved">Approved</span><?php else:?><span class="lt-pending">Pending</span><?php endif;?></td>
              <td>
                <div style="display:flex;gap:4px;">
                  <a href="<?=base_url('viewTestimony/'.$r->id)?>" class="lt-ab" style="background:#eef2ff;color:#6366f1;" title="View"><i class="dw dw-eye"></i></a>
                  <a href="<?=base_url('editTestimonyStatus/'.$r->id.'/'.$approved)?>" class="lt-ab" style="<?=$r->status==1?'background:#ecfdf5;color:#059669;':'background:#fef9c3;color:#92400e;'?>" title="<?=$r->status==1?'Approve':'Disapprove'?>"><i class="dw dw-<?=$r->status==1?'check':'close'?>-circle-2"></i></a>
                  <a href="<?=base_url('editTestimony/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltSDelConfirm('testimony',<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($testimonies)):?><div style="padding:40px;text-align:center;color:var(--t3);">No testimonies found</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<style>
#testimony_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#testimony_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#testimony_table tbody tr:hover td{background:#f8fafc}#testimony_table tbody tr:last-child td{border-bottom:none!important}
#testimony_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#testimony_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#testimony_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#testimony_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#testimony_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#testimony_table_wrapper .paginate_button.current,#testimony_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
.rq-clickable{color:var(--t1);font-weight:600;text-decoration:none;cursor:pointer;}
.rq-clickable:hover{color:var(--accent);text-decoration:underline;}
</style>
<script>
var TESTIMONY_DATA = <?= json_encode(array_map(function ($r) {
  return [
    'id' => (int) $r->id,
    'testifier' => $r->testifier,
    'title' => $r->title,
    'content' => $r->content,
    'date' => $r->date,
    'status' => (int) $r->status,
    'email' => $r->email ?? '',
    'admin_reply' => $r->admin_reply ?? '',
    'replied_by' => $r->replied_by ?? '',
    'replied_at' => $r->replied_at ?? '',
  ];
}, $testimonies), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function showTestimonyModal(id) {
  var r = TESTIMONY_DATA.find(function (x) { return x.id === id; });
  if (!r) return;
  var fields = [
    { label: 'Testifier', value: r.testifier },
    { label: 'Email', value: r.email },
    { label: 'Date', value: r.date },
    { label: 'Title', value: r.title },
    { label: 'Testimony', value: r.content },
  ];
  if (r.admin_reply) {
    fields.push({ label: 'Your Reply' + (r.replied_by ? ' (' + r.replied_by + ')' : ''), value: r.admin_reply });
  }
  var approveUrl = r.status !== 0 ? (baseURL + '/editTestimonyStatus/' + r.id + '/1') : null;
  openRequestModal(r.testifier, fields, approveUrl, 'Approve Testimony');
}

(function initTestimonyTableWhenReady() {
  if (!window.jQuery) { setTimeout(initTestimonyTableWhenReady, 20); return; }
  var $ = window.jQuery;
  $(document).ready(function(){
    if($.fn.DataTable.isDataTable('#testimony_table'))$('#testimony_table').DataTable().destroy();
    $('#testimony_table').DataTable({pageLength:15,order:[[1,'desc']],dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
      language:{search:'',searchPlaceholder:'Search testimonies…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
      columnDefs:[{targets:0,width:'50px',orderable:false},{targets:5,orderable:false}]});
  });
})();

function ltSDelConfirm(type,id){swal({title:'Delete?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){var u={inbox:'deleteInbox',message:'deleteMessage',book:'deleteBook',branch:'deleteBranch',events:'deleteEvent',groups:'deleteGroup',prayer:'deletePrayer',testimony:'deleteTestimony'};document.location.href=baseURL+'/'+(u[type]||'delete')+'/'+id;});}
</script>

<?= view('_request_modal') ?>
