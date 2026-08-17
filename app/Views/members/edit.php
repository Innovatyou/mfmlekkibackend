<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['members'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('membersListing') ?>"><?= $locale['members'] ?></a><span>/</span><span><?= $locale['edit_member'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editMemberData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $member->id ?>">
      <div class="row">
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['optional_member_photo'] ?></h3><p class="nf-card-sub">Click to change photo</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="mem-photo-zone" style="min-height:150px;display:flex;flex-direction:column;align-items:center;justify-content:center;" onclick="document.getElementById('mem-photo-input').click()">
                <?php if(!empty($member->thumbnail)):?>
                <img src="<?=esc($member->thumbnail)?>" style="width:100px;height:100px;border-radius:50%;object-fit:cover;" alt="Photo">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-user"></i></div>
                <p class="nf-upload-text">Upload photo</p>
                <p class="nf-upload-hint">PNG, JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="mem-photo-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'mem-photo-zone')">
            </div>
          </div>
          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title">Social Profiles</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:12px;">
                <label class="nf-label"><?= $locale['facebook_profile'] ?></label>
                <input type="text" name="facebook" class="nf-input" value="<?= esc($member->facebook) ?>">
              </div>
              <div style="margin-bottom:12px;">
                <label class="nf-label"><?= $locale['twitter_profile'] ?></label>
                <input type="text" name="twitter" class="nf-input" value="<?= esc($member->twitter) ?>">
              </div>
              <div>
                <label class="nf-label"><?= $locale['linkedin_profile'] ?></label>
                <input type="text" name="linkedln" class="nf-input" value="<?= esc($member->linkedln) ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Personal Information</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['first_name'] ?></label>
                  <input type="text" name="firstname" class="nf-input" value="<?= esc($member->firstname) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['last_name'] ?></label>
                  <input type="text" name="lastname" class="nf-input" value="<?= esc($member->lastname) ?>" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['gender'] ?></label>
                <div style="display:flex;gap:20px;padding:10px 0;">
                  <label style="display:flex;align-items:center;gap:8px;font-size:.875rem;cursor:pointer;">
                    <input type="radio" name="gender" value="Male" <?= $member->gender=='Male'?'checked':'' ?> style="accent-color:var(--accent);width:16px;height:16px;"> Male
                  </label>
                  <label style="display:flex;align-items:center;gap:8px;font-size:.875rem;cursor:pointer;">
                    <input type="radio" name="gender" value="Female" <?= $member->gender=='Female'?'checked':'' ?> style="accent-color:var(--accent);width:16px;height:16px;"> Female
                  </label>
                </div>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['email_address'] ?></label>
                  <input type="email" name="email" class="nf-input" value="<?= esc($member->email) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['phone_number'] ?></label>
                  <input type="number" name="phonenumber" class="nf-input" value="<?= esc($member->phonenumber) ?>" required>
                </div>
              </div>
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['dob'] ?></label>
                  <input type="date" name="dob" class="nf-input" value="<?= esc($member->dob) ?>" required>
                </div>
                <div class="nf-col-half">
                  <label class="nf-label"><?= $locale['occupation'] ?></label>
                  <input type="text" name="occupation" class="nf-input" value="<?= esc($member->occupation) ?>">
                </div>
              </div>
              <div>
                <label class="nf-label"><?= $locale['address'] ?></label>
                <input type="text" name="address" class="nf-input" value="<?= esc($member->address) ?>">
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update'] ?></button>
            <a href="<?= base_url('membersListing') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<script>
function nfPreview(input,zoneId){var z=document.getElementById(zoneId);if(input.files&&input.files[0]){var r=new FileReader();r.onload=function(e){z.innerHTML='<img src="'+e.target.result+'" style="width:100px;height:100px;border-radius:50%;object-fit:cover;" alt="Preview">';};r.readAsDataURL(input.files[0]);}}
</script>
