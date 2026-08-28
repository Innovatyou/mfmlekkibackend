<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['videos'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['video_listing'] ?></span></nav>
      </div>
      <a href="<?= base_url('newVideo') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_video'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head">
        <div><h3 class="lt-htitle">All Videos</h3><p class="lt-hsub">Church video library</p></div>
        <div id="videoBulkBar" class="lt-bulkbar">
          <span id="videoSelCount"></span>
          <button type="button" class="lt-bulk-del-btn" onclick="bulkDeleteVideosSelected()"><i class="dw dw-trash"></i> Delete Selected</button>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="videos_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th style="width:36px;"><input type="checkbox" id="videoSelectAll"></th>
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
.lt-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:18px 22px;border-bottom:1px solid var(--border)}
.lt-htitle{font-size:1rem;font-weight:700;color:var(--t1);margin:0}
.lt-hsub{font-size:.8rem;color:var(--t3);margin:2px 0 0}
.lt-bulkbar{display:none;align-items:center;gap:10px}
.lt-bulkbar.active{display:flex}
.lt-bulkbar span{font-size:.8rem;color:var(--t3);font-weight:600}
.lt-bulk-del-btn{background:#fef2f2;color:#ef4444;border:1px solid #fecaca;border-radius:8px;padding:7px 14px;font-size:.8rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:all .15s}
.lt-bulk-del-btn:hover{background:#ef4444;color:#fff;border-color:#ef4444}
.lt-ab{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;cursor:pointer;border:none}
.lt-edit{background:#fffbeb;color:#d97706}.lt-edit:hover{background:#f59e0b;color:#fff}
.lt-del{background:#fef2f2;color:#ef4444}.lt-del:hover{background:#ef4444;color:#fff}
.lt-player-wrap{border-radius:8px;overflow:hidden;background:#000;display:inline-block}
.lt-player-wrap video,.lt-player-wrap iframe{display:block;border-radius:8px}
#videos_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;white-space:nowrap;background:#f8fafc}
#videos_table tbody td{padding:12px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#videos_table tbody tr:hover td{background:#f8fafc}
#videos_table tbody tr:last-child td{border-bottom:none!important}
#videos_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none;transition:border .15s}
#videos_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#videos_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#videos_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#videos_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#videos_table_wrapper .paginate_button.current,#videos_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#videos_table'))$('#videos_table').DataTable().destroy();
  $('#videos_table').DataTable({
    processing:true,serverSide:true,pageLength:10,
    order:[[3,'asc']],
    ajax:{url:baseURL+'/fetchVideos',type:'POST'},
    dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{
      search:'',searchPlaceholder:'Search videos…',
      lengthMenu:'Show _MENU_ videos',
      info:'Showing _START_–_END_ of _TOTAL_ videos',
      paginate:{previous:'‹',next:'›'},
      processing:'<div style="padding:20px;color:var(--t3);font-size:.875rem;">Loading…</div>',
      emptyTable:'<div style="padding:40px;text-align:center;color:var(--t3);">No videos found</div>',
      zeroRecords:'<div style="padding:40px;text-align:center;color:var(--t3);">No matching videos found</div>'
    },
    columnDefs:[
      {targets:0,orderable:false,searchable:false,className:'text-center',width:'36px'},
      {targets:1,width:'50px',className:'text-muted',orderable:false},
      {targets:2,orderable:false,render:function(html,type){
        if(type!=='display')return'';
        return'<div class="lt-player-wrap">'+html+'</div>';
      }},
      {targets:3,render:function(title,type){
        if(type!=='display')return title;
        return'<span style="font-weight:600;color:var(--t1);">'+$('<div>').text(title).html()+'</span>';
      }},
      {targets:4,render:function(desc,type){
        if(type!=='display')return desc||'';
        var s=$('<div>').text(desc||'').html();
        return'<span style="color:var(--t2);font-size:.8rem;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;" title="'+s+'">'+s+'</span>';
      }},
      {targets:5,orderable:false,className:'text-center',render:function(html,type){
        if(type!=='display')return'';
        var m=html.match(/editVideo\/(\d+)/);
        if(!m)return html;
        var id=m[1],base=(typeof baseURL!=='undefined'?baseURL:'');
        return'<div style="display:flex;gap:5px;justify-content:center;">'+
          '<a href="'+base+'/editVideo/'+id+'" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>'+
          '<a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" data-type="video" data-id="'+id+'" onclick="delete_item(event); return false;"><i class="dw dw-trash"></i></a>'+
          '</div>';
      }}
    ]
  });

  var videoSelected = new Set();

  function updateVideoBulkBar(){
    var bar = document.getElementById('videoBulkBar');
    var count = document.getElementById('videoSelCount');
    if(videoSelected.size > 0){
      bar.classList.add('active');
      count.textContent = videoSelected.size + ' selected';
    } else {
      bar.classList.remove('active');
    }
  }

  $('#videos_table').on('draw.dt', function(){
    videoSelected.clear();
    updateVideoBulkBar();
    var sa = document.getElementById('videoSelectAll');
    if(sa) sa.checked = false;
  });

  $(document).on('change', '#videos_table tbody .row-check', function(){
    var id = $(this).val();
    if(this.checked){ videoSelected.add(id); } else { videoSelected.delete(id); }
    updateVideoBulkBar();
    var all = $('#videos_table tbody .row-check').length;
    var checked = $('#videos_table tbody .row-check:checked').length;
    document.getElementById('videoSelectAll').checked = all > 0 && all === checked;
  });

  $(document).on('change', '#videoSelectAll', function(){
    var checked = this.checked;
    $('#videos_table tbody .row-check').each(function(){
      this.checked = checked;
      var id = $(this).val();
      if(checked){ videoSelected.add(id); } else { videoSelected.delete(id); }
    });
    updateVideoBulkBar();
  });

  window.bulkDeleteVideosSelected = function(){
    if(videoSelected.size === 0) return;
    var ids = Array.from(videoSelected);
    swal({
      title: 'Delete ' + ids.length + ' video(s)?',
      text: 'This action cannot be undone.',
      type: 'warning',
      confirmButtonColor: '#ef4444',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete'
    }, function(){
      var fd = new FormData();
      fd.append('data', JSON.stringify({ids: ids}));
      makeAjaxCall((typeof baseURL!=='undefined'?baseURL:'') + '/bulkDeleteVideos', 'POST', fd).then(function(data){
        videoSelected.clear();
        updateVideoBulkBar();
        $('#videos_table').DataTable().ajax.reload(null, false);
        if(data.status === 'ok'){
          success_alert(data.msg);
        } else {
          error_alert(data.msg);
        }
      }, function(status){
        error_alert('Request failed with status ' + status);
      });
    });
  };
});
</script>
