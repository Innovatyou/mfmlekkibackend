<?php $session = session(); ?>
<?php
  $initials = strtoupper(substr($user->fullname ?? 'U', 0, 1));
  $gradients = ['A'=>'#6366f1,#8b5cf6','B'=>'#3b82f6,#6366f1','C'=>'#06b6d4,#3b82f6','D'=>'#10b981,#06b6d4','E'=>'#f59e0b,#f97316','F'=>'#ef4444,#f59e0b','G'=>'#8b5cf6,#ec4899','H'=>'#06b6d4,#10b981','I'=>'#6366f1,#3b82f6','J'=>'#f97316,#ef4444','K'=>'#10b981,#3b82f6','L'=>'#ec4899,#8b5cf6','M'=>'#3b82f6,#06b6d4','N'=>'#8b5cf6,#6366f1','O'=>'#f59e0b,#10b981','P'=>'#ef4444,#ec4899','Q'=>'#6366f1,#06b6d4','R'=>'#f97316,#f59e0b','S'=>'#10b981,#8b5cf6','T'=>'#3b82f6,#10b981','U'=>'#6366f1,#f97316','V'=>'#06b6d4,#6366f1','W'=>'#ec4899,#f59e0b','X'=>'#8b5cf6,#3b82f6','Y'=>'#f59e0b,#ec4899','Z'=>'#10b981,#6366f1'];
  $grad = $gradients[$initials] ?? '#6366f1,#8b5cf6';
  $slug = strtolower($user->role ?? '');
  $roleColor = str_contains($slug,'super') ? '#ef4444' : (str_contains($slug,'admin') ? '#6366f1' : (str_contains($slug,'editor') ? '#10b981' : '#64748b'));
  $roleBg    = str_contains($slug,'super') ? '#fef2f2' : (str_contains($slug,'admin') ? '#eef2ff' : (str_contains($slug,'editor') ? '#ecfdf5' : '#f1f5f9'));
