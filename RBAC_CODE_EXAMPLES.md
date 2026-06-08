# RBAC System - Code Examples

Complete code examples for implementing role-based access control in your application.

## 1. Checking Roles in Controllers

### Example 1: Simple Role Check
```php
<?php
namespace App\Controllers;

class Articles extends BaseController
{
    public function create()
    {
        // Check if user has editor role or higher
        if (!hasRole(['editor', 'manager', 'admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'You must be an editor to create articles');
        }

        return view('articles/create', ['viewdata' => $this->viewdata]);
    }

    public function store()
    {
        // Verify permission before creating
        if (!hasPermission('articles.create')) {
            return redirect()->back()->with('error', 'You do not have permission to create articles');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'author_id' => session()->get('userId'),
        ];

        // Save article...
    }

    public function delete($articleId)
    {
        // Only super admin can delete
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can delete articles');
        }

        // Delete article...
    }
}
```

### Example 2: Multiple Permission Checks
```php
<?php
namespace App\Controllers;

class Settings extends BaseController
{
    public function index()
    {
        // Check if user can view settings
        if (!hasPermission('settings.view')) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        // Get settings...
    }

    public function update()
    {
        // Must have all these permissions
        if (!hasAllPermissions(['settings.view', 'settings.edit'])) {
            return redirect()->back()->with('error', 'Insufficient permissions');
        }

        // Update settings...
    }

    public function sendNotification()
    {
        // User must have any of these permissions
        if (!hasAnyPermission(['users.edit', 'roles.edit'])) {
            return redirect()->back()->with('error', 'Cannot perform this action');
        }

        // Send notification...
    }
}
```

### Example 3: Protecting Administrative Functions
```php
<?php
namespace App\Controllers;

class AdminPanel extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        
        // Verify user is logged in and is admin
        $this->checkAdminAccess();
    }

    protected function checkAdminAccess()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        if (!isAdmin()) {
            return redirect()->back()->with('error', 'Admin access required');
        }
    }

    public function dashboard()
    {
        // Get user's current permissions for dashboard display
        $permissions = getCurrentUserPermissions();
        
        return view('admin/dashboard', [
            'permissions' => $permissions,
            'viewdata' => $this->viewdata
        ]);
    }

    public function systemSettings()
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'System settings available to Super Admin only');
        }

        // System settings...
    }
}
```

## 2. Protecting Routes with Filters

### In Config/Routes.php

```php
<?php

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

// Add filters for authorization
$routes->setAutoRoute(false);

// Public routes
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::index');
$routes->post('login/authenticate', 'Login::authenticate');

// Admin routes with role filter
$routes->group('admin', ['filter' => 'authorizeRole:admin,super_admin'], function($routes) {
    $routes->get('dashboard', 'Home::dashboard');
    
    // User Management Routes
    $routes->get('users', 'AdminUsers::index', ['filter' => 'authorizePermission:users.view']);
    $routes->get('users/create', 'AdminUsers::create', ['filter' => 'authorizePermission:users.create']);
    $routes->post('users/store', 'AdminUsers::store', ['filter' => 'authorizePermission:users.create']);
    $routes->get('users/(:num)/edit', 'AdminUsers::edit/$1', ['filter' => 'authorizePermission:users.edit']);
    $routes->post('users/(:num)/update', 'AdminUsers::update/$1', ['filter' => 'authorizePermission:users.edit']);
    $routes->post('users/(:num)/delete', 'AdminUsers::delete/$1', ['filter' => 'authorizePermission:users.delete']);

    // Articles Management Routes
    $routes->get('articles', 'Articles::index', ['filter' => 'authorizePermission:articles.view']);
    $routes->get('articles/create', 'Articles::create', ['filter' => 'authorizePermission:articles.create']);
    $routes->post('articles/store', 'Articles::store', ['filter' => 'authorizePermission:articles.create']);
    $routes->get('articles/(:num)/edit', 'Articles::edit/$1', ['filter' => 'authorizePermission:articles.edit']);
    $routes->post('articles/(:num)/update', 'Articles::update/$1', ['filter' => 'authorizePermission:articles.edit']);
    $routes->post('articles/(:num)/delete', 'Articles::delete/$1', ['filter' => 'authorizePermission:articles.delete']);
});

// Super Admin only routes
$routes->group('super-admin', ['filter' => 'authorizeRole:super_admin'], function($routes) {
    $routes->get('roles', 'AdminRoles::index');
    $routes->get('roles/create', 'AdminRoles::create');
    $routes->post('roles/store', 'AdminRoles::store');
    $routes->get('roles/(:num)/edit', 'AdminRoles::edit/$1');
    $routes->post('roles/(:num)/update', 'AdminRoles::update/$1');
    $routes->post('roles/(:num)/delete', 'AdminRoles::delete/$1');
    
    $routes->get('system-settings', 'SystemSettings::index');
    $routes->post('system-settings/update', 'SystemSettings::update');
});
```

