<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['prayer_requests'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('prayersListing') ?>"><?= $locale['prayer_requests'] ?></a><span>/</span><span><?= $locale['view_request'] ?></span></nav>
      </div>
      <?php $approved = $prayer->status == 0 ? 1 : 0; ?>
      <a href="<?= base_url('editPrayerStatus/' . $prayer->id . '/' . $approved) ?>" class="btn <?= $prayer->status == 1 ? 'btn-success' : 'btn-warning' ?> lt-cta">
        <i class="dw dw-<?= $prayer->status == 1 ? 'check' : 'close' ?>-circle-2"></i><?= $prayer->status == 1 ? 'Approve' : 'Disapprove' ?>
      </a>
    </div>
    <?php if (session()->getFlashdata('success')): ?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?= esc(session()->getFlashdata('error')) ?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif; ?>
    <div class="row">
      <div class="col-lg-8">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['view_request'] ?></h3></div>
          <div class="nf-card-body">
            <div class="nf-row" style="margin-bottom:16px;">
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['requester'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($prayer->requester) ?>" readonly>
              </div>
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['prayer_visibility'] ?></label>
                <input type="text" class="nf-input" value="<?= $prayer->public==0 ? $locale['public'] : $locale['private'] ?>" readonly>
              </div>
            </div>
            <div style="margin-bottom:16px;">
              <label class="nf-label"><?= $locale['request_title'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($prayer->title) ?>" readonly>
            </div>
            <div>
              <label class="nf-label"><?= $locale['request_content'] ?></label>
              <div class="nf-input" style="min-height:120px;height:auto;white-space:pre-wrap;"><?= $prayer->content ?></div>
            </div>
          </div>
        </div>
        <div style="margin-top:24px;">
          <a href="javascript:history.back()" class="btn btn-light nf-cancel">Back</a>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title">Reply to Requester</h3></div>
          <div class="nf-card-body">
            <?php if (!empty($prayer->admin_reply)): ?>
            <div style="background:#f8fafc;border:1px solid var(--border);border-radius:9px;padding:12px 14px;margin-bottom:16px;">
              <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);margin-bottom:4px;">
                Last reply &mdash; <?= esc($prayer->replied_by) ?> on <?= date('M j, Y g:i A', strtotime($prayer->replied_at)) ?>
              </div>
              <div style="font-size:.875rem;color:var(--t1);white-space:pre-wrap;"><?= esc($prayer->admin_reply) ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($prayer->email)): ?>
            <p style="font-size:.78rem;color:var(--t3);margin:0 0 12px;">The requester is a registered app user &mdash; sending a reply pushes it to their phone as a notification.</p>
            <?php else: ?>
            <p style="font-size:.78rem;color:#92400e;background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:8px 10px;margin:0 0 12px;">No app account is linked to this request, so a reply will be saved but not delivered anywhere.</p>
            <?php endif; ?>

            <form method="post" action="<?= base_url('replyPrayer') ?>">
              <input type="hidden" name="id" value="<?= $prayer->id ?>">
              <?= csrf_field() ?>
              <textarea name="reply" class="nf-input" rows="5" placeholder="Write your reply…" required></textarea>
              <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px;">
                <i class="dw dw-send"></i> Send Reply
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
