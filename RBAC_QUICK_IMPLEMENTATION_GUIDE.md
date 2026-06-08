# RBAC System - Quick Implementation Guide

## Step 1: Run Migrations

Execute the database migration to create the RBAC tables:

```bash
php spark migrate --namespace App\\Database\\Migrations
```

This will:
- Create `tbl_roles` table
- Create `tbl_permissions` table
- Create `tbl_role_permissions` table
- Add `role_id` column to `tbl_churches` table

## Step 2: Seed Initial Data

Populate the database with predefined roles and permissions:

```bash
php spark db:seed RolesAndPermissionsSeeder
```

This creates:
- 5 predefined roles (Super Admin, Admin, Manager, Editor, Viewer)
- 24 permissions across 6 modules
- Default role-permission mappings

## Step 3: Update Filters Configuration

Register the authorization filters in `app/Config/Filters.php`:

```php
public $aliases = [
    'csrf'                => CSRF::class,
    'toolbar'             => DebugToolbar::class,
    'honeypot'            => Honeypot::class,
    'invalidate'          => InvalidateSession::class,
    'imagefilter'         => ImageFilter::class,
    'authorizeRole'       => AuthorizeRole::class,
    'authorizePermission' => AuthorizePermission::class,
];
```

## Step 4: Update Routes

Register the new admin controllers in `app/Config/Routes.php`:

```php
$routes->group('admin', ['filter' => 'authorizePermission:roles.view'], function($routes) {
    // Roles Management
    $routes->get('roles', 'AdminRoles::index');
    $routes->get('roles/create', 'AdminRoles::create', ['filter' => 'authorizePermission:roles.create']);
    $routes->post('roles/store', 'AdminRoles::store', ['filter' => 'authorizePermission:roles.create']);
    $routes->get('roles/(:num)/edit', 'AdminRoles::edit/$1', ['filter' => 'authorizePermission:roles.edit']);
    $routes->post('roles/(:num)/update', 'AdminRoles::update/$1', ['filter' => 'authorizePermission:roles.edit']);
    $routes->post('roles/(:num)/delete', 'AdminRoles::delete/$1', ['filter' => 'authorizePermission:roles.delete']);
    $routes->get('roles/(:num)/view', 'AdminRoles::view/$1');

    // Users Management
    $routes->get('users', 'AdminUsers::index', ['filter' => 'authorizePermission:users.view']);
    $routes->get('users/create', 'AdminUsers::create', ['filter' => 'authorizePermission:users.create']);
    $routes->post('users/store', 'AdminUsers::store', ['filter' => 'authorizePermission:users.create']);
    $routes->get('users/(:num)/edit', 'AdminUsers::edit/$1', ['filter' => 'authorizePermission:users.edit']);
    $routes->post('users/(:num)/update', 'AdminUsers::update/$1', ['filter' => 'authorizePermission:users.edit']);
    $routes->post('users/(:num)/delete', 'AdminUsers::delete/$1', ['filter' => 'authorizePermission:users.delete']);
    $routes->post('users/(:num)/assign-role', 'AdminUsers::assignRole/$1', ['filter' => 'authorizePermission:users.edit']);
    $routes->get('users/(:num)/view', 'AdminUsers::view/$1');
});
```

## Step 5: Update Login Controller

The Login controller has already been updated to include `roleId` in session. Verify it includes:

```php
$sessionArray = array(
    'userId'        => $auth_user->email,
    'name'        => $auth_user->fullname,
    'role'        => $auth_user->role,
    'roleId'       => $auth_user->role_id,  // <-- Make sure this is included
    'status'        => 0,
    'apitoken'        => getenv('PURCHASE_CODE'),
    'logo' => $auth_user->logo == "" ? "" : base_url() . "/uploads/churches/" . $auth_user->logo,
    'isLoggedIn'    => TRUE
);
```

## Step 6: Load Helper in BaseController

Add the AdminAuthHelper to your BaseController's helpers array:

```php
protected $helpers = ['form', 'url', 'AdminAuth'];
```

Now it's available globally in all controllers.

## Step 7: Assign Roles to Existing Users

For existing admin users, assign them roles using the database:

### Option A: Via SQL
```sql
UPDATE tbl_churches 
SET role_id = 1 
WHERE email = 'superadmin@example.com';

UPDATE tbl_churches 
SET role_id = 2 
WHERE email = 'admin@example.com';
```

### Option B: Via Code
```php
$userModel = new \App\Models\UserRBAC();
$userModel->assignRoleToUser($userId, $roleId);
```

