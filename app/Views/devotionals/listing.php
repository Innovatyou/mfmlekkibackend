<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['devotionals'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['devotionals'] ?></span></nav>
      </div>
      <a href="<?= base_url('newDevotional') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_devotional'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">All Devotionals</h3><p class="lt-hsub">Daily devotional entries</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="devotionals_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th><?=$locale['date']?></th><th><?=$locale['title']?></th><th style="width:90px;"><?=$locale['action']?></th>
          </tr></thead>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
.lt-bc{font-size:.8rem;color:var(--t3);margin-top:3px}.lt-bc a{color:var(--t3);text-decoration:none}.lt-bc a:hover{color:var(--accent)}.lt-bc span{margin:0 5px}
.lt-cta{border-radius:8px!important;font-weight:600!important;padding:9px 20px!important;font-size:.875rem!important;display:inline-flex!important;align-items:center!important;gap:6px!important}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.lt-x{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;padding:0}
.lt-head{padding:18px 22px;border-bottom:1px solid var(--border)}.lt-htitle{font-size:1rem;font-weight:700;color:var(--t1);margin:0}.lt-hsub{font-size:.8rem;color:var(--t3);margin:2px 0 0}
#devotionals_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#devotionals_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#devotionals_table tbody tr:hover td{background:#f8fafc}#devotionals_table tbody tr:last-child td{border-bottom:none!important}
.lt-date{display:inline-flex;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:#eef2ff;color:#6366f1}
.lt-ab{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer}
.lt-edit{background:#fffbeb;color:#d97706}.lt-edit:hover{background:#f59e0b;color:#fff}
.lt-del{background:#fef2f2;color:#ef4444}.lt-del:hover{background:#ef4444;color:#fff}
#devotionals_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#devotionals_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#devotionals_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#devotionals_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#devotionals_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#devotionals_table_wrapper .paginate_button.current,#devotionals_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#devotionals_table'))$('#devotionals_table').DataTable().destroy();
  $('#devotionals_table').DataTable({
    processing:true,serverSide:true,pageLength:15,
    ajax:{url:baseURL+'/getDevotionals',type:'POST'},
    dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'},
      processing:'<div style="padding:20px;color:var(--t3);">Loading…</div>',
      emptyTable:'<div style="padding:40px;text-align:center;color:var(--t3);">No devotionals found</div>'},
    columnDefs:[
      {targets:0,width:'50px',className:'text-muted',orderable:false},
      {targets:1,render:function(d,t){return t!=='display'?d:'<span class="lt-date">'+$('<div>').text(d).html()+'</span>';}},
      {targets:2,render:function(d,t){return t!=='display'?d:'<b style="color:var(--t1)">'+$('<div>').text(d).html()+'</b>';}},
      {targets:3,orderable:false,className:'text-center',render:function(h,t){
        if(t!=='display')return'';
        var m=h.match(/editDevotional\/(\d+)/);if(!m)return h;
        var id=m[1],b=typeof baseURL!=='undefined'?baseURL:'';
        return'<div style="display:flex;gap:5px;justify-content:center;">'+
          '<a href="'+b+'/editDevotional/'+id+'" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>'+
          '<a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltDel(\'devotionals\','+id+')"><i class="dw dw-trash"></i></a></div>';
      }}
    ]
  });
});
function ltDel(type,id){swal({title:'Delete?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){var u={devotionals:'deleteDevotional',articles:'deleteArticle',hymns:'deleteHymn'};document.location.href=baseURL+'/'+(u[type]||'delete')+'/'+id;});}
</script>
