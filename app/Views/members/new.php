<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['new_member'] ?></h1>
        <nav class="nm-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('membersListing') ?>"><?= $locale['members'] ?></a>
          <span>/</span>
          <span><?= $locale['new_member'] ?></span>
        </nav>
      </div>
      <a href="<?= base_url('membersListing') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back to Members
      </a>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="nm-alert nm-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="nm-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="nm-alert nm-alert-danger">
        <i class="dw dw-close-circle-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="nm-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url() ?>/saveNewMember" enctype="multipart/form-data" id="newMemberForm">
      <?= csrf_field() ?>
      <div class="row">

        <!-- ── Left: Main form ── -->
        <div class="col-lg-8 mb-20">

          <!-- Personal Information -->
          <div class="card-box nm-section">
            <div class="nm-section-header">
              <div class="nm-section-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="dw dw-user1"></i>
              </div>
              <div>
                <h3 class="nm-section-title">Personal Information</h3>
                <p class="nm-section-sub">Basic details about the member</p>
              </div>
            </div>

            <div class="nm-grid-2">
              <div class="nm-field">
                <label class="nm-label" for="nm-firstname"><?= $locale['first_name'] ?> <span class="nm-req">*</span></label>
                <input type="text" class="nm-input" id="nm-firstname" name="firstname"
                       placeholder="e.g. John" required autofocus>
              </div>
              <div class="nm-field">
                <label class="nm-label" for="nm-lastname"><?= $locale['last_name'] ?> <span class="nm-req">*</span></label>
                <input type="text" class="nm-input" id="nm-lastname" name="lastname"
                       placeholder="e.g. Doe" required>
              </div>
            </div>

            <!-- Gender -->
            <div class="nm-field" style="margin-top:16px;">
              <label class="nm-label"><?= $locale['gender'] ?> <span class="nm-req">*</span></label>
              <div class="nm-gender-group">
                <label class="nm-gender-card" id="gc-male">
                  <input type="radio" name="gender" value="Male" required>
                  <div class="nm-gender-inner">
                    <span style="font-size:1.4rem;">♂</span>
                    <span>Male</span>
                  </div>
                </label>
                <label class="nm-gender-card" id="gc-female">
                  <input type="radio" name="gender" value="Female">
                  <div class="nm-gender-inner">
                    <span style="font-size:1.4rem;">♀</span>
                    <span>Female</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- DOB -->
            <div class="nm-field" style="margin-top:16px;">
              <label class="nm-label" for="nm-dob"><?= $locale['dob'] ?> <span class="nm-req">*</span></label>
              <input type="date" class="nm-input" id="nm-dob" name="dob" required>
              <span class="nm-hint" id="nm-age-hint"></span>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="card-box nm-section" style="margin-top:16px;">
            <div class="nm-section-header">
              <div class="nm-section-icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6);">
                <i class="dw dw-phone"></i>
              </div>
              <div>
                <h3 class="nm-section-title">Contact Details</h3>
                <p class="nm-section-sub">How to reach this member</p>
              </div>
            </div>

            <div class="nm-grid-2">
              <div class="nm-field">
                <label class="nm-label" for="nm-email"><?= $locale['email_address'] ?> <span class="nm-req">*</span></label>
                <div style="position:relative;">
                  <input type="email" class="nm-input" id="nm-email" name="email"
                         placeholder="e.g. john@email.com" required style="padding-left:38px;">
                  <i class="dw dw-email" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none;"></i>
                </div>
              </div>
              <div class="nm-field">
                <label class="nm-label" for="nm-phone"><?= $locale['phone_number'] ?> <span class="nm-req">*</span></label>
                <div style="position:relative;">
                  <input type="tel" class="nm-input" id="nm-phone" name="phonenumber"
                         placeholder="e.g. +1 234 567 8900" required style="padding-left:38px;">
                  <i class="dw dw-phone" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:.9rem;pointer-events:none;"></i>
                </div>
              </div>
            </div>

            <div class="nm-field" style="margin-top:16px;">
              <label class="nm-label" for="nm-address"><?= $locale['address'] ?></label>
              <input type="text" class="nm-input" id="nm-address" name="address"
                     placeholder="e.g. 123 Church Street, City">
            </div>
          </div>

          <!-- Occupation & Social Media -->
          <div class="card-box nm-section" style="margin-top:16px;">
            <div class="nm-section-header">
              <div class="nm-section-icon" style="background:linear-gradient(135deg,#10b981,#06b6d4);">
                <i class="dw dw-briefcase"></i>
              </div>
              <div>
                <h3 class="nm-section-title">Additional Details</h3>
                <p class="nm-section-sub">Occupation and social media profiles</p>
              </div>
            </div>

            <div class="nm-field">
              <label class="nm-label" for="nm-occupation"><?= $locale['occupation'] ?></label>
              <input type="text" class="nm-input" id="nm-occupation" name="occupation"
                     placeholder="e.g. Teacher, Engineer, Doctor…">
            </div>

            <div class="nm-grid-3" style="margin-top:16px;">
              <div class="nm-field">
                <label class="nm-label" for="nm-facebook"><?= $locale['facebook_profile'] ?></label>
                <div style="position:relative;">
                  <input type="text" class="nm-input" id="nm-facebook" name="facebook"
                         placeholder="Facebook username" style="padding-left:38px;">
                  <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:.85rem;color:#1877f2;font-weight:700;">f</span>
                </div>
              </div>
              <div class="nm-field">
                <label class="nm-label" for="nm-twitter"><?= $locale['twitter_profile'] ?></label>
                <div style="position:relative;">
                  <input type="text" class="nm-input" id="nm-twitter" name="twitter"
                         placeholder="@username" style="padding-left:38px;">
                  <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:.85rem;color:#1da1f2;font-weight:700;">𝕏</span>
                </div>
              </div>
              <div class="nm-field">
                <label class="nm-label" for="nm-linkedin"><?= $locale['linkedin_profile'] ?></label>
                <div style="position:relative;">
                  <input type="text" class="nm-input" id="nm-linkedin" name="linkedln"
                         placeholder="LinkedIn username" style="padding-left:38px;">
                  <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:.85rem;color:#0a66c2;font-weight:700;">in</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit -->
          <div style="display:flex;gap:10px;margin-top:4px;">
            <button type="submit" id="nm-submit" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:11px 28px;">
              <i class="dw dw-check-circle-2" id="nm-btn-icon" style="margin-right:7px;"></i>
              <span id="nm-btn-text"><?= $locale['save_new'] ?></span>
            </button>
            <a href="<?= base_url('membersListing') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:11px 24px;">
              Cancel
            </a>
          </div>

        </div>

        <!-- ── Right: Sidebar ── -->
        <div class="col-lg-4 mb-20">

          <!-- Photo upload -->
          <div class="card-box" style="padding:20px;margin-bottom:16px;">
            <h4 class="nm-sidebar-title">Member Photo</h4>
            <p style="font-size:.8rem;color:var(--t3);margin:0 0 16px;">Optional. PNG or JPG, max 5 MB.</p>

            <!-- Avatar preview -->
            <div style="text-align:center;margin-bottom:16px;">
              <div id="nm-photo-preview" style="width:80px;height:80px;border-radius:20px;margin:0 auto 10px;overflow:hidden;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:800;cursor:pointer;transition:opacity .15s;" onclick="document.getElementById('nm-photo-input').click()" title="Click to upload photo">
                <span id="nm-avatar-initials">?</span>
                <img id="nm-avatar-img" style="width:100%;height:100%;object-fit:cover;display:none;" alt="preview">
              </div>
              <p style="font-size:.75rem;color:var(--t3);margin:0;">Click avatar to upload</p>
            </div>

            <input type="file" name="thumbnail" id="nm-photo-input" accept=".jpg,.jpeg,.png,.PNG"
                   style="display:none;" onchange="previewPhoto(this)">

            <button type="button" onclick="document.getElementById('nm-photo-input').click()"
                    style="display:block;width:100%;padding:9px;border-radius:8px;border:1.5px dashed var(--border);background:#f8fafc;color:var(--t2);font-size:.82rem;font-weight:600;cursor:pointer;transition:border-color .15s;"
                    onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
              <i class="dw dw-upload" style="margin-right:5px;"></i>Choose Photo
            </button>
            <p id="nm-file-name" style="font-size:.75rem;color:var(--t3);margin:6px 0 0;text-align:center;"></p>
          </div>

          <!-- Quick tips -->
          <div class="card-box" style="padding:20px;">
            <h4 class="nm-sidebar-title">Quick Tips</h4>
            <div class="nm-tip">
              <div class="nm-tip-dot" style="background:#6366f1;"></div>
              <span>Fields marked <strong style="color:#ef4444;">*</strong> are required</span>
            </div>
            <div class="nm-tip">
              <div class="nm-tip-dot" style="background:#10b981;"></div>
              <span>Social media profiles are optional but help with member engagement</span>
            </div>
            <div class="nm-tip">
              <div class="nm-tip-dot" style="background:#f59e0b;"></div>
              <span>A valid email allows sending the member notifications and updates</span>
            </div>
            <div class="nm-tip">
              <div class="nm-tip-dot" style="background:#06b6d4;"></div>
              <span>You can edit all details later from the members listing page</span>
            </div>
          </div>

        </div>
      </div>
    </form>

  </div>
