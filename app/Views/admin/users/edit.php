<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Edit Admin User</h2>
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
      <div class="col-lg-8">
        <div class="card-box">
          <div class="card-header">
            <h5>User Information</h5>
          </div>
          <form method="POST" action="<?= base_url('admin/users/update/' . $user->id) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
              <label for="email">Email Address <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" disabled>
              <small class="form-text text-muted">Email cannot be changed</small>
            </div>

            <div class="form-group">
              <label for="fullname">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($user->fullname) ?>" required>
            </div>

            <div class="form-group">
              <label for="password">Password (leave blank to keep current)</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password to change">
              <small class="form-text text-muted">Minimum 6 characters</small>
            </div>

            <div class="form-group">
              <label for="role_id">Assign Role <span class="text-danger">*</span></label>
              <select class="form-control" id="role_id" name="role_id" required>
                <option value="">-- Select a Role --</option>
                <?php if (!empty($roles)): ?>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                      <?= htmlspecialchars($role->display_name) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" <?= $user->status == 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="status">
                  User is active
                </label>
              </div>
            </div>

            <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
              <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="icon-copy dw dw-check"></i> Update Admin User
                </button>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                  <i class="icon-copy dw dw-close"></i> Cancel
                </a>
              </div>
            <?php else: ?>
              <div class="alert alert-warning mt-4">
                <i class="icon-copy dw dw-lock"></i> You don't have permission to edit users
              </div>
              <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                <i class="icon-copy dw dw-close"></i> Back to Users
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card-box">
          <div class="card-header">
            <h5>User Information</h5>
          </div>
          <div class="card-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user->fullname) ?></p>
            <p><strong>Role:</strong> <span class="badge badge-info"><?= htmlspecialchars($user->role_display_name ?? $user->role ?? 'N/A') ?></span></p>
            <p><strong>Status:</strong> 
              <?php if ($user->status == 1): ?>
                <span class="badge badge-success">Active</span>
              <?php else: ?>
                <span class="badge badge-warning">Inactive</span>
              <?php endif; ?>
            </p>
            <p><strong>Created:</strong> <?= date('M d, Y', strtotime($user->date_created)) ?></p>
          </div>
        </div>

        <?php if ($user->email !== $session->get('userId')): ?>
          <?php if (hasPermission('users.delete') || isSuperAdmin()): ?>
            <div class="card-box mt-3">
              <div class="card-header">
                <h5>Danger Zone</h5>
              </div>
              <div class="card-body">
                <a href="<?= base_url('admin/users/delete/' . $user->id) ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                  <i class="icon-copy dw dw-trash"></i> Delete User
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="card-box mt-3">
            <div class="card-header">
              <h5>Danger Zone</h5>
            </div>
            <div class="card-body">
              <button class="btn btn-secondary btn-block" disabled>
                <i class="icon-copy dw dw-close"></i> Cannot Delete Your Own Account
              </button>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
