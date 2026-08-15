<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['members'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('membersListing') ?>"><?= $locale['members'] ?></a><span>/</span><span><?= $locale['view_members'] ?></span></nav>
      </div>
      <?php if(isset($member->signup_status) && $member->signup_status !== 'approved'):?>
      <div style="display:flex;align-items:center;gap:10px;">
        <span class="badge badge-pill <?= $member->signup_status=='pending' ? 'badge-warning' : 'badge-secondary' ?>" style="font-size:.8rem;padding:6px 14px;">
          <?= $member->signup_status=='pending' ? 'Pending Review' : 'Rejected' ?>
        </span>
        <?php if($member->signup_status=='pending'):?>
        <a href="<?= base_url('approveSignupRequest/'.$member->id) ?>" class="btn btn-sm" style="background:#10b981;color:#fff;border-radius:8px;font-weight:600;">Approve</a>
        <a href="<?= base_url('rejectSignupRequest/'.$member->id) ?>" class="btn btn-sm" style="background:#ef4444;color:#fff;border-radius:8px;font-weight:600;">Reject</a>
        <?php endif;?>
      </div>
      <?php endif;?>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <div class="nf-card">
          <div class="nf-card-body" style="display:flex;flex-direction:column;align-items:center;padding:24px;">
            <?php if(!empty($member->thumbnail)): ?>
            <img src="<?= esc($member->thumbnail) ?>" style="width:100px;height:100px;border-radius:50%;object-fit:cover;" alt="Photo">
            <?php else: ?>
            <div style="width:100px;height:100px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--t3);">
              <i class="dw dw-user"></i>
            </div>
            <?php endif; ?>
            <p style="font-size:1rem;font-weight:700;color:var(--t1);margin:12px 0 2px;"><?= esc($member->firstname . ' ' . $member->lastname) ?></p>
            <p style="font-size:.8rem;color:var(--t3);margin:0;"><?= esc($member->email) ?></p>
          </div>
        </div>
        <div class="nf-card" style="margin-top:16px;">
          <div class="nf-card-head"><h3 class="nf-card-title">Social Profiles</h3></div>
          <div class="nf-card-body">
            <div style="margin-bottom:12px;">
              <label class="nf-label"><?= $locale['facebook_profile'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($member->facebook) ?>" readonly>
            </div>
            <div style="margin-bottom:12px;">
              <label class="nf-label"><?= $locale['twitter_profile'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($member->twitter) ?>" readonly>
            </div>
            <div>
              <label class="nf-label"><?= $locale['linkedin_profile'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($member->linkedln) ?>" readonly>
            </div>
          </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px;">
          <a href="<?= base_url('editMember/' . $member->id) ?>" class="btn btn-primary nf-submit">Edit Member</a>
          <a href="javascript:history.back()" class="btn btn-light nf-cancel">Back</a>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="nf-card">
          <div class="nf-card-head"><h3 class="nf-card-title">Personal Information</h3></div>
          <div class="nf-card-body">
            <div class="nf-row" style="margin-bottom:16px;">
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['first_name'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->firstname) ?>" readonly>
              </div>
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['last_name'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->lastname) ?>" readonly>
              </div>
            </div>
            <div style="margin-bottom:16px;">
              <label class="nf-label"><?= $locale['gender'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($member->gender) ?>" readonly>
            </div>
            <div class="nf-row" style="margin-bottom:16px;">
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['email_address'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->email) ?>" readonly>
              </div>
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['phone_number'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->phonenumber) ?>" readonly>
              </div>
            </div>
            <div class="nf-row" style="margin-bottom:16px;">
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['dob'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->dob) ?>" readonly>
              </div>
              <div class="nf-col-half">
                <label class="nf-label"><?= $locale['occupation'] ?></label>
                <input type="text" class="nf-input" value="<?= esc($member->occupation) ?>" readonly>
              </div>
            </div>
            <div>
              <label class="nf-label"><?= $locale['address'] ?></label>
              <input type="text" class="nf-input" value="<?= esc($member->address) ?>" readonly>
            </div>
          </div>
        </div>

        <?php if(!empty($answers)):?>
        <div class="nf-card" style="margin-top:16px;">
          <div class="nf-card-head"><h3 class="nf-card-title">Membership Form Answers</h3></div>
          <div class="nf-card-body">
            <?php foreach($answers as $a):?>
            <div style="margin-bottom:14px;">
              <label class="nf-label"><?= esc($a->label) ?></label>
              <p style="font-size:.875rem;color:var(--t1);margin:0;padding:9px 12px;background:#f8fafc;border:1.5px solid var(--border);border-radius:8px;"><?= esc($a->value) !== '' ? esc($a->value) : '—' ?></p>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
