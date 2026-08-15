<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Message from <?= esc($item->name) ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('contactMessages') ?>">Contact Messages</a><span>/</span><span>View</span></nav>
      </div>
      <?php $badges = ['unread'=>['Unread','badge-warning'],'read'=>['Read','badge-secondary'],'replied'=>['Replied','badge-success']]; [$label,$cls] = $badges[$item->status] ?? ['—','badge-secondary']; ?>
      <span class="badge badge-pill <?= $cls ?>" style="font-size:.8rem;padding:6px 14px;"><?= $label ?></span>
    </div>

    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>

    <div class="row">
      <div class="col-lg-4">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title">Sender</h3></div>
          <div class="nf-card-body">
            <div style="margin-bottom:14px;">
              <label class="nf-label">Name</label>
              <input type="text" class="nf-input" value="<?= esc($item->name) ?>" readonly>
            </div>
            <div style="margin-bottom:14px;">
              <label class="nf-label">Email</label>
              <input type="text" class="nf-input" value="<?= esc($item->email) ?>" readonly>
            </div>
            <?php if(!empty($item->phone)):?>
            <div style="margin-bottom:14px;">
              <label class="nf-label">Phone</label>
              <input type="text" class="nf-input" value="<?= esc($item->phone) ?>" readonly>
            </div>
            <?php endif;?>
            <div>
              <label class="nf-label">Received</label>
              <input type="text" class="nf-input" value="<?= $item->created_at ? date('M j, Y g:i A', strtotime($item->created_at)) : '—' ?>" readonly>
            </div>
          </div>
        </div>
        <div style="margin-top:16px;">
          <a href="javascript:void(0)" onclick="cmDelConfirm(<?= $item->id ?>)" class="btn btn-light" style="width:100%;border-radius:8px;color:#ef4444;font-weight:600;">
            <i class="dw dw-trash" style="margin-right:6px;"></i>Delete Message
          </a>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="nf-card" style="margin-bottom:16px;">
          <div class="nf-card-head"><h3 class="nf-card-title"><?= $item->subject ? esc($item->subject) : '(no subject)' ?></h3></div>
          <div class="nf-card-body">
            <p style="white-space:pre-line;color:var(--t1);line-height:1.7;margin:0;"><?= esc($item->message) ?></p>
          </div>
        </div>

        <?php if(!empty($item->admin_reply)):?>
        <div class="nf-card" style="margin-bottom:16px;background:#f0fdf4;border-color:#bbf7d0;">
          <div class="nf-card-head"><h3 class="nf-card-title" style="color:#166534;">Your Reply — sent <?= $item->replied_at ? date('M j, Y g:i A', strtotime($item->replied_at)) : '' ?></h3></div>
          <div class="nf-card-body">
            <p style="white-space:pre-line;color:#166534;line-height:1.7;margin:0;"><?= esc($item->admin_reply) ?></p>
          </div>
        </div>
        <?php endif;?>

        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title"><?= $item->admin_reply ? 'Send Another Reply' : 'Reply' ?></h3></div>
          <div class="nf-card-body">
            <form method="POST" action="<?= base_url('replyContactMessage') ?>">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $item->id ?>">
              <textarea name="reply" class="nf-input" rows="6" placeholder="Type your reply — it will be emailed to <?= esc($item->email) ?>" required></textarea>
              <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-mail-1" style="margin-right:6px;"></i>Send Reply</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<script>
function cmDelConfirm(id){
  swal({title:'Delete this message?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){
    document.location.href = baseURL + '/deleteContactMessage/' + id;
  });
}
</script>
