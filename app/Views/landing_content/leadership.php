<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Leadership</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('landingContent') ?>">Website</a><span>/</span><span>Leadership</span></nav>
      </div>
      <a href="<?= base_url('newLeader') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i>New Leader</a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Pastors &amp; Leaders</h3><p class="lt-hsub">Shown on the public website "Our Leadership" section</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="leadership_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th>Photo</th><th>Name</th><th>Role</th><th>Status</th><th style="width:90px;">Action</th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($leaders as $r): ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td>
                <?php if($r->photo!=""):?>
                  <img src="<?=esc($r->photo)?>" style="width:38px;height:38px;border-radius:10px;object-fit:cover;">
                <?php else:?>
                  <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;"><?=strtoupper(substr($r->name,0,1))?></div>
                <?php endif;?>
              </td>
              <td><span style="font-weight:600;color:var(--t1);"><?=esc($r->name)?></span></td>
              <td style="color:var(--t2);font-size:.82rem;"><?=esc($r->role_title)?></td>
              <td><?php if($r->status=='active'):?><span class="badge badge-pill badge-success">Active</span><?php else:?><span class="badge badge-pill badge-secondary">Inactive</span><?php endif;?></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?=base_url('editLeader/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ldDelConfirm(<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($leaders)):?><div style="padding:40px;text-align:center;color:var(--t3);">No leaders added yet</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.lt-head{padding:16px 22px 0;}.lt-htitle{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}.lt-hsub{font-size:.78rem;color:var(--t3);margin:0;}
.lt-ab{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;}
.lt-edit{background:#f59e0b;}.lt-del{background:#ef4444;}
#leadership_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#leadership_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#leadership_table tbody tr:hover td{background:#f8fafc}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#leadership_table'))$('#leadership_table').DataTable().destroy();
  $('#leadership_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:1,orderable:false},{targets:5,orderable:false}]});
});
function ldDelConfirm(id){swal({title:'Delete this leader?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){document.location.href=baseURL+'/deleteLeader/'+id;});}
</script>
