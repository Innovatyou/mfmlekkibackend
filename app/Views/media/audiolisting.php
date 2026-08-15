<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['audios'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['audios_listing'] ?></span></nav>
      </div>
      <a href="<?= base_url('newaudio') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_audio'] ?? 'New Audio' ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">All Audio</h3><p class="lt-hsub">Church audio library</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="audios_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['player'] ?></th>
              <th><?= $locale['title'] ?></th>
              <th><?= $locale['description'] ?></th>
              <th style="width:90px;"><?= $locale['action'] ?></th>
            </tr>
          </thead>
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
.lt-audio-wrap audio{display:block;border-radius:8px;min-width:260px}
#audios_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;white-space:nowrap;background:#f8fafc}
#audios_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#audios_table tbody tr:hover td{background:#f8fafc}
#audios_table tbody tr:last-child td{border-bottom:none!important}
#audios_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none;transition:border .15s}
#audios_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#audios_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#audios_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#audios_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#audios_table_wrapper .paginate_button.current,#audios_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#audios_table'))$('#audios_table').DataTable().destroy();
  $('#audios_table').DataTable({
    processing:true,serverSide:true,pageLength:10,
    ajax:{url:baseURL+'/fetchAudios',type:'POST'},
    dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{
      search:'',searchPlaceholder:'Search audio…',
      lengthMenu:'Show _MENU_ audio',
      info:'Showing _START_–_END_ of _TOTAL_ audio',
      paginate:{previous:'‹',next:'›'},
      processing:'<div style="padding:20px;color:var(--t3);font-size:.875rem;">Loading…</div>',
      emptyTable:'<div style="padding:40px;text-align:center;color:var(--t3);">No audio found</div>',
      zeroRecords:'<div style="padding:40px;text-align:center;color:var(--t3);">No matching audio found</div>'
    },
    columnDefs:[
      {targets:0,width:'50px',className:'text-muted',orderable:false},
      {targets:1,orderable:false,render:function(html,type){
        if(type!=='display')return'';
        return'<div class="lt-audio-wrap">'+html+'</div>';
      }},
      {targets:2,render:function(title,type){
        if(type!=='display')return title;
        return'<span style="font-weight:600;color:var(--t1);">'+$('<div>').text(title).html()+'</span>';
      }},
      {targets:3,render:function(desc,type){
        if(type!=='display')return desc||'';
        var s=$('<div>').text(desc||'').html();
        return'<span style="color:var(--t2);font-size:.8rem;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;" title="'+s+'">'+s+'</span>';
      }},
      {targets:4,orderable:false,className:'text-center',render:function(html,type){
        if(type!=='display')return'';
        var m=html.match(/editAudio\/(\d+)/);
        if(!m)return html;
        var id=m[1],base=(typeof baseURL!=='undefined'?baseURL:'');
        return'<div style="display:flex;gap:5px;justify-content:center;">'+
          '<a href="'+base+'/editAudio/'+id+'" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>'+
          '<a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltDelAudio('+id+')"><i class="dw dw-trash"></i></a>'+
          '</div>';
      }}
    ]
  });
});
function ltDelAudio(id){
  swal({title:'Delete audio?',text:'This cannot be undone.',type:'warning',showCancelButton:true,
    confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},
    function(){document.location.href=(typeof baseURL!=='undefined'?baseURL:'')+'/deleteAudio/'+id;});
}
</script>
