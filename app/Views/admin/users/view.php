<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">User Details</h2>
          <p class="text-muted"><?= htmlspecialchars($user->fullname) ?></p>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary btn-sm">
          <i class="icon-copy dw dw-left"></i> Back to Users
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card-box">
          <div class="card-header">
            <h5>User Information</h5>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <tr>
                <td><strong>Email:</strong></td>
                <td><?= htmlspecialchars($user->email) ?></td>
              </tr>
              <tr>
                <td><strong>Name:</strong></td>
                <td><?= htmlspecialchars($user->fullname) ?></td>
              </tr>
              <tr>
                <td><strong>Role:</strong></td>
                <td><span class="badge badge-info"><?= htmlspecialchars($user->role_display_name ?? $user->role ?? 'N/A') ?></span></td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                  <?php if ($user->status == 1): ?>
                    <span class="badge badge-success">Active</span>
                  <?php else: ?>
                    <span class="badge badge-warning">Inactive</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td><strong>Created:</strong></td>
                <td><?= date('M d, Y H:i', strtotime($user->date_created)) ?></td>
              </tr>
            </table>

            <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
              <div class="mt-3">
                <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-warning btn-sm">
                  <i class="icon-copy dw dw-edit-2"></i> Edit User
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card-box">
          <div class="card-header">
            <h5>Assigned Permissions</h5>
          </div>
          <div class="card-body">
            <?php if (!empty($user->permission_names)): ?>
              <div class="permission-list">
                <?php 
                $permsByModule = [];
                foreach ($user->permissions as $perm) {
                  if (!isset($permsByModule[$perm->module])) {
                    $permsByModule[$perm->module] = [];
                  }
                  $permsByModule[$perm->module][] = $perm;
                }
                ?>
                <?php foreach ($permsByModule as $module => $perms): ?>
                  <div class="module-section mb-3">
                    <h6 class="text-uppercase font-weight-bold mb-2">
                      <i class="icon-copy dw dw-folder"></i> <?= htmlspecialchars($module) ?>
                    </h6>
                    <?php foreach ($perms as $perm): ?>
                      <div class="mb-2">
                        <span class="badge badge-success"><?= htmlspecialchars($perm->display_name) ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p class="text-muted">No permissions assigned</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