## 3. Using Helper Functions in Views

### Example 1: Conditional Buttons
```php
<!-- views/articles/index.php -->

<div class="articles-container">
    <?php foreach ($articles as $article): ?>
        <div class="article-card">
            <h3><?= $article->title ?></h3>
            <p><?= $article->excerpt ?></p>
            
            <div class="actions">
                <!-- Edit button only for users with edit permission -->
                <?php if (hasPermission('articles.edit')): ?>
                    <a href="<?= base_url('admin/articles/' . $article->id . '/edit') ?>" class="btn btn-primary">
                        Edit
                    </a>
                <?php endif; ?>

                <!-- Delete button only for users with delete permission -->
                <?php if (hasPermission('articles.delete')): ?>
                    <button onclick="deleteArticle(<?= $article->id ?>)" class="btn btn-danger">
                        Delete
                    </button>
                <?php endif; ?>

                <!-- View link available to all -->
                <a href="<?= base_url('articles/' . $article->id) ?>" class="btn btn-info">
                    View
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Show create button only if user has permission -->
<?php if (hasPermission('articles.create')): ?>
    <a href="<?= base_url('admin/articles/create') ?>" class="btn btn-success float-right">
        Create New Article
    </a>
<?php endif; ?>
```

### Example 2: Role-Based Navigation
```php
<!-- views/layouts/navbar.php -->

<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= base_url() ?>">MFM Admin</a>
    </div>

    <ul class="navbar-menu">
        <li><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>

        <!-- Show user management only to admins -->
        <?php if (hasPermission('users.view')): ?>
            <li class="dropdown">
                <a href="#">Users</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= base_url('admin/users') ?>">All Users</a></li>
                    <?php if (hasPermission('users.create')): ?>
                        <li><a href="<?= base_url('admin/users/create') ?>">Add User</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Content management -->
        <?php if (hasPermission('articles.view')): ?>
            <li class="dropdown">
                <a href="#">Content</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= base_url('admin/articles') ?>">Articles</a></li>
                    <li><a href="<?= base_url('admin/videos') ?>">Videos</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Role management for super admin only -->
        <?php if (isSuperAdmin()): ?>
            <li class="dropdown">
                <a href="#">System</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= base_url('super-admin/roles') ?>">Manage Roles</a></li>
                    <li><a href="<?= base_url('super-admin/system-settings') ?>">Settings</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li class="float-right">
            <a href="<?= base_url('login/logout') ?>">Logout</a>
        </li>
    </ul>
</nav>
```

