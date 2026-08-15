<?php helper('AdminAuth'); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- ── Flash messages ── -->
    <?= view('_flash') ?>
    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <?php foreach ((array)session()->getFlashdata('errors') as $e): ?>
          <div><?= esc($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- ── Page header ── -->
    <div class="page-header">
      <div>
        <nav style="font-size:.8rem;color:var(--t3);margin-bottom:4px;">
          <a href="<?= base_url('/') ?>" style="color:var(--t3);text-decoration:none;">Dashboard</a>
          <span style="margin:0 6px;">/</span>
          <a href="<?= base_url('admin/users') ?>" style="color:var(--t3);text-decoration:none;">Admin Users</a>
          <span style="margin:0 6px;">/</span>
          <span style="color:var(--t1);font-weight:500;">Create</span>
        </nav>
        <h1 class="page-title">Create Admin User</h1>
        <p class="page-subtitle">Add a new administrator account to the system</p>
      </div>
      <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="display:flex;align-items:center;gap:6px;border-radius:9px;font-weight:600;">
        <i class="dw dw-left-arrow" style="font-size:.85rem;"></i> Back to Users
      </a>
    </div>

    <div class="row">

      <!-- ══ LEFT: Form ══ -->
      <div class="col-lg-7 col-xl-8">
        <div class="card-box" style="padding:0;overflow:hidden;">

          <!-- Card header -->
          <div style="padding:20px 24px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;">
            <!-- Live avatar preview -->
            <div id="avatarPreview" style="
              width:52px;height:52px;border-radius:14px;flex-shrink:0;
              background:linear-gradient(135deg,#6366f1,#8b5cf6);
              display:flex;align-items:center;justify-content:center;
              font-size:1.3rem;font-weight:800;color:#fff;letter-spacing:-.01em;
              transition:background .3s;
            ">?</div>
            <div>
              <div style="font-weight:700;font-size:1rem;color:var(--t1);" id="namePreview">New Administrator</div>
              <div style="font-size:.8rem;color:var(--t3);" id="emailPreview">No email set</div>
            </div>
          </div>

          <!-- Form body -->
          <form method="POST" action="<?= base_url('admin/users/store') ?>" id="createUserForm" novalidate style="padding:24px;">
            <?= csrf_field() ?>

            <div class="cu-grid">

              <!-- Full name -->
              <div class="cu-field">
                <label class="cu-label" for="fullname">
                  Full Name <span class="cu-required">*</span>
                </label>
                <div class="cu-input-wrap">
                  <i class="dw dw-user1 cu-input-icon"></i>
                  <input type="text" id="fullname" name="fullname" placeholder="e.g. John Adeyemi"
                    class="cu-input" value="<?= old('fullname') ?>" autocomplete="name" required>
                </div>
                <div class="cu-error" id="fullname-err"></div>
              </div>

              <!-- Email -->
              <div class="cu-field">
                <label class="cu-label" for="email">
                  Email Address <span class="cu-required">*</span>
                </label>
                <div class="cu-input-wrap">
                  <i class="dw dw-email cu-input-icon"></i>
                  <input type="email" id="email" name="email" placeholder="user@example.com"
                    class="cu-input" value="<?= old('email') ?>" autocomplete="email" required>
                  <span class="cu-input-badge" id="email-badge"></span>
                </div>
                <div class="cu-error" id="email-err"></div>
              </div>

              <!-- Password -->
              <div class="cu-field cu-field--full">
                <label class="cu-label" for="password">
                  Password <span class="cu-required">*</span>
                </label>
                <div class="cu-input-wrap">
                  <i class="dw dw-padlock1 cu-input-icon"></i>
                  <input type="password" id="password" name="password" placeholder="Create a strong password"
                    class="cu-input" autocomplete="new-password" required>
                  <button type="button" id="togglePwd" class="cu-eye-btn" title="Show / hide">
                    <i class="dw dw-eye" id="eyeIcon"></i>
                  </button>
                </div>
                <!-- Strength bar -->
                <div class="cu-strength-wrap" id="strengthWrap" style="display:none;">
                  <div class="cu-strength-bar">
                    <div class="cu-strength-fill" id="strengthFill"></div>
                  </div>
                  <span class="cu-strength-label" id="strengthLabel"></span>
                </div>
                <div class="cu-hint">Minimum 6 characters — use letters, numbers & symbols for best security</div>
              </div>

              <!-- Role -->
              <div class="cu-field cu-field--full">
                <label class="cu-label" for="role_id">
                  Assign Role <span class="cu-required">*</span>
                </label>
                <div class="cu-input-wrap">
                  <i class="dw dw-folder cu-input-icon"></i>
                  <select id="role_id" name="role_id" class="cu-input cu-select" required>
                    <option value="">— Select a role —</option>
                    <?php foreach ($roles as $role): ?>
                      <option value="<?= $role->id ?>"
                        data-desc="<?= esc($role->description ?? '') ?>"
                        <?= old('role_id') == $role->id ? 'selected' : '' ?>>
                        <?= esc($role->display_name) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Role description hint -->
                <div class="cu-role-desc" id="roleDesc" style="display:none;"></div>
                <div class="cu-error" id="role-err"></div>
              </div>

            </div><!-- /cu-grid -->

            <!-- Activate toggle -->
            <div class="cu-toggle-row">
              <div>
                <div style="font-weight:600;font-size:.875rem;color:var(--t1);">Activate immediately</div>
                <div style="font-size:.8rem;color:var(--t3);margin-top:2px;">User can log in as soon as the account is created</div>
              </div>
              <label class="cu-toggle">
                <input type="checkbox" name="status" value="1" id="statusToggle" <?= old('status') ? 'checked' : '' ?>>
                <span class="cu-toggle-track">
                  <span class="cu-toggle-thumb"></span>
                </span>
              </label>
            </div>

            <!-- Actions -->
            <?php if (hasPermission('users.create') || isSuperAdmin()): ?>
            <div class="cu-actions">
              <button type="submit" class="btn btn-primary cu-submit" id="submitBtn" style="padding:10px 28px;font-weight:700;font-size:.9rem;">
                <span class="cu-submit-text">
                  <i class="dw dw-check" style="margin-right:6px;"></i>Create Admin User
                </span>
                <span class="cu-submit-spinner" style="display:none;">
                  <span class="cu-spinner"></span> Creating…
                </span>
              </button>
              <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="padding:10px 20px;">
                Cancel
              </a>
            </div>
            <?php else: ?>
            <div class="alert alert-warning" style="margin-top:20px;">
              <i class="dw dw-padlock1"></i> You don't have permission to create users.
            </div>
            <?php endif; ?>

          </form>
        </div>
      </div><!-- /col -->

      <!-- ══ RIGHT: Info panel ══ -->
      <div class="col-lg-5 col-xl-4">

        <!-- Role guide -->
        <div class="card-box" style="padding:0;overflow:hidden;margin-bottom:20px;">
          <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
            <div style="font-weight:700;font-size:.9rem;color:var(--t1);">Available Roles</div>
            <div style="font-size:.78rem;color:var(--t3);margin-top:2px;">Click a role to select it</div>
          </div>
          <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;">
            <?php
            $roleColors = [
              'super_admin' => ['#7c3aed','#f5f3ff','#6d28d9'],
              'admin'       => ['#1d4ed8','#eff6ff','#1e40af'],
              'manager'     => ['#0891b2','#ecfeff','#0e7490'],
              'editor'      => ['#059669','#ecfdf5','#047857'],
              'viewer'      => ['#d97706','#fffbeb','#b45309'],
            ];
            foreach ($roles as $role):
              $slug  = $role->name;
              $color = $roleColors[$slug] ?? ['#475569','#f1f5f9','#334155'];
            ?>
            <button type="button" class="cu-role-card" data-role-id="<?= $role->id ?>"
              style="--rc:<?= $color[0] ?>;--rb:<?= $color[1] ?>;--rd:<?= $color[2] ?>;">
              <span class="cu-role-badge" style="background:<?= $color[1] ?>;color:<?= $color[0] ?>;">
                <?= strtoupper(substr($role->name,0,2)) ?>
              </span>
              <div class="cu-role-info">
                <div class="cu-role-name"><?= esc($role->display_name) ?></div>
                <div class="cu-role-hint"><?= esc($role->description ?? '') ?></div>
              </div>
              <i class="dw dw-check cu-role-check"></i>
            </button>
            <?php endforeach; ?>
          </div>
          <div style="padding:12px 16px;border-top:1px solid var(--border);">
            <a href="<?= base_url('admin/roles') ?>" style="font-size:.8rem;color:var(--accent);font-weight:500;text-decoration:none;display:flex;align-items:center;gap:4px;">
              <i class="dw dw-settings" style="font-size:.8rem;"></i> Manage roles &amp; permissions
            </a>
          </div>
        </div>

        <!-- Tips -->
        <div class="card-box" style="padding:20px;">
          <div style="font-weight:700;font-size:.875rem;color:var(--t1);margin-bottom:14px;display:flex;align-items:center;gap:7px;">
            <span style="width:22px;height:22px;border-radius:6px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:.7rem;">
              <i class="dw dw-information"></i>
            </span>
            Quick Tips
          </div>
          <div style="display:flex;flex-direction:column;gap:10px;">
            <?php foreach([
              ['dw-email',   '#6366f1', 'Use an email the user can access — it becomes their login ID.'],
              ['dw-padlock1','#10b981', 'Share the password securely. It cannot be retrieved later, only reset.'],
              ['dw-folder',  '#f59e0b', 'Assign the least-privileged role that meets the user\'s needs.'],
              ['dw-user1',   '#8b5cf6', 'Activate immediately only when the user is ready to start.'],
            ] as [$icon, $clr, $tip]): ?>
            <div style="display:flex;gap:10px;align-items:flex-start;">
              <span style="width:28px;height:28px;border-radius:8px;background:<?= $clr ?>18;color:<?= $clr ?>;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;">
                <i class="dw <?= $icon ?>"></i>
              </span>
              <span style="font-size:.82rem;color:var(--t2);line-height:1.5;"><?= $tip ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div><!-- /col -->
    </div><!-- /row -->
  </div>
</div>

<!-- ═══════════════════════════════════════
     PAGE STYLES
═══════════════════════════════════════ -->
<style>
  /* Grid */
  .cu-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px 20px; }
  .cu-field--full { grid-column: 1 / -1; }

  /* Field wrapper */
  .cu-field { display:flex; flex-direction:column; gap:5px; }
  .cu-label { font-size:.82rem; font-weight:600; color:var(--t2); }
  .cu-required { color:#ef4444; }
  .cu-hint { font-size:.77rem; color:var(--t3); margin-top:3px; }

  /* Input row */
  .cu-input-wrap { position:relative; display:flex; align-items:center; }
  .cu-input-icon {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    font-size:1rem; color:var(--t3); pointer-events:none; transition:color .15s;
    z-index:1;
  }
  .cu-input {
    width:100%; height:44px;
    padding:0 42px 0 40px;
    border:1.5px solid var(--border);
    border-radius:9px;
    font-size:.875rem; font-family:'Inter',sans-serif; color:var(--t1);
    background:#f8fafc;
    outline:none;
    transition:border-color .15s, background .15s, box-shadow .15s;
    appearance:none;
  }
  .cu-input:focus {
    border-color:var(--accent);
    background:#fff;
    box-shadow:0 0 0 3px rgba(99,102,241,.12);
  }
  .cu-input:focus ~ .cu-input-icon,
  .cu-input-wrap:focus-within .cu-input-icon { color:var(--accent); }
  .cu-input.is-valid   { border-color:#10b981 !important; }
  .cu-input.is-invalid { border-color:#ef4444 !important; }

  /* Select */
  .cu-select { cursor:pointer; }

  /* Eye button */
  .cu-eye-btn {
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    background:none; border:none; padding:4px; cursor:pointer;
    color:var(--t3); font-size:1rem; transition:color .15s; z-index:1;
  }
  .cu-eye-btn:hover { color:var(--accent); }

  /* Email valid badge */
  .cu-input-badge {
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    font-size:.7rem; font-weight:700; border-radius:5px;
    padding:2px 7px; display:none;
  }
  .cu-input-badge.valid   { background:#d1fae5; color:#065f46; display:inline; }
  .cu-input-badge.invalid { background:#fee2e2; color:#7f1d1d; display:inline; }

  /* Password strength */
  .cu-strength-wrap { display:flex; align-items:center; gap:10px; margin-top:6px; }
  .cu-strength-bar  { flex:1; height:4px; background:#e2e8f0; border-radius:4px; overflow:hidden; }
  .cu-strength-fill { height:100%; border-radius:4px; transition:width .3s, background .3s; width:0%; }
  .cu-strength-label{ font-size:.75rem; font-weight:700; min-width:60px; }

  /* Error */
  .cu-error { font-size:.77rem; color:#ef4444; font-weight:500; min-height:16px; }

  /* Role description */
  .cu-role-desc {
    margin-top:6px; padding:8px 12px;
    background:#f1f5f9; border-radius:8px;
    font-size:.8rem; color:var(--t2); line-height:1.5;
    border-left:3px solid var(--accent);
  }

  /* Toggle */
  .cu-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 0; border-top:1px solid var(--border); border-bottom:1px solid var(--border);
    margin:20px 0;
  }
  .cu-toggle { position:relative; cursor:pointer; }
  .cu-toggle input { position:absolute; opacity:0; width:0; height:0; }
  .cu-toggle-track {
    display:block; width:44px; height:24px;
    background:#e2e8f0; border-radius:12px;
    transition:background .2s;
    position:relative;
  }
  .cu-toggle input:checked + .cu-toggle-track { background:var(--accent); }
  .cu-toggle-thumb {
    position:absolute; top:2px; left:2px;
    width:20px; height:20px; border-radius:50%;
    background:#fff;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
    transition:transform .2s;
  }
  .cu-toggle input:checked + .cu-toggle-track .cu-toggle-thumb { transform:translateX(20px); }

  /* Actions */
  .cu-actions { display:flex; gap:10px; flex-wrap:wrap; }

  /* Submit spinner */
  .cu-spinner {
    display:inline-block; width:14px; height:14px;
    border:2px solid rgba(255,255,255,.4); border-top-color:#fff;
    border-radius:50%; animation:spin .7s linear infinite; vertical-align:middle;
    margin-right:4px;
  }
  @keyframes spin { to{transform:rotate(360deg)} }

  /* Role cards (right panel) */
  .cu-role-card {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:9px;
    border:1.5px solid var(--border); background:var(--card-bg);
    cursor:pointer; text-align:left; transition:all .15s; width:100%;
  }
  .cu-role-card:hover { border-color:var(--rc); background:var(--rb); }
  .cu-role-card.selected { border-color:var(--rc); background:var(--rb); }
  .cu-role-badge {
    width:34px; height:34px; border-radius:8px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:.65rem; font-weight:800; letter-spacing:.02em;
  }
  .cu-role-info { flex:1; min-width:0; }
  .cu-role-name { font-size:.82rem; font-weight:600; color:var(--t1); }
  .cu-role-hint { font-size:.75rem; color:var(--t3); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .cu-role-check { color:var(--rc); font-size:.9rem; opacity:0; transition:opacity .15s; }
  .cu-role-card.selected .cu-role-check { opacity:1; }

  /* Responsive */
  @media (max-width:576px) {
    .cu-grid { grid-template-columns:1fr; }
  }
</style>

<!-- ═══════════════════════════════════════
     PAGE SCRIPTS
═══════════════════════════════════════ -->
<script>
(function () {
  /* ── Avatar preview ── */
  const avatarEl   = document.getElementById('avatarPreview');
  const namePreEl  = document.getElementById('namePreview');
  const emailPreEl = document.getElementById('emailPreview');
  const nameInput  = document.getElementById('fullname');
  const emailInput = document.getElementById('email');

  const gradients = [
    ['#6366f1','#8b5cf6'],['#0ea5e9','#0891b2'],
    ['#10b981','#059669'],['#f59e0b','#d97706'],
    ['#ef4444','#dc2626'],['#ec4899','#db2777'],
  ];
  function getGradient(name) {
    const i = (name.charCodeAt(0) || 65) % gradients.length;
    return `linear-gradient(135deg,${gradients[i][0]},${gradients[i][1]})`;
  }
  function initials(name) {
    const parts = name.trim().split(/\s+/);
    return parts.length >= 2 ? (parts[0][0] + parts[parts.length-1][0]).toUpperCase() : (name[0] || '?').toUpperCase();
  }

  nameInput.addEventListener('input', function () {
    const val = this.value.trim();
    avatarEl.textContent  = val ? initials(val) : '?';
    avatarEl.style.background = val ? getGradient(val) : 'linear-gradient(135deg,#6366f1,#8b5cf6)';
    namePreEl.textContent = val || 'New Administrator';
  });
  emailInput.addEventListener('input', function () {
    emailPreEl.textContent = this.value.trim() || 'No email set';
  });

  /* ── Password toggle ── */
  document.getElementById('togglePwd').addEventListener('click', function () {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') { pwd.type = 'text';  icon.className = 'dw dw-eye-close'; }
    else                         { pwd.type = 'password'; icon.className = 'dw dw-eye'; }
  });

  /* ── Password strength ── */
  const strengthConfig = [
    { min:0,  max:1,  label:'Too short', color:'#ef4444', pct:'15%'  },
    { min:1,  max:2,  label:'Weak',      color:'#f97316', pct:'30%'  },
    { min:2,  max:3,  label:'Fair',      color:'#f59e0b', pct:'55%'  },
    { min:3,  max:4,  label:'Good',      color:'#3b82f6', pct:'75%'  },
    { min:4,  max:99, label:'Strong',    color:'#10b981', pct:'100%' },
  ];
  function scorePassword(p) {
    let s = 0;
    if (p.length >= 6)  s++;
    if (p.length >= 10) s++;
    if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
    if (/\d/.test(p))   s++;
    if (/[^A-Za-z0-9]/.test(p)) s++;
    return s;
  }
  document.getElementById('password').addEventListener('input', function () {
    const wrap  = document.getElementById('strengthWrap');
    const fill  = document.getElementById('strengthFill');
    const lbl   = document.getElementById('strengthLabel');
    if (!this.value) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';
    const score = scorePassword(this.value);
    const cfg   = strengthConfig.find(c => score >= c.min && score < c.max) || strengthConfig[4];
    fill.style.width      = cfg.pct;
    fill.style.background = cfg.color;
    lbl.textContent       = cfg.label;
    lbl.style.color       = cfg.color;
  });

  /* ── Email validation badge ── */
  emailInput.addEventListener('input', function () {
    const badge = document.getElementById('email-badge');
    const ok    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
    badge.className = this.value ? (ok ? 'cu-input-badge valid' : 'cu-input-badge invalid') : 'cu-input-badge';
    badge.textContent = this.value ? (ok ? '✓ Valid' : '✗ Invalid') : '';
    this.classList.toggle('is-valid',   !!this.value && ok);
    this.classList.toggle('is-invalid', !!this.value && !ok);
  });

  /* ── Role cards → select sync ── */
  const roleSelect = document.getElementById('role_id');
  const roleDesc   = document.getElementById('roleDesc');
  document.querySelectorAll('.cu-role-card').forEach(function (card) {
    card.addEventListener('click', function () {
      const id   = this.dataset.roleId;
      roleSelect.value = id;
      document.querySelectorAll('.cu-role-card').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      // show desc from select option
      const opt  = roleSelect.querySelector(`option[value="${id}"]`);
      const desc = opt ? opt.dataset.desc : '';
      if (desc) {
        roleDesc.textContent    = desc;
        roleDesc.style.display  = 'block';
      } else {
        roleDesc.style.display  = 'none';
      }
    });
  });
  // Sync role card when select changes
  roleSelect.addEventListener('change', function () {
    document.querySelectorAll('.cu-role-card').forEach(c => c.classList.remove('selected'));
    if (this.value) {
      const card = document.querySelector(`.cu-role-card[data-role-id="${this.value}"]`);
      if (card) card.classList.add('selected');
      const opt  = this.querySelector(`option[value="${this.value}"]`);
      const desc = opt ? opt.dataset.desc : '';
      roleDesc.textContent   = desc;
      roleDesc.style.display = desc ? 'block' : 'none';
    } else {
      roleDesc.style.display = 'none';
    }
  });

  /* ── Pre-select role if old() value present ── */
  if (roleSelect.value) roleSelect.dispatchEvent(new Event('change'));

  /* ── Submit loading state ── */
  document.getElementById('createUserForm').addEventListener('submit', function (e) {
    // Basic front-end guard
    let ok = true;
    if (!nameInput.value.trim())  { document.getElementById('fullname-err').textContent = 'Full name is required.'; ok = false; }
    else                           { document.getElementById('fullname-err').textContent = ''; }
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
    if (!emailOk)                  { document.getElementById('email-err').textContent = 'A valid email address is required.'; ok = false; }
    else                           { document.getElementById('email-err').textContent = ''; }
    if (!roleSelect.value)         { document.getElementById('role-err').textContent = 'Please select a role.'; ok = false; }
    else                           { document.getElementById('role-err').textContent = ''; }

    if (!ok) { e.preventDefault(); return; }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.querySelector('.cu-submit-text').style.display   = 'none';
    btn.querySelector('.cu-submit-spinner').style.display = 'inline-flex';
  });
})();
</script>