### Option C: Via Admin Panel
1. Navigate to Admin > Users
2. Click Edit on user
3. Select role and save

## Step 8: Protect Your Controllers

Update existing controllers to check permissions:

```php
public function index()
{
    if (!hasPermission('articles.view')) {
        return redirect()->back()->with('error', 'Access Denied');
    }
    
    // Your code here
}

public function create()
{
    if (!hasPermission('articles.create')) {
        return redirect()->back()->with('error', 'Access Denied');
    }
    
    // Your code here
}
```

## Step 9: Protect Views

Add conditional display based on permissions:

```php
<?php if (hasPermission('articles.create')): ?>
    <a href="<?= base_url('admin/articles/create') ?>" class="btn btn-primary">
        New Article
    </a>
<?php endif; ?>

<?php if (hasPermission('articles.delete')): ?>
    <button onclick="deleteArticle(<?= $article->id ?>)" class="btn btn-danger">
        Delete
    </button>
<?php endif; ?>
```

## Step 10: Migrate Admin Users to RBAC

For existing admin users created with the old system, migrate them to use the new role system:

```sql
-- Migrate super admins (role = 1)
UPDATE tbl_churches 
SET role_id = (SELECT id FROM tbl_roles WHERE name = 'super_admin') 
WHERE role = 1 AND role_id IS NULL;

-- Migrate admins (role = 0)
UPDATE tbl_churches 
SET role_id = (SELECT id FROM tbl_roles WHERE name = 'admin') 
WHERE role = 0 AND role_id IS NULL;
```

## Verification Checklist

After implementation, verify:

- [ ] Migrations executed successfully
- [ ] Seeder ran and created roles/permissions
- [ ] Filters registered in Config/Filters.php
- [ ] Routes configured for new controllers
- [ ] Login controller sets roleId in session
- [ ] AdminAuthHelper loaded in helpers
- [ ] AdminRoles controller can be accessed
- [ ] AdminUsers controller can be accessed
- [ ] Permission checks working in controllers
- [ ] Permission checks working in views
- [ ] Existing admin users have roles assigned
- [ ] No errors in application logs

## Testing the System

### 1. Login and Check Session
```php
// In a test controller
$session = session();
echo "Role: " . $session->get('role');
echo "Role ID: " . $session->get('roleId');
```

### 2. Test Permission Check
```php
if (hasPermission('users.create')) {
    echo "Can create users";
} else {
    echo "Cannot create users";
}
```

### 3. Test Role Check
```php
if (hasRole('admin')) {
    echo "Is admin";
}
```

### 4. Create Test User with Each Role
1. Go to Admin > Users > Create
2. Create users with each role
3. Login with each and verify permissions work

## Common Issues & Solutions

### Helper functions not found
**Solution:** Add to BaseController:
```php
protected $helpers = ['AdminAuth'];
```

### Role not being set during login
**Solution:** Ensure user has role_id in database and Login controller includes it in session

### Permission always returns false
**Solution:** 
1. Check roleId is in session: `var_dump(session()->get('roleId'))`
2. Check role has permission: Check tbl_role_permissions
3. Check permission name spelling: Must match exactly

### Filters not working
**Solution:**
1. Verify filter registered in Config/Filters.php
2. Check route filter syntax: `['filter' => 'filterName:arg1,arg2']`
3. Clear CodeIgniter cache

## Next Steps

1. Create views for role and user management
2. Add audit logging for role/permission changes
3. Set up role-based dashboard views
4. Add bulk role assignment
5. Create permission reports
6. Set up role templates

## Files Created/Modified

### New Files
- `app/Models/Role.php`
- `app/Models/Permission.php`
- `app/Models/UserRBAC.php`
- `app/Controllers/AdminRoles.php`
- `app/Controllers/AdminUsers.php`
- `app/Helpers/AdminAuthHelper.php`
- `app/Filters/AuthorizeRole.php`
- `app/Filters/AuthorizePermission.php`
- `app/Database/Migrations/2026-01-01-000001_CreateRolesAndPermissions.php`
- `app/Database/Seeds/RolesAndPermissionsSeeder.php`

### Modified Files
- `app/Controllers/Login.php` - Added roleId to session

### Documentation
- `RBAC_DOCUMENTATION.md` - Complete RBAC documentation
- `RBAC_QUICK_IMPLEMENTATION_GUIDE.md` - This file

## Support & Resources

- Full documentation: See `RBAC_DOCUMENTATION.md`
- Helper functions: `app/Helpers/AdminAuthHelper.php`
- CodeIgniter Guide: https://codeigniter4.github.io/userguide/
