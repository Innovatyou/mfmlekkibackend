# Role-Based Access Control (RBAC) Documentation

## Overview

This document describes the Role-Based Access Control (RBAC) system implemented for the MFM Admin Panel. The system provides granular control over what admin users can do based on their assigned roles and permissions.

## Architecture

### Core Components

1. **Roles** - Define access levels (Super Admin, Admin, Manager, Editor, Viewer)
2. **Permissions** - Specific actions a user can perform (users.view, articles.create, etc.)
3. **Role-Permission Mapping** - Associates permissions with roles
4. **Users** - Admin users assigned to roles

### Database Tables

#### tbl_roles
Stores role definitions
```sql
- id: Primary Key
- name: Unique role identifier (e.g., 'super_admin', 'admin')
- display_name: Human-readable role name
- description: Role description
- created_at, updated_at: Timestamps
```

#### tbl_permissions
Stores permission definitions
```sql
- id: Primary Key
- name: Unique permission identifier (e.g., 'users.view')
- display_name: Human-readable permission name
- description: Permission description
- module: Module the permission belongs to (users, articles, videos, etc.)
- created_at, updated_at: Timestamps
```

#### tbl_role_permissions
Junction table mapping roles to permissions
```sql
- id: Primary Key
- role_id: Foreign key to tbl_roles
- permission_id: Foreign key to tbl_permissions
- created_at: Timestamp
```

#### tbl_churches (Updated)
Added new column for role relationship:
```sql
- role_id: Foreign key to tbl_roles (nullable)
```

## Predefined Roles

### 1. Super Administrator
- **Name**: super_admin
- **Permissions**: All permissions
- **Purpose**: Full system access with no restrictions

### 2. Administrator
- **Name**: admin
- **Permissions**: All except role management and settings edit
- **Purpose**: Full administrative control over content and users

### 3. Manager
- **Name**: manager
- **Permissions**: Can manage users, articles, videos, livestreams (no delete)
- **Purpose**: Moderate content and user management

### 4. Editor
- **Name**: editor
- **Permissions**: Can create and edit content only
- **Purpose**: Content creation and editing

### 5. Viewer
- **Name**: viewer
- **Permissions**: Read-only access to all resources
- **Purpose**: Monitor and view system data

## Permissions

### User Management (users.*)
- `users.view` - View user list
- `users.create` - Create new users
- `users.edit` - Edit existing users
- `users.delete` - Delete users

### Articles Management (articles.*)
- `articles.view` - View article list
- `articles.create` - Create new articles
- `articles.edit` - Edit existing articles
- `articles.delete` - Delete articles

### Videos Management (videos.*)
- `videos.view` - View video list
- `videos.create` - Create new videos
- `videos.edit` - Edit existing videos
- `videos.delete` - Delete videos

### Livestream Management (livestream.*)
- `livestream.view` - View livestreams
- `livestream.create` - Create new livestreams
- `livestream.edit` - Edit livestreams
- `livestream.delete` - Delete livestreams

### Settings Management (settings.*)
- `settings.view` - View system settings
- `settings.edit` - Edit system settings

### Roles Management (roles.*)
- `roles.view` - View role list
- `roles.create` - Create new roles
- `roles.edit` - Edit existing roles
- `roles.delete` - Delete roles

## Installation & Setup

### 1. Run Migration

```bash
php spark migrate --namespace App\\Database\\Migrations
```

This creates:
- `tbl_roles` table
- `tbl_permissions` table
- `tbl_role_permissions` table
- Adds `role_id` column to `tbl_churches`

### 2. Seed Initial Data

```bash
php spark db:seed RolesAndPermissionsSeeder
```

This populates:
- 5 predefined roles
- 24 permissions across modules
- Role-permission mappings for each role

## Usage

### In Controllers

#### Check for Role
```php
// Check if current user has specific role
if (hasRole('super_admin')) {
    // Allow action
}

// Check multiple roles
if (hasRole(['admin', 'manager'])) {
    // Allow action
}

// Check if super admin or admin
if (isAdmin()) {
    // Allow action
}

// Check if super admin
if (isSuperAdmin()) {
    // Allow action
}
```

#### Check for Permission
```php
// Check single permission
if (hasPermission('users.create')) {
    // Allow action
}

// Check multiple permissions (any match)
if (hasAnyPermission(['users.create', 'users.edit'])) {
    // Allow action
}

// Check multiple permissions (all required)
if (hasAllPermissions(['users.view', 'users.create'])) {
    // Allow action
}
```

#### Get Current User Info
```php
// Get current user's role ID
$roleId = getCurrentUserRoleId();

// Get current user's role name
$roleName = getCurrentUserRoleName();

// Get all permissions for current user
$permissions = getCurrentUserPermissions();
```

### Using Filters

#### Role-Based Filter
Apply to routes in `Config/Routes.php`:

