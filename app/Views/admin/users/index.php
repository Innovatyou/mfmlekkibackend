<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Admin Users</h1>
        <nav class="au-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a>
          <span>/</span>
          <span>Admin Users</span>
        </nav>
      </div>
      <?php if (hasPermission('users.create') || isSuperAdmin()): ?>
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:9px 20px;font-size:.875rem;">
          <i class="dw dw-add" style="margin-right:6px;font-size:.9rem;"></i>New Admin User
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

    <!-- Users table card -->
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
        <div>
          <h3 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0;">All Admin Users</h3>
          <p style="font-size:.8rem;color:var(--t3);margin:2px 0 0;"><?= count($users ?? []) ?> user<?= count($users ?? []) !== 1 ? 's' : '' ?> in the system</p>
        </div>
      </div>

      <?php if (!empty($users)): ?>
        <div style="overflow-x:auto;">
          <table class="data-table table nowrap" style="margin:0;width:100%;">
            <thead>
              <tr>
                <th style="width:50px;">#</th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th style="width:130px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; foreach ($users as $u): ?>
                <?php
                  $initials = strtoupper(substr($u->fullname ?? 'U', 0, 1));
                  $gradients = ['A'=>'#6366f1,#8b5cf6','B'=>'#3b82f6,#6366f1','C'=>'#06b6d4,#3b82f6','D'=>'#10b981,#06b6d4','E'=>'#f59e0b,#f97316','F'=>'#ef4444,#f59e0b','G'=>'#8b5cf6,#ec4899','H'=>'#06b6d4,#10b981','I'=>'#6366f1,#3b82f6','J'=>'#f97316,#ef4444','K'=>'#10b981,#3b82f6','L'=>'#ec4899,#8b5cf6','M'=>'#3b82f6,#06b6d4','N'=>'#8b5cf6,#6366f1','O'=>'#f59e0b,#10b981','P'=>'#ef4444,#ec4899','Q'=>'#6366f1,#06b6d4','R'=>'#f97316,#f59e0b','S'=>'#10b981,#8b5cf6','T'=>'#3b82f6,#10b981','U'=>'#6366f1,#f97316','V'=>'#06b6d4,#6366f1','W'=>'#ec4899,#f59e0b','X'=>'#8b5cf6,#3b82f6','Y'=>'#f59e0b,#ec4899','Z'=>'#10b981,#6366f1'];
                  $grad = $gradients[$initials] ?? '6366f1,8b5cf6';
                  $slug = strtolower($u->role ?? '');
                  $roleColor = str_contains($slug,'super') ? '#ef4444' : (str_contains($slug,'admin') ? '#6366f1' : (str_contains($slug,'editor') ? '#10b981' : '#64748b'));
                  $roleBg    = str_contains($slug,'super') ? '#fef2f2' : (str_contains($slug,'admin') ? '#eef2ff' : (str_contains($slug,'editor') ? '#ecfdf5' : '#f1f5f9'));
                ?>
                <tr>
                  <td style="color:var(--t3);font-weight:600;"><?= $i++ ?></td>
                  <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                      <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,<?= $grad ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">
                        <?= $initials ?>
                      </div>
                      <div>
                        <div style="font-weight:600;color:var(--t1);font-size:.875rem;line-height:1.2;"><?= esc($u->fullname) ?></div>
                      </div>
                    </div>
                  </td>
                  <td style="color:var(--t2);font-size:.875rem;"><?= esc($u->email) ?></td>
                  <td>
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:<?= $roleBg ?>;color:<?= $roleColor ?>;">
                      <?= esc($u->role_display_name ?? $u->role ?? 'N/A') ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($u->status == 1): ?>
                      <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:#ecfdf5;color:#059669;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;"></span>Active
                      </span>
                    <?php else: ?>
                      <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;background:#fffbeb;color:#d97706;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>Inactive
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="display:flex;gap:5px;">
                      <a href="<?= base_url('admin/users/view/' . $u->id) ?>" class="au-action-btn au-action-view" title="View">
                        <i class="dw dw-eye"></i>
                      </a>
                      <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
                        <a href="<?= base_url('admin/users/edit/' . $u->id) ?>" class="au-action-btn au-action-edit" title="Edit">
                          <i class="dw dw-edit-2"></i>
                        </a>
                      <?php endif; ?>
                      <?php if ((hasPermission('users.delete') || isSuperAdmin()) && $u->email !== $session->get('userId')): ?>
                        <a href="<?= base_url('admin/users/delete/' . $u->id) ?>" class="au-action-btn au-action-delete" title="Delete"
                           onclick="return confirm('Delete <?= esc(addslashes($u->fullname)) ?>? This cannot be undone.')">
                          <i class="dw dw-trash"></i>
                        </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php else: ?>
        <!-- Empty state -->
        <div style="text-align:center;padding:64px 20px;">
          <div style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="dw dw-user1" style="font-size:2rem;color:#6366f1;"></i>
          </div>
          <h4 style="font-size:1rem;font-weight:700;color:var(--t1);margin:0 0 6px;">No admin users yet</h4>
          <p style="font-size:.875rem;color:var(--t3);margin:0 0 20px;">Create the first admin user to get started.</p>
          <?php if (hasPermission('users.create') || isSuperAdmin()): ?>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary" style="border-radius:8px;font-weight:600;">
              <i class="dw dw-add" style="margin-right:6px;"></i>Create First User
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

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
  .au-alert-close {
    position:absolute;right:12px;top:50%;transform:translateY(-50%);
    background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;
    line-height:1;padding:0;
  }
  .au-alert-close:hover { opacity:1; }

  .au-action-btn {
    width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;
    justify-content:center;font-size:.85rem;text-decoration:none;transition:all .15s;
  }
  .au-action-view   { background:#eef2ff;color:#6366f1; }
  .au-action-view:hover   { background:#6366f1;color:#fff; }
  .au-action-edit   { background:#fffbeb;color:#d97706; }
  .au-action-edit:hover   { background:#f59e0b;color:#fff; }
  .au-action-delete { background:#fef2f2;color:#ef4444; }
  .au-action-delete:hover { background:#ef4444;color:#fff; }
</style>
