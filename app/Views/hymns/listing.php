<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['hymns'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['hymns'] ?></span></nav>
      </div>
      <a href="<?= base_url('newHymn') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_hymn'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Hymns &amp; Worship Lyrics</h3><p class="lt-hsub">Church hymnal collection</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="hymns_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th><?=$locale['title']?></th><th style="width:90px;"><?=$locale['action']?></th>
          </tr></thead>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
#hymns_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#hymns_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#hymns_table tbody tr:hover td{background:#f8fafc}#hymns_table tbody tr:last-child td{border-bottom:none!important}
#hymns_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#hymns_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#hymns_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#hymns_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#hymns_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#hymns_table_wrapper .paginate_button.current,#hymns_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#hymns_table'))$('#hymns_table').DataTable().destroy();
  $('#hymns_table').DataTable({
    processing:true,serverSide:true,pageLength:15,
    ajax:{url:baseURL+'/getHymns',type:'POST'},
    dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search hymns…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'},
      processing:'<div style="padding:20px;color:var(--t3);">Loading…</div>',
      emptyTable:'<div style="padding:40px;text-align:center;color:var(--t3);">No hymns found</div>'},
    columnDefs:[
      {targets:0,width:'50px',className:'text-muted',orderable:false},
      {targets:1,render:function(d,t){return t!=='display'?d:'<b style="color:var(--t1)">'+$('<div>').text(d).html()+'</b>';}},
      {targets:2,orderable:false,className:'text-center',render:function(h,t){
        if(t!=='display')return'';
        var m=h.match(/editHymn\/(\d+)/);if(!m)return h;
        var id=m[1],b=typeof baseURL!=='undefined'?baseURL:'';
        return'<div style="display:flex;gap:5px;justify-content:center;">'+
          '<a href="'+b+'/editHymn/'+id+'" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>'+
          '<a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltDel(\'hymns\','+id+')"><i class="dw dw-trash"></i></a></div>';
      }}
    ]
  });
});
</script>