?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit User</h1>
        <nav class="au-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('admin/users') ?>">Admin Users</a>
          <span>/</span>
          <span>Edit</span>
        </nav>
      </div>
      <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
        <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back
      </a>
    </div>

    <form method="POST" action="<?= base_url('admin/users/update/' . $user->id) ?>" id="editUserForm">
      <?= csrf_field() ?>
      <div class="row">

        <!-- Left: Form -->
        <div class="col-lg-8 mb-20">
          <div class="card-box" style="padding:0;overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border);">
              <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">User Information</h3>
              <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">Update account details for this admin user</p>
            </div>
            <div style="padding:24px;">

              <!-- Avatar preview -->
              <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;padding:16px;background:#f8fafc;border-radius:var(--radius);border:1px solid var(--border);">
                <div id="eu-avatar" style="width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,<?= $grad ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.4rem;flex-shrink:0;">
                  <?= $initials ?>
                </div>
                <div>
                  <div id="eu-name-preview" style="font-weight:700;color:var(--t1);font-size:.95rem;"><?= esc($user->fullname) ?></div>
                  <div style="font-size:.8rem;color:var(--t3);margin-top:2px;"><?= esc($user->email) ?></div>
                </div>
              </div>

              <!-- Email (read-only) -->
              <div class="cu-field-group">
                <label class="cu-label">Email Address</label>
                <div style="position:relative;">
                  <input type="email" class="cu-input" value="<?= esc($user->email) ?>" disabled style="background:#f8fafc;color:var(--t3);cursor:not-allowed;">
                  <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:.75rem;color:#94a3b8;background:#e2e8f0;padding:2px 8px;border-radius:12px;">locked</span>
                </div>
                <span class="cu-hint">Email address cannot be changed</span>
              </div>

              <!-- Full Name -->
              <div class="cu-field-group">
                <label class="cu-label" for="eu-fullname">Full Name <span style="color:#ef4444;">*</span></label>
                <input type="text" class="cu-input" id="eu-fullname" name="fullname"
                       value="<?= esc($user->fullname) ?>" placeholder="e.g. John Doe" required>
              </div>

              <!-- Password -->
              <div class="cu-field-group">
                <label class="cu-label" for="eu-password">New Password</label>
                <div style="position:relative;">
                  <input type="password" class="cu-input" id="eu-password" name="password"
                         placeholder="Leave blank to keep current password" style="padding-right:46px;">
                  <button type="button" onclick="togglePw()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--t3);padding:0;">
                    <i class="dw dw-eye" id="eu-pw-icon" style="font-size:1rem;"></i>
                  </button>
                </div>
                <!-- Strength bar -->
                <div id="eu-strength-wrap" style="margin-top:8px;display:none;">
                  <div style="height:4px;background:#e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:4px;">
                    <div id="eu-strength-bar" style="height:100%;width:0;border-radius:4px;transition:all .3s;"></div>
                  </div>
                  <span id="eu-strength-label" style="font-size:.75rem;color:var(--t3);font-weight:500;"></span>
                </div>
                <span class="cu-hint">Minimum 8 characters. Leave blank to keep the current password.</span>
              </div>

              <!-- Role -->
              <div class="cu-field-group">
                <label class="cu-label" for="eu-role">Assign Role <span style="color:#ef4444;">*</span></label>
                <select class="cu-input" id="eu-role" name="role_id" required style="appearance:auto;cursor:pointer;">
                  <option value="">-- Select a Role --</option>
                  <?php foreach ($roles as $r): ?>
                    <option value="<?= $r->id ?>" <?= $user->role_id == $r->id ? 'selected' : '' ?>
                            data-desc="<?= esc($r->description ?? '') ?>">
                      <?= esc($r->display_name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <span id="eu-role-desc" class="cu-hint" style="display:none;"></span>
              </div>

              <!-- Status toggle -->
              <div class="cu-field-group" style="display:flex;align-items:center;justify-content:space-between;background:#f8fafc;padding:14px 16px;border-radius:var(--radius);border:1px solid var(--border);">
                <div>
                  <div style="font-size:.875rem;font-weight:600;color:var(--t1);">Account Active</div>
                  <div style="font-size:.78rem;color:var(--t3);margin-top:1px;">Allow this user to log in to the system</div>
                </div>
                <label class="cu-toggle">
                  <input type="checkbox" name="status" value="1" id="eu-status" <?= $user->status == 1 ? 'checked' : '' ?>>
                  <span class="cu-toggle-track">
                    <span class="cu-toggle-thumb"></span>
                  </span>
                </label>
              </div>

              <!-- Actions -->
              <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
                <div style="display:flex;gap:10px;margin-top:24px;">
                  <button type="submit" id="eu-submit" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:10px 24px;">
                    <i class="dw dw-check-circle-2" id="eu-btn-icon" style="margin-right:6px;"></i>
                    <span id="eu-btn-text">Save Changes</span>
                  </button>
                  <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:10px 24px;">
                    Cancel
                  </a>
                </div>
              <?php else: ?>
                <div style="margin-top:24px;padding:14px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:var(--radius);font-size:.875rem;color:#92400e;display:flex;align-items:center;gap:8px;">
                  <i class="dw dw-lock" style="flex-shrink:0;"></i>
                  You don&apos;t have permission to edit users.
                </div>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="margin-top:12px;border-radius:8px;font-weight:600;">Back to Users</a>
              <?php endif; ?>

            </div>
          </div>
        </div>

        <!-- Right: Sidebar -->
        <div class="col-lg-4 mb-20">

          <!-- User summary -->
          <div class="card-box" style="padding:20px;margin-bottom:16px;">
            <h4 style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);margin:0 0 14px;">Current Account</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
              <div style="width:44px;height:44px;border-radius:11px;background:linear-gradient(135deg,<?= $grad ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0;">
                <?= $initials ?>
              </div>
              <div>
                <div style="font-weight:600;color:var(--t1);font-size:.875rem;"><?= esc($user->fullname) ?></div>
                <div style="font-size:.78rem;color:var(--t3);"><?= esc($user->email) ?></div>
              </div>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <span style="padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:<?= $roleBg ?>;color:<?= $roleColor ?>;">
                <?= esc($user->role_display_name ?? $user->role ?? 'N/A') ?>
              </span>
              <?php if ($user->status == 1): ?>
                <span style="padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:#ecfdf5;color:#059669;">Active</span>
              <?php else: ?>
                <span style="padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:#fffbeb;color:#d97706;">Inactive</span>
              <?php endif; ?>
            </div>
            <div style="font-size:.78rem;color:var(--t3);margin-top:10px;padding-top:10px;border-top:1px solid var(--border);">
              Created <?= date('M d, Y', strtotime($user->date_created)) ?>
            </div>
          </div>

          <!-- Danger zone -->
          <div class="card-box" style="padding:20px;border:1px solid #fecaca;">
            <h4 style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#ef4444;margin:0 0 10px;">Danger Zone</h4>
            <p style="font-size:.8rem;color:var(--t2);margin:0 0 14px;line-height:1.5;">Permanently delete this user account. This action cannot be undone.</p>
            <?php if ($user->email !== $session->get('userId') && (hasPermission('users.delete') || isSuperAdmin())): ?>
              <a href="<?= base_url('admin/users/delete/' . $user->id) ?>"
                 onclick="return confirm('Permanently delete <?= esc(addslashes($user->fullname)) ?>? This cannot be undone.')"
                 style="display:block;text-align:center;padding:9px;border-radius:8px;border:1px solid #fecaca;background:#fef2f2;color:#ef4444;font-size:.875rem;font-weight:600;text-decoration:none;transition:background .15s;"
                 onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="dw dw-trash" style="margin-right:5px;"></i>Delete User
              </a>
            <?php else: ?>
              <div style="text-align:center;padding:9px;border-radius:8px;border:1px solid var(--border);background:#f8fafc;color:var(--t3);font-size:.875rem;font-weight:600;">
                <i class="dw dw-lock" style="margin-right:5px;"></i>
                <?= $user->email === $session->get('userId') ? 'Cannot delete your own account' : 'No delete permission' ?>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </form>

  </div>
