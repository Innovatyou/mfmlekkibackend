<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Edit Role</h2>
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
      <div class="col-lg-8">
        <div class="card-box">
          <div class="card-header">
            <h5>Role Information</h5>
          </div>
          <form method="POST" action="<?= base_url('admin/roles/update/' . $role->id) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
              <label for="name">Role Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($role->name) ?>" required>
              <small class="form-text text-muted">Use lowercase with underscores (e.g., content_editor)</small>
            </div>

            <div class="form-group">
              <label for="display_name">Display Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="display_name" name="display_name" value="<?= htmlspecialchars($role->display_name) ?>" required>
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($role->description ?? '') ?></textarea>
            </div>

            <div class="card-header mt-4 mb-3">
              <h5>Assign Permissions</h5>
            </div>

            <?php if (!empty($permissions)): ?>
              <div class="permission-list">
                <?php foreach ($permissions as $module => $perms): ?>
                  <div class="module-section mb-4">
                    <div class="module-header mb-3">
                      <h6 class="text-uppercase font-weight-bold">
                        <i class="icon-copy dw dw-folder"></i> <?= htmlspecialchars($module) ?>
                      </h6>
                      <small class="form-text text-muted">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="module-select-all" data-module="<?= htmlspecialchars($module) ?>">
                          Select All
                        </label>
                      </small>
                    </div>

                    <div class="row">
                      <?php foreach ($perms as $permission): ?>
                        <div class="col-md-6 mb-2">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input permission-checkbox" id="perm_<?= $permission->id ?>" name="permissions[]" value="<?= $permission->id ?>" data-module="<?= htmlspecialchars($module) ?>" <?= in_array($permission->id, $rolePermissionIds ?? []) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="perm_<?= $permission->id ?>">
                              <strong><?= htmlspecialchars($permission->display_name) ?></strong>
                              <br>
                              <small class="text-muted"><?= htmlspecialchars($permission->description ?? '') ?></small>
                            </label>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="alert alert-warning">
                <strong>No permissions available</strong>
                <p>Permissions have not been set up yet. <a href="<?= base_url('setup/permissions') ?>" class="alert-link">Click here to set up permissions</a></p>
              </div>
            <?php endif; ?>

            <?php if (hasPermission('roles.edit') || isSuperAdmin()): ?>
              <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="icon-copy dw dw-check"></i> Update Role
                </button>
                <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
                  <i class="icon-copy dw dw-close"></i> Cancel
                </a>
              </div>
            <?php else: ?>
              <div class="alert alert-warning mt-4">
                <i class="icon-copy dw dw-lock"></i> You don't have permission to edit roles
              </div>
              <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
                <i class="icon-copy dw dw-close"></i> Back to Roles
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-box">
          <div class="card-header">
            <h5>Role Information</h5>
          </div>
          <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($role->name) ?></p>
            <p><strong>Display:</strong> <?= htmlspecialchars($role->display_name) ?></p>
            <p><strong>Created:</strong> <?= date('M d, Y', strtotime($role->created_at)) ?></p>
            <p><strong>Updated:</strong> <?= date('M d, Y', strtotime($role->updated_at)) ?></p>
          </div>
        </div>

        <?php if (hasPermission('roles.delete') || isSuperAdmin()): ?>
          <div class="card-box mt-3">
            <div class="card-header">
              <h5>Danger Zone</h5>
            </div>
            <div class="card-body">
              <?php if ($role->name !== 'super_admin'): ?>
                <a href="<?= base_url('admin/roles/delete/' . $role->id) ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                  <i class="icon-copy dw dw-trash"></i> Delete Role
                </a>
              <?php else: ?>
                <button class="btn btn-secondary btn-block" disabled>
                  <i class="icon-copy dw dw-close"></i> Cannot Delete Super Admin Role
                </button>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.module-select-all').forEach(btn => {
  btn.addEventListener('change', function() {
    const module = this.getAttribute('data-module');
    const isChecked = this.checked;
    document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`).forEach(checkbox => {
      checkbox.checked = isChecked;
    });
  });
});
</script>
