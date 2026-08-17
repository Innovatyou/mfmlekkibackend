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
        <h1 class="page-title">User Profile</h1>
        <nav class="au-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('admin/users') ?>">Admin Users</a>
          <span>/</span>
          <span><?= esc($user->fullname) ?></span>
        </nav>
      </div>
      <div style="display:flex;gap:8px;align-items:center;">
        <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
          <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
            <i class="dw dw-edit-2" style="margin-right:6px;"></i>Edit User
          </a>
        <?php endif; ?>
        <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
          <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back
        </a>
      </div>
    </div>

    <div class="row">

      <!-- Left: Profile card -->
      <div class="col-lg-4 mb-20">

        <!-- Profile hero -->
        <div class="card-box" style="text-align:center;padding:32px 24px;">
          <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,<?= $grad ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:2rem;margin:0 auto 16px;">
            <?= $initials ?>
          </div>
          <h3 style="font-size:1.1rem;font-weight:700;color:var(--t1);margin:0 0 4px;"><?= esc($user->fullname) ?></h3>
          <p style="font-size:.875rem;color:var(--t3);margin:0 0 14px;"><?= esc($user->email) ?></p>
          <div style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap;">
            <span style="padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;background:<?= $roleBg ?>;color:<?= $roleColor ?>;">
              <?= esc($user->role_display_name ?? $user->role ?? 'N/A') ?>
            </span>
            <?php if ($user->status == 1): ?>
              <span style="padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;background:#ecfdf5;color:#059669;">
                <span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;margin-right:4px;vertical-align:middle;"></span>Active
              </span>
            <?php else: ?>
              <span style="padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;background:#fffbeb;color:#d97706;">
                <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b;display:inline-block;margin-right:4px;vertical-align:middle;"></span>Inactive
              </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Info list -->
        <div class="card-box" style="margin-top:16px;padding:20px;">
          <h4 style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);margin:0 0 14px;">Account Details</h4>
          <div class="uv-detail-row">
            <span class="uv-detail-label">Email</span>
            <span class="uv-detail-val"><?= esc($user->email) ?></span>
          </div>
          <div class="uv-detail-row">
            <span class="uv-detail-label">Full Name</span>
            <span class="uv-detail-val"><?= esc($user->fullname) ?></span>
          </div>
          <div class="uv-detail-row">
            <span class="uv-detail-label">Role</span>
            <span class="uv-detail-val" style="color:<?= $roleColor ?>;font-weight:600;"><?= esc($user->role_display_name ?? $user->role ?? 'N/A') ?></span>
          </div>
          <div class="uv-detail-row">
            <span class="uv-detail-label">Status</span>
            <span class="uv-detail-val"><?= $user->status == 1 ? 'Active' : 'Inactive' ?></span>
          </div>
          <div class="uv-detail-row" style="border:none;padding-bottom:0;">
            <span class="uv-detail-label">Created</span>
            <span class="uv-detail-val"><?= date('M d, Y', strtotime($user->date_created)) ?></span>
          </div>
        </div>
      </div>

      <!-- Right: Permissions -->
      <div class="col-lg-8 mb-20">
        <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
          <div style="padding:18px 22px;border-bottom:1px solid var(--border);">
            <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">Assigned Permissions</h3>
            <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;">
              <?php
                $total = 0;
                if (!empty($user->permissions)) foreach ($user->permissions as $p) $total++;
              ?>
              <?= $total ?> permission<?= $total !== 1 ? 's' : '' ?> granted via role
            </p>
          </div>
          <div style="padding:20px;">
            <?php if (!empty($user->permissions)):
              $permsByModule = [];
              foreach ($user->permissions as $perm) {
                $permsByModule[$perm->module][] = $perm;
              }
              ?>
              <div class="row">
                <?php foreach ($permsByModule as $module => $perms): ?>
                  <div class="col-md-6 mb-20">
                    <div class="uv-module-card">
                      <div class="uv-module-header">
                        <i class="dw dw-folder" style="color:var(--accent);"></i>
                        <span><?= esc(ucfirst($module)) ?></span>
                      </div>
                      <div class="uv-module-perms">
                        <?php foreach ($perms as $perm): ?>
                          <div class="uv-perm-item">
                            <i class="dw dw-check-circle-2" style="color:#10b981;font-size:.8rem;flex-shrink:0;"></i>
                            <span><?= esc($perm->display_name) ?></span>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div style="text-align:center;padding:40px;">
                <i class="dw dw-lock" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:10px;"></i>
                <p style="color:var(--t3);font-size:.875rem;margin:0;">No permissions assigned to this user</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
  .au-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .au-breadcrumb a { color:var(--t3);text-decoration:none; }
  .au-breadcrumb a:hover { color:var(--accent); }
  .au-breadcrumb span { margin:0 5px; }

  .uv-detail-row {
    display:flex;justify-content:space-between;align-items:center;
    padding:9px 0;border-bottom:1px solid var(--border);font-size:.875rem;
  }
  .uv-detail-label { color:var(--t3);font-weight:500; }
  .uv-detail-val   { color:var(--t1);font-weight:600;text-align:right;max-width:60%; }

  .uv-module-card {
    border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;height:100%;
  }
  .uv-module-header {
    display:flex;align-items:center;gap:7px;padding:10px 14px;background:#f8fafc;
    border-bottom:1px solid var(--border);font-size:.82rem;font-weight:700;
    text-transform:uppercase;letter-spacing:.05em;color:var(--t2);
  }
  .uv-module-perms { padding:10px 14px;display:flex;flex-direction:column;gap:6px; }
  .uv-perm-item {
    display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t2);
  }
</style>
