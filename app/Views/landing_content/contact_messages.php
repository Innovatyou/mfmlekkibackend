<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Contact Messages</h1>
        <p class="page-subtitle">Messages submitted through the website's contact form</p>
      </div>
      <a href="<?= base_url('landingContent') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back to Website
      </a>
    </div>

    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>

    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">Inbox</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Click a message to view and reply — replies are emailed to the sender</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="cm_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th><th>From</th><th>Subject</th><th>Status</th><th>Received</th><th style="width:100px;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
  .mp-act-btn { display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:opacity .15s; }
  .mp-act-btn:hover { opacity:.85;text-decoration:none; }
  .mp-act-reject  { background:#ef4444;color:#fff; }
  .mp-act-view    { background:#6366f1;color:#fff; }
  #cm_table thead th { font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc; }
  #cm_table tbody td { padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle; }
  #cm_table tbody tr:hover td { background:#f8fafc; }
</style>
<script>
$(document).ready(function(){
  $('#cm_table').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 15,
    order: [[4, 'desc']],
    ajax: { url: baseURL + '/getContactMessages', type: 'POST' },
    dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language: {
      search: '', searchPlaceholder: 'Search…',
      lengthMenu: 'Show _MENU_',
      info: 'Showing _START_–_END_ of _TOTAL_',
      paginate: { previous: '‹', next: '›' },
      emptyTable: 'No messages yet.',
    },
    columnDefs: [
      { targets: 0, width: '50px', className: 'text-muted', orderable: false },
      { targets: [1,2,3,4], orderable: false },
      { targets: 5, orderable: false, className: 'text-center' },
    ]
  });
});

function cmDelConfirm(id){
  swal({title:'Delete this message?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){
    document.location.href = baseURL + '/deleteContactMessage/' + id;
  });
}
</script>
