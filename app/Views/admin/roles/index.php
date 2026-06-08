<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="row" style="padding:20px;">
      <div class="col-md-8 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0">Role Management</h2>
          <p class="text-muted">Manage system roles and permissions</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <?php if (hasPermission('roles.create') || isSuperAdmin()): ?>
          <a href="<?= base_url('admin/roles/create') ?>" class="btn btn-primary btn-sm">
            <i class="icon-copy dw dw-add"></i> New Role
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
            <h5>All Roles</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>Role Name</th>
                  <th>Display Name</th>
                  <th>Description</th>
                  <th style="width: 120px;">Permissions</th>
                  <th style="width: 150px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($roles)): ?>
                  <?php $i = 1; ?>
                  <?php foreach ($roles as $role): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td>
                        <span class="badge badge-primary"><?= htmlspecialchars($role->name) ?></span>
                      </td>
                      <td><?= htmlspecialchars($role->display_name) ?></td>
                      <td><?= htmlspecialchars(strlen($role->description) > 50 ? substr($role->description, 0, 50) . '...' : $role->description) ?></td>
                      <td>
                        <a href="<?= base_url('admin/roles/view/' . $role->id) ?>" class="btn btn-xs btn-info" title="View Permissions">
                          <i class="icon-copy dw dw-eye"></i>
                        </a>
                      </td>
                      <td>
                        <?php if (hasPermission('roles.edit') || isSuperAdmin()): ?>
                          <a href="<?= base_url('admin/roles/edit/' . $role->id) ?>" class="btn btn-xs btn-warning" title="Edit">
                            <i class="icon-copy dw dw-edit-2"></i>
                          </a>
                        <?php endif; ?>
                        <?php if ((hasPermission('roles.delete') || isSuperAdmin()) && $role->name !== 'super_admin'): ?>
                          <a href="<?= base_url('admin/roles/delete/' . $role->id) ?>" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this role?')">
                            <i class="icon-copy dw dw-trash"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted">No roles found</td>
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
