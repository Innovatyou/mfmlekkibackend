<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Service Times</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('landingContent') ?>">Website</a><span>/</span><span>Service Times</span></nav>
      </div>
      <a href="<?= base_url('newServiceTime') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i>New Service Time</a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Weekly Services</h3><p class="lt-hsub">Shown on the public website in the order below</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="servicetimes_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th>Name</th><th>Day</th><th>Time</th><th>Location</th><th>Status</th><th style="width:90px;">Action</th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($times as $r): ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td><span style="font-weight:600;color:var(--t1);"><?=esc($r->name)?></span></td>
              <td><?=esc($r->day_of_week)?></td>
              <td><?=esc($r->time_label)?></td>
              <td style="color:var(--t2);font-size:.82rem;"><?=esc($r->location)?></td>
              <td><?php if($r->status=='active'):?><span class="badge badge-pill badge-success">Active</span><?php else:?><span class="badge badge-pill badge-secondary">Inactive</span><?php endif;?></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?=base_url('editServiceTime/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="stDelConfirm(<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($times)):?><div style="padding:40px;text-align:center;color:var(--t3);">No service times added yet</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.lt-head{padding:16px 22px 0;}.lt-htitle{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}.lt-hsub{font-size:.78rem;color:var(--t3);margin:0;}
.lt-ab{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;}
.lt-edit{background:#f59e0b;}.lt-del{background:#ef4444;}
#servicetimes_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#servicetimes_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#servicetimes_table tbody tr:hover td{background:#f8fafc}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#servicetimes_table'))$('#servicetimes_table').DataTable().destroy();
  $('#servicetimes_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:6,orderable:false}]});
});
function stDelConfirm(id){swal({title:'Delete this service time?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){document.location.href=baseURL+'/deleteServiceTime/'+id;});}
</script>