```php
// Single role
$routes->group('admin', ['filter' => 'authorizeRole:super_admin'], function($routes) {
    $routes->get('dashboard', 'Home::adminDashboard');
});

// Multiple roles
$routes->group('admin', ['filter' => 'authorizeRole:admin,manager'], function($routes) {
    $routes->get('content', 'Content::index');
});
```

#### Permission-Based Filter
```php
$routes->group('admin', ['filter' => 'authorizePermission:users.create,users.edit'], function($routes) {
    $routes->post('users/store', 'AdminUsers::store');
});
```

### In Views

```php
// Check role
<?php if (hasRole('admin')): ?>
    <!-- Show admin panel -->
<?php endif; ?>

// Check permission
<?php if (hasPermission('articles.create')): ?>
    <a href="<?= base_url('admin/articles/create') ?>">Create Article</a>
<?php endif; ?>

// Show only to super admin
<?php if (isSuperAdmin()): ?>
    <a href="<?= base_url('admin/system-settings') ?>">System Settings</a>
<?php endif; ?>
```

## Models & Methods

### Role Model (`App\Models\Role`)

```php
// Get all permissions for a role
$role->getPermissions($roleId);

// Get permission names for a role
$role->getPermissionNames($roleId);

// Assign single permission to role
$role->assignPermission($roleId, $permissionId);

// Revoke permission from role
$role->revokePermission($roleId, $permissionId);

// Assign multiple permissions to role
$role->assignPermissions($roleId, [$permissionId1, $permissionId2]);

// Get users with this role
$role->getUsers($roleId);
```

### Permission Model (`App\Models\Permission`)

```php
// Get all permissions grouped by module
$permission->getPermissionsByModule();

// Get permissions for a role
$permission->getPermissionsForRole($roleId);

// Check if role has permission
$permission->hasPermission($roleId, 'users.create');

// Get permission by name
$permission->getByName('users.create');

// Get permissions for a user
$permission->getPermissionsForUser($userId);

// Check if user has permission
$permission->userHasPermission($userId, 'articles.create');
```

### UserRBAC Model (`App\Models\UserRBAC`)

```php
// Get user with role details
$user = $userModel->getUserWithRole($userId);

// Get user by email with role
$user = $userModel->getUserByEmailWithRole('admin@example.com');

// Get all users with their roles
$users = $userModel->getAllUsersWithRoles();

// Get users by role
$users = $userModel->getUsersByRole($roleId);

// Assign role to user
$userModel->assignRoleToUser($userId, $roleId);

// Get permissions for a user
$permissions = $userModel->getUserPermissions($userId);

// Check if user has permission
$userModel->userHasPermission($userId, 'users.create');

// Create admin user
$userModel->createAdminUser([
    'email' => 'admin@example.com',
    'password' => 'secure_password',
    'fullname' => 'Admin User',
    'role_id' => 1
]);

// Update admin user
$userModel->updateAdminUser($userId, [
    'fullname' => 'New Name',
    'role_id' => 2
]);

// Get user with full data (including permissions)
$user = $userModel->getUserWithFullData($userId);

// Verify credentials
$user = $userModel->verifyCredentials('admin@example.com', 'password');
```

## Controllers

### AdminRoles Controller

Manage system roles and their permissions

**Routes:**
- `GET /admin/roles` - List all roles
- `GET /admin/roles/create` - Create role form
- `POST /admin/roles/store` - Store new role
- `GET /admin/roles/{id}/edit` - Edit role form
- `POST /admin/roles/{id}/update` - Update role
- `POST /admin/roles/{id}/delete` - Delete role
- `GET /admin/roles/{id}/view` - View role details

### AdminUsers Controller

Manage admin users and role assignments

**Routes:**
- `GET /admin/users` - List all admin users
- `GET /admin/users/create` - Create user form
- `POST /admin/users/store` - Store new user
- `GET /admin/users/{id}/edit` - Edit user form
- `POST /admin/users/{id}/update` - Update user
- `POST /admin/users/{id}/delete` - Delete user
- `POST /admin/users/{id}/assign-role` - Assign role to user (AJAX)
- `GET /admin/users/{id}/view` - View user details

## Session Data

When a user logs in, the following session variables are set:

```php
$_SESSION['userId']    // User email
$_SESSION['name']      // User's full name
$_SESSION['role']      // Role name (e.g., 'admin')
$_SESSION['roleId']    // Role ID from tbl_roles
$_SESSION['isLoggedIn'] // Boolean
```

## Helper Functions

Located in `app/Helpers/AdminAuthHelper.php`:

