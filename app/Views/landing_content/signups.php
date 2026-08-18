<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Signup Requests</h1>
        <p class="page-subtitle">New members who joined through the public website, awaiting your review</p>
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
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">Pending Membership Requests</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Approve to add them as a full member, or reject the request</p>
        </div>
      </div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="signups_table" class="table nowrap" style="width:100%;">
          <thead>
            <tr>
              <th>#</th><th>Name</th><th>Contact</th><th>Gender</th><th>Submitted</th><th style="width:170px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($signupRequests as $index => $request): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc(trim($request->firstname . ' ' . $request->lastname)) ?></td>
                <td><?= esc($request->email) ?><br><span style="font-size:.75rem;color:var(--t3);"><?= esc($request->phonenumber) ?></span></td>
                <td><?= esc($request->gender) ?></td>
                <td><?= $request->date_inserted ? date('M j, Y g:i A', strtotime($request->date_inserted)) : '—' ?></td>
                <td class="text-center">
                  <div style="display:flex;gap:6px;justify-content:center;">
                    <a href="<?= base_url('viewMember/' . $request->id) ?>" class="mp-act-btn mp-act-view" title="View"><i class="dw dw-eye"></i></a>
                    <button type="button" data-id="<?= (int) $request->id ?>" class="mp-act-btn mp-act-approve signup-approve-btn" title="Approve"><i class="dw dw-check-circle-2"></i> Approve</button>
                    <button type="button" data-id="<?= (int) $request->id ?>" class="mp-act-btn mp-act-reject signup-reject-btn" title="Reject"><i class="dw dw-close-circle-1"></i> Reject</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
  .mp-act-btn { display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:opacity .15s; }
  .mp-act-btn:hover { opacity:.85;text-decoration:none; }
  .mp-act-approve { background:#10b981;color:#fff; }
  .mp-act-reject  { background:#ef4444;color:#fff; }
  .mp-act-view    { background:#6366f1;color:#fff; }
  #signups_table thead th { font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc; }
  #signups_table tbody td { padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle; }
  #signups_table tbody tr:hover td { background:#f8fafc; }
</style>
<script>
$(document).ready(function(){
  var dt = $('#signups_table').DataTable({
    pageLength: 15,
    order: [[4, 'desc']],
    dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language: {
      search: '', searchPlaceholder: 'Search…',
      lengthMenu: 'Show _MENU_',
      info: 'Showing _START_–_END_ of _TOTAL_',
      paginate: { previous: '‹', next: '›' },
      emptyTable: "No pending signup requests — you're all caught up!",
    },
    columnDefs: [
      { targets: 0, width: '50px', className: 'text-muted', orderable: false },
      { targets: [2,3,4], orderable: false },
      { targets: 5, orderable: false, className: 'text-center' },
    ]
  });

  $(document).on('click', '.signup-approve-btn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    swal({ title: 'Approve this member?', text: 'They will be added to your members list.', icon: 'success',
      buttons: ['Cancel', 'Approve'], dangerMode: false }).then(function(ok){
      if (ok) window.location.href = baseURL + '/approveSignupRequest/' + id;
    });
  });

  $(document).on('click', '.signup-reject-btn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    swal({ title: 'Reject this request?', text: 'This request will be marked as rejected.', icon: 'warning',
      buttons: ['Cancel', 'Reject'], dangerMode: true }).then(function(ok){
      if (ok) window.location.href = baseURL + '/rejectSignupRequest/' + id;
    });
  });
});
</script>
