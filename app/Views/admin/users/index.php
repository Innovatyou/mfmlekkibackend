<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Admin User Management</h2>
          <p class="text-muted">Manage system administrators and their roles</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <?php if (hasPermission('users.create') || isSuperAdmin()): ?>
          <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
            <i class="icon-copy dw dw-add"></i> New Admin User
          </a>
        <?php endif; ?>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <div class="card-header">
            <h5>All Admin Users</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>Email</th>
                  <th>Full Name</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th style="width: 150px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($users)): ?>
                  <?php $i = 1; ?>
                  <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td><?= htmlspecialchars($user->email) ?></td>
                      <td><?= htmlspecialchars($user->fullname) ?></td>
                      <td>
                        <span class="badge badge-info"><?= htmlspecialchars($user->role_display_name ?? $user->role ?? 'N/A') ?></span>
                      </td>
                      <td>
                        <?php if ($user->status == 1): ?>
                          <span class="badge badge-success">Active</span>
                        <?php else: ?>
                          <span class="badge badge-warning">Inactive</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <a href="<?= base_url('admin/users/view/' . $user->id) ?>" class="btn btn-xs btn-info" title="View">
                          <i class="icon-copy dw dw-eye"></i>
                        </a>
                        <?php if (hasPermission('users.edit') || isSuperAdmin()): ?>
                          <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-xs btn-warning" title="Edit">
                            <i class="icon-copy dw dw-edit-2"></i>
                          </a>
                        <?php endif; ?>
                        <?php if ((hasPermission('users.delete') || isSuperAdmin()) && $user->email !== $session->get('userId')): ?>
                          <a href="<?= base_url('admin/users/delete/' . $user->id) ?>" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                            <i class="icon-copy dw dw-trash"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted">No admin users found</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
