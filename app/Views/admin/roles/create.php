<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Create New Role</h2>
          <p class="text-muted">Add a new role to your system</p>
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
          <form method="POST" action="<?= base_url('admin/roles/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
              <label for="name">Role Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="e.g., content_editor" required>
              <small class="form-text text-muted">Use lowercase with underscores (e.g., content_editor)</small>
            </div>

            <div class="form-group">
              <label for="display_name">Display Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="display_name" name="display_name" placeholder="e.g., Content Editor" required>
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe what this role can do..."></textarea>
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
                            <input type="checkbox" class="custom-control-input permission-checkbox" id="perm_<?= $permission->id ?>" name="permissions[]" value="<?= $permission->id ?>" data-module="<?= htmlspecialchars($module) ?>">
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

            <?php if (hasPermission('roles.create') || isSuperAdmin()): ?>
              <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="icon-copy dw dw-check"></i> Create Role
                </button>
                <a href="<?= base_url('admin/roles') ?>" class="btn btn-secondary">
                  <i class="icon-copy dw dw-close"></i> Cancel
                </a>
              </div>
            <?php else: ?>
              <div class="alert alert-warning mt-4">
                <i class="icon-copy dw dw-lock"></i> You don't have permission to create roles
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
            <h5>Instructions</h5>
          </div>
          <div class="card-body">
            <p><strong>Step 1:</strong> Enter role name and display name</p>
            <p><strong>Step 2:</strong> Add a description (optional)</p>
            <p><strong>Step 3:</strong> Select permissions for this role</p>
            <p><strong>Step 4:</strong> Click "Create Role"</p>
            <hr>
            <p class="text-muted"><small>You can modify permissions after creating the role.</small></p>
          </div>
        </div>
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