</div>

<style>
  .au-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .au-breadcrumb a { color:var(--t3);text-decoration:none; }
  .au-breadcrumb a:hover { color:var(--accent); }
  .au-breadcrumb span { margin:0 5px; }

  .cu-field-group { margin-bottom:20px; }
  .cu-label { display:block;font-size:.8rem;font-weight:600;color:var(--t2);margin-bottom:6px;letter-spacing:.01em; }
  .cu-input {
    display:block;width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;
    font-size:.875rem;color:var(--t1);background:#fff;outline:none;transition:border .15s,box-shadow .15s;
    font-family:inherit;
  }
  .cu-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.1); }
  .cu-hint { display:block;font-size:.76rem;color:var(--t3);margin-top:5px; }

  .cu-toggle { position:relative;display:inline-block;width:44px;height:24px;cursor:pointer; }
  .cu-toggle input { opacity:0;width:0;height:0; }
  .cu-toggle-track {
    position:absolute;inset:0;border-radius:12px;background:#e2e8f0;transition:background .2s;
  }
  .cu-toggle input:checked + .cu-toggle-track { background:var(--accent); }
  .cu-toggle-thumb {
    position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;
    box-shadow:0 1px 3px rgba(0,0,0,.2);transition:transform .2s;
  }
  .cu-toggle input:checked + .cu-toggle-track .cu-toggle-thumb { transform:translateX(20px); }
</style>

<script>
  // Name preview
  document.getElementById('eu-fullname').addEventListener('input', function() {
    var val = this.value.trim();
    document.getElementById('eu-name-preview').textContent = val || '<?= esc(addslashes($user->fullname)) ?>';
    var init = val ? val.charAt(0).toUpperCase() : '<?= $initials ?>';
    document.getElementById('eu-avatar').textContent = init;
  });

  // Role description
  document.getElementById('eu-role').addEventListener('change', function() {
    var desc = this.options[this.selectedIndex].getAttribute('data-desc');
    var el = document.getElementById('eu-role-desc');
    if (desc) { el.textContent = desc; el.style.display = 'block'; }
    else { el.style.display = 'none'; }
  });
  // Init on load
  (function() {
    var sel = document.getElementById('eu-role');
    var desc = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].getAttribute('data-desc') : '';
    var el = document.getElementById('eu-role-desc');
    if (desc) { el.textContent = desc; el.style.display = 'block'; }
  })();

  // Password strength
  document.getElementById('eu-password').addEventListener('input', function() {
    var v = this.value;
    var wrap = document.getElementById('eu-strength-wrap');
    if (!v) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';
    var bar = document.getElementById('eu-strength-bar');
    var lbl = document.getElementById('eu-strength-label');
    var levels = [
      { min:0,  w:'20%', c:'#ef4444', t:'Too short'  },
      { min:6,  w:'40%', c:'#f97316', t:'Weak'       },
      { min:8,  w:'60%', c:'#f59e0b', t:'Fair'       },
      { min:10, w:'80%', c:'#3b82f6', t:'Good'       },
      { min:12, w:'100%',c:'#10b981', t:'Strong'     },
    ];
    var lvl = levels[0];
    for (var i = levels.length-1; i >= 0; i--) { if (v.length >= levels[i].min) { lvl = levels[i]; break; } }
    bar.style.width = lvl.w; bar.style.background = lvl.c;
    lbl.textContent = lvl.t; lbl.style.color = lvl.c;
  });

  // Toggle password
  function togglePw() {
    var inp = document.getElementById('eu-password');
    var icon = document.getElementById('eu-pw-icon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'dw dw-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'dw dw-eye'; }
  }

  // Submit state
  document.getElementById('editUserForm').addEventListener('submit', function() {
    var btn = document.getElementById('eu-submit');
    btn.disabled = true;
    document.getElementById('eu-btn-icon').className = 'dw dw-loading';
    document.getElementById('eu-btn-text').textContent = 'Saving…';
  });
</script>