```php
// Role checks
hasRole($role)                      // Check single role
isAdmin()                           // Check if admin or super_admin
isSuperAdmin()                      // Check if super_admin
getCurrentUserRoleId()              // Get current user's role ID
getCurrentUserRoleName()            // Get current user's role name

// Permission checks
hasPermission($permission)          // Check single permission
hasAnyPermission($permissions)      // Check if has any permission
hasAllPermissions($permissions)     // Check if has all permissions
getCurrentUserPermissions()         // Get all permissions for user

// Data retrieval
getAllRoles()                       // Get all roles
getRolePermissions($roleId)        // Get permissions for role
getRole($roleId)                   // Get role by ID
getPermissionByName($name)         // Get permission by name
getAllPermissionsByModule()        // Get permissions grouped by module
```

## Filters

### AuthorizeRole Filter (`app/Filters/AuthorizeRole.php`)
Checks if user has required role(s) before allowing access to route.

### AuthorizePermission Filter (`app/Filters/AuthorizePermission.php`)
Checks if user has required permission(s) before allowing access to route.

## Creating New Roles

### Programmatically

```php
$roleModel = new \App\Models\Role();

// Create role
$roleModel->save([
    'name' => 'content_manager',
    'display_name' => 'Content Manager',
    'description' => 'Manages content for the platform'
]);

$roleId = $roleModel->getInsertID();

// Assign permissions
$permissionIds = [1, 2, 3, 4]; // IDs of permissions
$roleModel->assignPermissions($roleId, $permissionIds);
```

### Via Admin Panel

1. Navigate to Admin > Roles > Create Role
2. Enter role name and display name
3. Select permissions from the list
4. Submit the form

## Creating New Permissions

### Programmatically

```php
$permissionModel = new \App\Models\Permission();

$permissionModel->save([
    'name' => 'donations.view',
    'display_name' => 'View Donations',
    'description' => 'Can view donation list',
    'module' => 'donations'
]);
```

### Manually via Database

```sql
INSERT INTO tbl_permissions (name, display_name, description, module, created_at, updated_at)
VALUES ('donations.view', 'View Donations', 'Can view donation list', 'donations', NOW(), NOW());
```

## Best Practices

1. **Always check permissions** - Don't rely solely on frontend checks
2. **Use appropriate roles** - Choose the least privileged role for users
3. **Regular audits** - Review user-role assignments periodically
4. **Permission naming** - Use consistent naming: `module.action` format
5. **Documentation** - Keep permission list documented and updated
6. **Session validation** - Always validate session data server-side
7. **Defensive programming** - Always check permissions in sensitive operations

## Common Use Cases

### Protect Admin Dashboard
```php
public function dashboard()
{
    if (!hasPermission('roles.view') && !isSuperAdmin()) {
        return redirect()->back()->with('error', 'Access Denied');
    }
    
    // Show dashboard
}
```

### Hide Buttons in Views
```php
<?php if (hasPermission('articles.delete')): ?>
    <button onclick="deleteArticle(<?= $article->id ?>)">Delete</button>
<?php endif; ?>
```

### Route Protection
```php
$routes->group('admin', function($routes) {
    $routes->get('users', 'AdminUsers::index', ['filter' => 'authorizePermission:users.view']);
    $routes->post('users/store', 'AdminUsers::store', ['filter' => 'authorizePermission:users.create']);
    $routes->post('users/(:num)/update', 'AdminUsers::update/$1', ['filter' => 'authorizePermission:users.edit']);
});
```

## Troubleshooting

### Helper Functions Not Available
Make sure to load the helper in your controller:
```php
helper(['AdminAuth']);
```

### Permission Check Returning False
1. Verify user is logged in: Check `$_SESSION['isLoggedIn']`
2. Verify role_id is set: Check `$_SESSION['roleId']`
3. Verify role has permission: Check `tbl_role_permissions` table
4. Check permission name spelling: Must match exactly

### Filter Not Working
1. Verify filter is registered in `Config/Filters.php`
2. Check route configuration includes filter
3. Ensure user is logged in before filter executes
4. Check filter class namespace is correct

## Future Enhancements

- [ ] Dynamic permission creation UI
- [ ] Permission import/export
- [ ] Role duplication
- [ ] Permission-level audit logs
- [ ] Time-based role assignments
- [ ] Temporary role elevation
- [ ] Permission inheritance
- [ ] Role-based dashboards

## Support

For questions or issues with the RBAC system, please refer to the official CodeIgniter documentation:
- https://codeigniter4.github.io/userguide/

## Related Files

- Models: `app/Models/Role.php`, `app/Models/Permission.php`, `app/Models/UserRBAC.php`
- Controllers: `app/Controllers/AdminRoles.php`, `app/Controllers/AdminUsers.php`
- Helpers: `app/Helpers/AdminAuthHelper.php`
- Filters: `app/Filters/AuthorizeRole.php`, `app/Filters/AuthorizePermission.php`
- Migration: `app/Database/Migrations/2026-01-01-000001_CreateRolesAndPermissions.php`
- Seeder: `app/Database/Seeds/RolesAndPermissionsSeeder.php`
