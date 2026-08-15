<?php $session = session(); ?>
<?php
  $slug = strtolower($role->name ?? '');
  $roleStyles = [
    'super_admin' => ['icon'=>'dw-star-1',  'grad'=>'#ef4444,#f97316', 'bg'=>'#fef2f2','color'=>'#ef4444'],
    'admin'       => ['icon'=>'dw-user1',    'grad'=>'#6366f1,#8b5cf6', 'bg'=>'#eef2ff','color'=>'#6366f1'],
    'editor'      => ['icon'=>'dw-edit-2',   'grad'=>'#10b981,#06b6d4', 'bg'=>'#ecfdf5','color'=>'#10b981'],
    'viewer'      => ['icon'=>'dw-eye',       'grad'=>'#64748b,#94a3b8', 'bg'=>'#f1f5f9','color'=>'#64748b'],
    'moderator'   => ['icon'=>'dw-shield',   'grad'=>'#f59e0b,#f97316', 'bg'=>'#fffbeb','color'=>'#d97706'],
  ];
  $rs = $roleStyles[$slug] ?? ['icon'=>'dw-lock','grad'=>'#6366f1,#8b5cf6','bg'=>'#eef2ff','color'=>'#6366f1'];
  $permCount = !empty($permissions) ? count($permissions) : 0;
  $userCount = !empty($users) ? count($users) : 0;