### Example 3: Permission-Based Content Display
```php
<!-- views/user/profile.php -->

<div class="user-profile">
    <h1><?= $user->fullname ?></h1>
    <p>Email: <?= $user->email ?></p>

    <!-- Show role information only to authorized users -->
    <?php if (hasPermission('users.view')): ?>
        <div class="user-role">
            <strong>Role:</strong> <?= $user->role_name ?>
        </div>

        <!-- Show permissions only to super admin -->
        <?php if (isSuperAdmin()): ?>
            <div class="user-permissions">
                <h3>Permissions:</h3>
                <ul>
                    <?php foreach ($user->permissions as $permission): ?>
                        <li><?= $permission->display_name ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Edit button for admins only -->
    <?php if (hasPermission('users.edit')): ?>
        <a href="<?= base_url('admin/users/' . $user->id . '/edit') ?>" class="btn btn-primary">
            Edit User
        </a>
    <?php endif; ?>
</div>
```

## 4. Working with User Models

### Example: Creating Users with Roles
```php
<?php
namespace App\Controllers;

use App\Models\UserRBAC;

class CreateAdminUser extends BaseController
{
    public function create()
    {
        $userModel = new UserRBAC();

        // Create super admin user
        $userModel->createAdminUser([
            'email' => 'superadmin@example.com',
            'password' => 'secure_password_123',
            'fullname' => 'Super Administrator',
            'role_id' => 1, // Assuming super_admin role has ID 1
            'status' => 0,
            'isdelete' => 1,
        ]);

        // Create regular admin user
        $userModel->createAdminUser([
            'email' => 'admin@example.com',
            'password' => 'admin_password_456',
            'fullname' => 'Administrator',
            'role_id' => 2, // Assuming admin role has ID 2
            'status' => 0,
            'isdelete' => 1,
        ]);

        // Create editor user
        $userModel->createAdminUser([
            'email' => 'editor@example.com',
            'password' => 'editor_password_789',
            'fullname' => 'Content Editor',
            'role_id' => 4, // Assuming editor role has ID 4
            'status' => 0,
            'isdelete' => 1,
        ]);
    }
}
```

### Example: Updating User Roles
```php
<?php
namespace App\Controllers;

use App\Models\UserRBAC;

class ManageUsers extends BaseController
{
    public function promoteUserToAdmin($userId)
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can promote users');
        }

        $userModel = new UserRBAC();

        // Get admin role ID
        $adminRole = $this->db->table('tbl_roles')
            ->where('name', 'admin')
            ->first();

        // Update user
        $userModel->assignRoleToUser($userId, $adminRole->id);

        return redirect()->back()->with('success', 'User promoted to Admin');
    }

    public function demoteUserToEditor($userId)
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can demote users');
        }

        $userModel = new UserRBAC();

        // Get editor role ID
        $editorRole = $this->db->table('tbl_roles')
            ->where('name', 'editor')
            ->first();

        // Update user
        $userModel->assignRoleToUser($userId, $editorRole->id);

        return redirect()->back()->with('success', 'User demoted to Editor');
    }
}
```

### Example: Getting User Information
```php
<?php
namespace App\Controllers;

use App\Models\UserRBAC;

class UserInfo extends BaseController
{
    public function getUserData()
    {
        $userModel = new UserRBAC();
        $userId = session()->get('userId');

        // Get user with role information
        $user = $userModel->getUserWithRole($userId);
        echo "User: " . $user->fullname . "\n";
        echo "Role: " . $user->role_name . "\n";

        // Get user's permissions
        $permissions = $userModel->getUserPermissions($userId);
        echo "Permissions:\n";
        foreach ($permissions as $perm) {
            echo "  - " . $perm->display_name . "\n";
        }

        // Check specific permission
        if ($userModel->userHasPermission($userId, 'articles.delete')) {
            echo "Can delete articles\n";
        }

        // Get all data with permissions
        $fullData = $userModel->getUserWithFullData($userId);
        echo "Permission Names: " . implode(', ', $fullData->permission_names) . "\n";
    }
}
```

## 5. Managing Roles and Permissions