</div>

<style>
  .nm-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .nm-breadcrumb a { color:var(--t3);text-decoration:none; }
  .nm-breadcrumb a:hover { color:var(--accent); }
  .nm-breadcrumb span { margin:0 5px; }

  .nm-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .nm-alert i { font-size:1.1rem;flex-shrink:0; }
  .nm-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .nm-alert-danger  { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
  .nm-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }
  .nm-alert-close:hover { opacity:1; }

  .nm-section { padding:0;overflow:hidden; }
  .nm-section-header {
    display:flex;align-items:center;gap:14px;padding:18px 22px;
    border-bottom:1px solid var(--border);
  }
  .nm-section-icon {
    width:40px;height:40px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;
  }
  .nm-section-title { font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 1px; }
  .nm-section-sub   { font-size:.78rem;color:var(--t3);margin:0; }

  .nm-section > .nm-grid-2,
  .nm-section > .nm-grid-3,
  .nm-section > .nm-field,
  .nm-section > div[style*="margin-top"] { padding:0 22px 20px; }
  .nm-section-header + .nm-grid-2,
  .nm-section-header + .nm-grid-3,
  .nm-section-header + .nm-field,
  .nm-section-header + div { padding-top:20px; }

  .nm-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
  .nm-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; }
  @media(max-width:640px) {
    .nm-grid-2 { grid-template-columns:1fr; }
    .nm-grid-3 { grid-template-columns:1fr; }
  }

  .nm-field { display:flex;flex-direction:column; }
  .nm-label { font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;letter-spacing:.01em; }
  .nm-req   { color:#ef4444; }
  .nm-input {
    padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;
    transition:border .15s,box-shadow .15s;font-family:inherit;width:100%;box-sizing:border-box;
  }
  .nm-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .nm-hint { font-size:.76rem;color:var(--t3);margin-top:5px; }

  /* Gender cards */
  .nm-gender-group { display:flex;gap:10px; }
  .nm-gender-card { flex:1;cursor:pointer; }
  .nm-gender-card input { position:absolute;opacity:0;pointer-events:none; }
  .nm-gender-inner {
    display:flex;flex-direction:column;align-items:center;gap:4px;padding:12px 8px;
    border:1.5px solid var(--border);border-radius:10px;font-size:.82rem;font-weight:600;
    color:var(--t2);transition:all .15s;background:#fff;
  }
  .nm-gender-card input:checked + .nm-gender-inner {
    border-color:var(--accent);background:#eef2ff;color:var(--accent);
    box-shadow:0 0 0 3px rgba(99,102,241,.1);
  }
  .nm-gender-card:hover .nm-gender-inner { border-color:var(--accent);background:#f8faff; }

  /* Sidebar */
  .nm-sidebar-title { font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);margin:0 0 12px; }

  .nm-tip { display:flex;align-items:flex-start;gap:8px;margin-bottom:10px;font-size:.8rem;color:var(--t2);line-height:1.5; }
  .nm-tip-dot { width:7px;height:7px;border-radius:50%;flex-shrink:0;margin-top:5px; }
</style>

<script>
  // Live avatar initials from name fields
  function updateInitials() {
    var fn = document.getElementById('nm-firstname').value.trim();
    var ln = document.getElementById('nm-lastname').value.trim();
    var init = (fn ? fn.charAt(0) : '') + (ln ? ln.charAt(0) : '');
    var el = document.getElementById('nm-avatar-initials');
    if (el) el.textContent = init.toUpperCase() || '?';
  }
  document.getElementById('nm-firstname').addEventListener('input', updateInitials);
  document.getElementById('nm-lastname').addEventListener('input', updateInitials);

  // Age hint from DOB
  document.getElementById('nm-dob').addEventListener('change', function () {
    var dob = new Date(this.value);
    if (isNaN(dob)) return;
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    var hint = document.getElementById('nm-age-hint');
    if (hint) hint.textContent = age >= 0 ? 'Age: ' + age + ' years old' : '';
  });

  // Photo preview
  function previewPhoto(input) {
    if (input.files && input.files[0]) {
      var file = input.files[0];
      var reader = new FileReader();
      reader.onload = function (e) {
        var img = document.getElementById('nm-avatar-img');
        var initials = document.getElementById('nm-avatar-initials');
        img.src = e.target.result;
        img.style.display = 'block';
        initials.style.display = 'none';
      };
      reader.readAsDataURL(file);
      var nameEl = document.getElementById('nm-file-name');
      if (nameEl) nameEl.textContent = file.name;
    }
  }

  // Gender card styling
  document.querySelectorAll('.nm-gender-card input').forEach(function (radio) {
    radio.addEventListener('change', function () {
      document.querySelectorAll('.nm-gender-card input').forEach(function (r) {
        r.closest('.nm-gender-card').querySelector('.nm-gender-inner').style.borderColor = '';
      });
    });
  });

  // Submit state
  document.getElementById('newMemberForm').addEventListener('submit', function () {
    var btn = document.getElementById('nm-submit');
    btn.disabled = true;
    document.getElementById('nm-btn-icon').className = 'dw dw-loading';
    document.getElementById('nm-btn-text').textContent = 'Saving…';
  });
</script>
