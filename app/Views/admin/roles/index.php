<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Role Management</h1>
        <nav class="au-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <span>Roles</span>
        </nav>
      </div>
      <?php if (hasPermission('roles.create') || isSuperAdmin()): ?>
        <a href="<?= base_url('admin/roles/create') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
          <i class="dw dw-add" style="margin-right:6px;"></i>New Role
        </a>
      <?php endif; ?>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="au-alert au-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="au-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="au-alert au-alert-danger">
        <i class="dw dw-close-circle-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="au-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <?php if (!empty($roles)): ?>

      <!-- Role cards grid -->
      <div class="row">
        <?php
        $roleStyles = [
          'super_admin' => ['icon'=>'dw-star-1',  'grad'=>'#ef4444,#f97316', 'bg'=>'#fef2f2','color'=>'#ef4444'],
          'admin'       => ['icon'=>'dw-user1',    'grad'=>'#6366f1,#8b5cf6', 'bg'=>'#eef2ff','color'=>'#6366f1'],
          'editor'      => ['icon'=>'dw-edit-2',   'grad'=>'#10b981,#06b6d4', 'bg'=>'#ecfdf5','color'=>'#10b981'],
          'viewer'      => ['icon'=>'dw-eye',       'grad'=>'#64748b,#94a3b8', 'bg'=>'#f1f5f9','color'=>'#64748b'],
          'moderator'   => ['icon'=>'dw-shield',   'grad'=>'#f59e0b,#f97316', 'bg'=>'#fffbeb','color'=>'#d97706'],
        ];
        foreach ($roles as $role):
          $key = strtolower($role->name);
          $rs  = $roleStyles[$key] ?? ['icon'=>'dw-lock','grad'=>'#6366f1,#8b5cf6','bg'=>'#eef2ff','color'=>'#6366f1'];
          $permCount = $role->permission_count ?? 0;
          $userCount = $role->user_count ?? 0;
        ?>
        <div class="col-xl-4 col-lg-6 col-md-6 mb-20">
          <div class="ri-role-card">
            <div class="ri-role-card-top">
              <div class="ri-role-icon" style="background:linear-gradient(135deg,<?= $rs['grad'] ?>);">
                <i class="dw <?= $rs['icon'] ?>"></i>
              </div>
              <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                  <h3 class="ri-role-name"><?= esc($role->display_name) ?></h3>
                  <?php if (strtolower($role->name) === 'super_admin'): ?>
                    <span style="font-size:.68rem;font-weight:700;padding:2px 7px;border-radius:10px;background:#fef2f2;color:#ef4444;letter-spacing:.04em;">SYSTEM</span>
                  <?php endif; ?>
                </div>
                <code class="ri-role-slug"><?= esc($role->name) ?></code>
              </div>
              <!-- action buttons -->
              <div style="display:flex;gap:5px;flex-shrink:0;margin-left:8px;">
                <a href="<?= base_url('admin/roles/view/' . $role->id) ?>" class="au-action-btn au-action-view" title="View Permissions">
                  <i class="dw dw-eye"></i>
                </a>
                <?php if (hasPermission('roles.edit') || isSuperAdmin()): ?>
                  <a href="<?= base_url('admin/roles/edit/' . $role->id) ?>" class="au-action-btn au-action-edit" title="Edit">
                    <i class="dw dw-edit-2"></i>
                  </a>
                <?php endif; ?>
                <?php if ((hasPermission('roles.delete') || isSuperAdmin()) && strtolower($role->name) !== 'super_admin'): ?>
                  <a href="<?= base_url('admin/roles/delete/' . $role->id) ?>" class="au-action-btn au-action-delete" title="Delete"
                     onclick="return confirm('Delete role &quot;<?= esc(addslashes($role->display_name)) ?>&quot;? This cannot be undone.')">
                    <i class="dw dw-trash"></i>
                  </a>
                <?php endif; ?>
              </div>
            </div>

            <?php if (!empty($role->description)): ?>
              <p class="ri-role-desc"><?= esc(strlen($role->description) > 90 ? substr($role->description, 0, 90) . '…' : $role->description) ?></p>
            <?php endif; ?>

            <div class="ri-role-stats">
              <div class="ri-stat">
                <span class="ri-stat-val" style="color:<?= $rs['color'] ?>;"><?= $permCount ?></span>
                <span class="ri-stat-label">Permission<?= $permCount !== 1 ? 's' : '' ?></span>
              </div>
              <div style="width:1px;background:var(--border);"></div>
              <div class="ri-stat">
                <span class="ri-stat-val"><?= $userCount ?></span>
                <span class="ri-stat-label">User<?= $userCount !== 1 ? 's' : '' ?></span>
              </div>
              <a href="<?= base_url('admin/roles/view/' . $role->id) ?>" class="ri-view-link" style="color:<?= $rs['color'] ?>;">
                View details <i class="dw dw-next-button" style="font-size:.6rem;vertical-align:middle;"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>
      <!-- Empty state -->
      <div class="card-box" style="text-align:center;padding:64px 20px;">
        <div style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
          <i class="dw dw-shield" style="font-size:2rem;color:#6366f1;"></i>
        </div>
        <h4 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0 0 6px;">No roles defined</h4>
        <p style="font-size:.875rem;color:var(--t3);margin:0 0 20px;">Create your first role to start assigning permissions.</p>
        <?php if (hasPermission('roles.create') || isSuperAdmin()): ?>
          <a href="<?= base_url('admin/roles/create') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;">
            <i class="dw dw-add" style="margin-right:6px;"></i>Create First Role
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<style>
  .au-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .au-breadcrumb a { color:var(--t3);text-decoration:none; }
  .au-breadcrumb a:hover { color:var(--accent); }
  .au-breadcrumb span { margin:0 5px; }

  .au-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .au-alert i { font-size:1.1rem;flex-shrink:0; }
  .au-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .au-alert-danger  { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
  .au-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;line-height:1;padding:0; }
  .au-alert-close:hover { opacity:1; }

  .au-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;flex-shrink:0;
  }
  .au-action-view   { background:#eef2ff;color:#6366f1; }
  .au-action-view:hover   { background:#6366f1;color:#fff; }
  .au-action-edit   { background:#fffbeb;color:#d97706; }
  .au-action-edit:hover   { background:#f59e0b;color:#fff; }
  .au-action-delete { background:#fef2f2;color:#ef4444; }
  .au-action-delete:hover { background:#ef4444;color:#fff; }

  .ri-role-card {
    background:var(--card-bg);border:1px solid var(--border);border-radius:var(--radius);
    box-shadow:var(--shadow-sm);padding:20px;transition:box-shadow .2s,transform .2s;height:100%;
    display:flex;flex-direction:column;gap:12px;
  }
  .ri-role-card:hover { box-shadow:var(--shadow-md);transform:translateY(-2px); }

  .ri-role-card-top { display:flex;align-items:flex-start;gap:12px; }
  .ri-role-icon {
    width:44px;height:44px;border-radius:11px;display:flex;align-items:center;
    justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0;
  }
  .ri-role-name { font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;line-height:1.2; }
  .ri-role-slug { font-size:.72rem;color:var(--t3);background:#f1f5f9;padding:2px 6px;border-radius:5px;font-family:monospace; }

  .ri-role-desc { font-size:.8rem;color:var(--t2);margin:0;line-height:1.5; }

  .ri-role-stats {
    display:flex;align-items:center;gap:14px;padding-top:12px;border-top:1px solid var(--border);
    margin-top:auto;
  }
  .ri-stat { display:flex;align-items:baseline;gap:5px; }
  .ri-stat-val { font-size:1.1rem;font-weight:800;color:var(--t1); }
  .ri-stat-label { font-size:.75rem;color:var(--t3); }
  .ri-view-link { margin-left:auto;font-size:.78rem;font-weight:600;text-decoration:none; }
  .ri-view-link:hover { text-decoration:underline; }
</style>
