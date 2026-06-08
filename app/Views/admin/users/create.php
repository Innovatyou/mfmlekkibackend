<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Create New Admin User</h2>
          <p class="text-muted">Add a new administrator to your system</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary btn-sm">
          <i class="icon-copy dw dw-left"></i> Back to Users
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card-box">
          <div class="card-header">
            <h5>User Information</h5>
          </div>
          <form method="POST" action="<?= base_url('admin/users/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
              <label for="email">Email Address <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" required>
            </div>

            <div class="form-group">
              <label for="fullname">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe" required>
            </div>

            <div class="form-group">
              <label for="password">Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter a strong password" required>
              <small class="form-text text-muted">Minimum 6 characters</small>
            </div>

            <div class="form-group">
              <label for="role_id">Assign Role <span class="text-danger">*</span></label>
              <select class="form-control" id="role_id" name="role_id" required>
                <option value="">-- Select a Role --</option>
                <?php if (!empty($roles)): ?>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>"><?= htmlspecialchars($role->display_name) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1">
                <label class="custom-control-label" for="status">
                  Activate user immediately
                </label>
              </div>
            </div>

            <?php if (hasPermission('users.create') || isSuperAdmin()): ?>
              <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="icon-copy dw dw-check"></i> Create Admin User
                </button>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                  <i class="icon-copy dw dw-close"></i> Cancel
                </a>
              </div>
            <?php else: ?>
              <div class="alert alert-warning mt-4">
                <i class="icon-copy dw dw-lock"></i> You don't have permission to create users
              </div>
              <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                <i class="icon-copy dw dw-close"></i> Back to Users
              </a>
            <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-box">
          <div class="card-header">
            <h5>Instructions</h5>
          </div>
          <div class="card-body">
            <p><strong>Step 1:</strong> Enter email address</p>
            <p><strong>Step 2:</strong> Enter full name</p>
            <p><strong>Step 3:</strong> Set a strong password</p>
            <p><strong>Step 4:</strong> Assign a role</p>
            <p><strong>Step 5:</strong> Optionally activate immediately</p>
            <p><strong>Step 6:</strong> Click "Create Admin User"</p>
            <hr>
            <p class="text-muted"><small>The user will receive login credentials. Passwords cannot be retrieved but can be reset.</small></p>
          </div>
        </div>

        <div class="card-box mt-3">
          <div class="card-header">
            <h5>Role Information</h5>
          </div>
          <div class="card-body">
            <p class="text-muted"><small>Roles determine what actions users can perform in the system.</small></p>
            <a href="<?= base_url('admin/roles') ?>" class="btn btn-info btn-sm btn-block">
              <i class="icon-copy dw dw-folder"></i> Manage Roles
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
