<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Role Details</h2>
          <p class="text-muted"><?= htmlspecialchars($role->display_name) ?></p>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary btn-sm">
          <i class="icon-copy dw dw-left"></i> Back to Roles
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card-box">
          <div class="card-header">
            <h5>Role Information</h5>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <tr>
                <td><strong>Name:</strong></td>
                <td><span class="badge badge-primary"><?= htmlspecialchars($role->name) ?></span></td>
              </tr>
              <tr>
                <td><strong>Display Name:</strong></td>
                <td><?= htmlspecialchars($role->display_name) ?></td>
              </tr>
              <tr>
                <td><strong>Description:</strong></td>
                <td><?= htmlspecialchars($role->description ?? 'N/A') ?></td>
              </tr>
              <tr>
                <td><strong>Created:</strong></td>
                <td><?= date('M d, Y H:i', strtotime($role->created_at)) ?></td>
              </tr>
              <tr>
                <td><strong>Updated:</strong></td>
                <td><?= date('M d, Y H:i', strtotime($role->updated_at)) ?></td>
              </tr>
            </table>

            <?php if (hasPermission('roles.edit') || isSuperAdmin()): ?>
              <div class="mt-3">
                <a href="<?= base_url('admin/roles/edit/' . $role->id) ?>" class="btn btn-warning btn-sm">
                  <i class="icon-copy dw dw-edit-2"></i> Edit Role
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card-box">
          <div class="card-header">
            <h5>Users with this Role</h5>
          </div>
          <div class="card-body">
            <?php if (!empty($users)): ?>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Email</th>
                    <th>Name</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?= htmlspecialchars($user->email) ?></td>
                      <td><?= htmlspecialchars($user->fullname) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p class="text-muted">No users assigned to this role</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-lg-12">
        <div class="card-box">
          <div class="card-header">
            <h5>Assigned Permissions</h5>
          </div>
          <div class="card-body">
            <?php if (!empty($permissions)): ?>
              <div class="row">
                <?php 
                $grouped = [];
                foreach ($permissions as $permission) {
                  if (!isset($grouped[$permission->module])) {
                    $grouped[$permission->module] = [];
                  }
                  $grouped[$permission->module][] = $permission;
                }
                ?>
                <?php foreach ($grouped as $module => $perms): ?>
                  <div class="col-md-6 mb-4">
                    <div class="permission-module">
                      <h6 class="text-uppercase font-weight-bold mb-3">
                        <i class="icon-copy dw dw-folder"></i> <?= htmlspecialchars($module) ?>
                      </h6>
                      <div class="permission-list">
                        <?php foreach ($perms as $perm): ?>
                          <div class="mb-2">
                            <span class="badge badge-success"><?= htmlspecialchars($perm->display_name) ?></span>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($perm->description ?? '') ?></small>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p class="text-muted">No permissions assigned to this role</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