?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Role Details</h1>
        <nav class="au-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <a href="<?= base_url('admin/roles') ?>">Roles</a>
          <span>/</span>
          <span><?= esc($role->display_name) ?></span>
        </nav>
      </div>
      <div style="display:flex;gap:8px;align-items:center;">
        <?php if (hasPermission('roles.edit') || isSuperAdmin()): ?>
          <a href="<?= base_url('admin/roles/edit/' . $role->id) ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
            <i class="dw dw-edit-2" style="margin-right:6px;"></i>Edit Role
          </a>
        <?php endif; ?>
        <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
          <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back
        </a>
      </div>
    </div>

    <div class="row">

      <!-- Left: Role info + users -->
      <div class="col-lg-4 mb-20">

        <!-- Role hero card -->
        <div class="card-box" style="padding:24px;margin-bottom:16px;">
          <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
            <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,<?= $rs['grad'] ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;flex-shrink:0;">
              <i class="dw <?= $rs['icon'] ?>"></i>
            </div>
            <div>
              <h3 style="font-size:1.05rem;font-weight:700;color:var(--t1);margin:0 0 2px;"><?= esc($role->display_name) ?></h3>
              <code style="font-size:.72rem;color:var(--t3);background:#f1f5f9;padding:2px 8px;border-radius:5px;"><?= esc($role->name) ?></code>
            </div>
          </div>

          <?php if (!empty($role->description)): ?>
            <p style="font-size:.8rem;color:var(--t2);line-height:1.6;margin:0 0 16px;padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid var(--border);">
              <?= esc($role->description) ?>
            </p>
          <?php endif; ?>

          <!-- Stats row -->
          <div style="display:flex;gap:0;border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <div style="flex:1;text-align:center;padding:12px 8px;border-right:1px solid var(--border);">
              <div style="font-size:1.4rem;font-weight:800;color:<?= $rs['color'] ?>;"><?= $permCount ?></div>
              <div style="font-size:.72rem;color:var(--t3);font-weight:500;">Permission<?= $permCount !== 1 ? 's' : '' ?></div>
            </div>
            <div style="flex:1;text-align:center;padding:12px 8px;">
              <div style="font-size:1.4rem;font-weight:800;color:var(--t1);"><?= $userCount ?></div>
              <div style="font-size:.72rem;color:var(--t3);font-weight:500;">User<?= $userCount !== 1 ? 's' : '' ?></div>
            </div>
          </div>

          <!-- Meta -->
          <div style="margin-top:14px;font-size:.78rem;color:var(--t3);border-top:1px solid var(--border);padding-top:12px;display:flex;flex-direction:column;gap:5px;">
            <div>Created: <?= date('M d, Y', strtotime($role->created_at)) ?></div>
            <div>Updated: <?= date('M d, Y', strtotime($role->updated_at)) ?></div>
          </div>
        </div>

        <!-- Users with this role -->
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div style="padding:14px 18px;border-bottom:1px solid var(--border);">
            <h4 style="font-size:.875rem;font-weight:700;color:var(--t1);margin:0;">Users with this Role</h4>
          </div>
          <?php if (!empty($users)): ?>
            <div style="padding:10px;">
              <?php foreach ($users as $u):
                $ui = strtoupper(substr($u->fullname ?? 'U', 0, 1));
                $ug = ['A'=>'#6366f1,#8b5cf6','B'=>'#3b82f6,#6366f1','C'=>'#06b6d4,#3b82f6','D'=>'#10b981,#06b6d4','E'=>'#f59e0b,#f97316','F'=>'#ef4444,#f59e0b','G'=>'#8b5cf6,#ec4899','H'=>'#06b6d4,#10b981','I'=>'#6366f1,#3b82f6','J'=>'#f97316,#ef4444','K'=>'#10b981,#3b82f6','L'=>'#ec4899,#8b5cf6','M'=>'#3b82f6,#06b6d4','N'=>'#8b5cf6,#6366f1','O'=>'#f59e0b,#10b981','P'=>'#ef4444,#ec4899','Q'=>'#6366f1,#06b6d4','R'=>'#f97316,#f59e0b','S'=>'#10b981,#8b5cf6','T'=>'#3b82f6,#10b981','U'=>'#6366f1,#f97316','V'=>'#06b6d4,#6366f1','W'=>'#ec4899,#f59e0b','X'=>'#8b5cf6,#3b82f6','Y'=>'#f59e0b,#ec4899','Z'=>'#10b981,#6366f1'];
                $ugrad = $ug[$ui] ?? '#6366f1,#8b5cf6';
              ?>
                <a href="<?= base_url('admin/users/view/' . $u->id) ?>" class="rv-user-row">
                  <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,<?= $ugrad ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0;">
                    <?= $ui ?>
                  </div>
                  <div style="min-width:0;flex:1;">
                    <div style="font-size:.82rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($u->fullname) ?></div>
                    <div style="font-size:.75rem;color:var(--t3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($u->email) ?></div>
                  </div>
                  <i class="dw dw-next-button" style="font-size:.65rem;color:var(--t3);flex-shrink:0;"></i>
                </a>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div style="text-align:center;padding:24px;color:var(--t3);font-size:.8rem;">No users assigned to this role</div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Right: Permissions grid -->
      <div class="col-lg-8 mb-20">
        <div class="card-box" style="padding:0;overflow:hidden;height:100%;">
          <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div>
              <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">Assigned Permissions</h3>
              <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;"><?= $permCount ?> permission<?= $permCount !== 1 ? 's' : '' ?> across <?= !empty($permissions) ? count(array_unique(array_column((array)$permissions, 'module'))) : 0 ?> module<?= !empty($permissions) && count(array_unique(array_column((array)$permissions, 'module'))) !== 1 ? 's' : '' ?></p>
            </div>
          </div>
          <div style="padding:20px;">
            <?php if (!empty($permissions)):
              $grouped = [];
              foreach ($permissions as $perm) {
                $grouped[$perm->module][] = $perm;
              }
              $moduleColors = ['users'=>'#6366f1','roles'=>'#8b5cf6','content'=>'#10b981','media'=>'#f59e0b','donations'=>'#06b6d4','settings'=>'#64748b','branches'=>'#f97316','groups'=>'#ec4899'];
              ?>
              <div class="row">
                <?php foreach ($grouped as $module => $perms):
                  $mc = $moduleColors[strtolower($module)] ?? '#6366f1';
                ?>
                  <div class="col-md-6 mb-20">
                    <div class="rv-module-card">
                      <div class="rv-module-header" style="border-left:3px solid <?= $mc ?>;">
                        <span style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t2);"><?= esc(ucfirst($module)) ?></span>
                        <span style="font-size:.72rem;font-weight:600;padding:2px 7px;border-radius:10px;background:<?= $mc ?>22;color:<?= $mc ?>;"><?= count($perms) ?></span>
                      </div>
                      <div class="rv-module-perms">
                        <?php foreach ($perms as $perm): ?>
                          <div class="rv-perm-item">
                            <div style="width:20px;height:20px;border-radius:5px;background:<?= $mc ?>18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                              <i class="dw dw-check-circle-2" style="font-size:.65rem;color:<?= $mc ?>;"></i>
                            </div>
                            <div style="min-width:0;">
                              <div style="font-size:.8rem;font-weight:600;color:var(--t1);"><?= esc($perm->display_name) ?></div>
                              <?php if (!empty($perm->description)): ?>
                                <div style="font-size:.72rem;color:var(--t3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($perm->description) ?></div>
                              <?php endif; ?>
                            </div>
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
                <p style="color:var(--t3);font-size:.875rem;margin:0;">No permissions assigned to this role</p>
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

  .rv-user-row {
    display:flex;align-items:center;gap:9px;padding:7px 8px;border-radius:8px;
    text-decoration:none;transition:background .15s;
  }
  .rv-user-row:hover { background:#f8fafc; }

  .rv-module-card {
    border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;height:100%;
  }
  .rv-module-header {
    display:flex;align-items:center;justify-content:space-between;
    padding:10px 12px;background:#f8fafc;border-bottom:1px solid var(--border);
  }
  .rv-module-perms { padding:10px 12px;display:flex;flex-direction:column;gap:8px; }
  .rv-perm-item { display:flex;align-items:flex-start;gap:8px; }
</style>