### Example: Creating Custom Roles
```php
<?php
namespace App\Controllers;

use App\Models\Role;
use App\Models\Permission;

class RoleManagement extends BaseController
{
    public function createContentManagerRole()
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can create roles');
        }

        $roleModel = new Role();
        $permissionModel = new Permission();

        // Create the role
        $roleModel->save([
            'name' => 'content_manager',
            'display_name' => 'Content Manager',
            'description' => 'Can manage articles, videos, and livestreams',
        ]);

        $roleId = $roleModel->getInsertID();

        // Get specific permissions
        $permissions = $permissionModel->whereIn('name', [
            'articles.view',
            'articles.create',
            'articles.edit',
            'videos.view',
            'videos.create',
            'videos.edit',
            'livestream.view',
            'livestream.create',
            'livestream.edit',
        ])->findAll();

        $permissionIds = array_map(function($p) { return $p->id; }, $permissions);

        // Assign permissions to role
        $roleModel->assignPermissions($roleId, $permissionIds);

        return redirect()->to('admin/roles')->with('success', 'Content Manager role created');
    }

    public function addPermissionToRole($roleId, $permissionId)
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can modify roles');
        }

        $roleModel = new Role();
        $roleModel->assignPermission($roleId, $permissionId);

        return redirect()->back()->with('success', 'Permission added to role');
    }

    public function removePermissionFromRole($roleId, $permissionId)
    {
        if (!isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only Super Admin can modify roles');
        }

        $roleModel = new Role();
        $roleModel->revokePermission($roleId, $permissionId);

        return redirect()->back()->with('success', 'Permission removed from role');
    }
}
```

### Example: Querying Role Information
```php
<?php
namespace App\Controllers;

use App\Models\Role;
use App\Models\Permission;

class ReportGenerator extends BaseController
{
    public function generateRoleReport()
    {
        $roleModel = new Role();
        $permissionModel = new Permission();

        // Get all roles with their permissions
        $roles = $roleModel->findAll();

        echo "Role Report\n";
        echo "===========\n\n";

        foreach ($roles as $role) {
            echo "Role: {$role->display_name}\n";
            echo "Description: {$role->description}\n";

            $permissions = $roleModel->getPermissions($role->id);
            echo "Permissions (" . count($permissions) . "):\n";

            foreach ($permissions as $permission) {
                echo "  - {$permission->display_name} ({$permission->name})\n";
            }

            $users = $roleModel->getUsers($role->id);
            echo "Users (" . count($users) . "):\n";

            foreach ($users as $user) {
                echo "  - {$user->fullname} ({$user->email})\n";
            }

            echo "\n---\n\n";
        }
    }

    public function generatePermissionReport()
    {
        $permissionModel = new Permission();

        // Get permissions grouped by module
        $modules = $permissionModel->getPermissionsByModule();

        echo "Permission Report\n";
        echo "=================\n\n";

        foreach ($modules as $module => $permissions) {
            echo "Module: $module\n";
            echo "Permissions:\n";

            foreach ($permissions as $permission) {
                echo "  - {$permission->display_name} ({$permission->name})\n";
            }

            echo "\n";
        }
    }
}
```

## 6. AJAX Operations for Dynamic Updates

### Example: Assign Role via AJAX
```javascript
// In your JavaScript file
function assignRoleToUser(userId, roleId) {
    fetch('<?= base_url('admin/users') ?>/' + userId + '/assign-role', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'role_id=' + roleId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Role assigned successfully');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
```

### Example: HTML Form
```html
<form id="roleForm">
    <div class="form-group">
        <label for="roleSelect">Assign Role:</label>
        <select id="roleSelect" class="form-control">
            <option value="">-- Select Role --</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role->id ?>" 
                    <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                    <?= $role->display_name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="button" class="btn btn-primary" 
        onclick="assignRoleToUser(<?= $user->id ?>, document.getElementById('roleSelect').value)">
        Update Role
    </button>
</form>
```

These examples cover the most common scenarios when implementing RBAC in your application. Adapt them to your specific needs!
