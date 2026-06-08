# Admin User Roles - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Step 1: Run Database Migration
Run this command in your terminal from the project root:

```bash
php spark migrate
```

**What this does:**
- Creates `tbl_roles` table
- Creates `tbl_permissions` table  
- Creates `tbl_role_permissions` table
- Adds `role_id` column to `tbl_churches`

### Step 2: Populate Initial Data
Run the seeder:

```bash
php spark db:seed RolesAndPermissionsSeeder
```

**What this does:**
- Creates 5 predefined roles
- Creates 24 predefined permissions
- Automatically assigns permissions to each role

### Step 3: Assign Roles to Existing Users
Run this SQL in your database:

```sql
-- Make your main admin a super admin
UPDATE tbl_churches SET role_id = 1 WHERE email = 'youremail@example.com';
```

The role IDs are:
- `1` = Super Administrator (full access)
- `2` = Administrator (all except role management)
- `3` = Manager (content & users, no delete)
- `4` = Editor (content creation only)
- `5` = Viewer (read-only access)

### Step 4: Access Admin Panel
Login and navigate to:
- **Manage Roles:** `http://yoursite.com/admin/roles`
- **Manage Users:** `http://yoursite.com/admin/users`

---

## 📋 Predefined Roles

| Role | ID | Permissions | Best For |
|------|-----|------------|----------|
| Super Administrator | 1 | All 24 permissions | System owner |
| Administrator | 2 | All except role management | Senior staff |
| Manager | 3 | Content & users (no delete) | Department heads |
| Editor | 4 | Create/edit content only | Content creators |
| Viewer | 5 | Read-only access | Guests/observers |

---

## 🔐 Available Permissions

### User Management (4 permissions)
- `users.view` - View user list
- `users.create` - Create new users
- `users.edit` - Edit users
- `users.delete` - Delete users

### Articles Management (4 permissions)
- `articles.view` - View articles
- `articles.create` - Create articles
- `articles.edit` - Edit articles
- `articles.delete` - Delete articles

### Videos Management (4 permissions)
- `videos.view` - View videos
- `videos.create` - Upload videos
- `videos.edit` - Edit videos
- `videos.delete` - Delete videos

### Livestreams Management (4 permissions)
- `livestreams.view` - View livestreams
- `livestreams.create` - Create livestreams
- `livestreams.edit` - Edit livestreams
- `livestreams.delete` - Delete livestreams

### Settings Management (2 permissions)
- `settings.view` - View settings
- `settings.edit` - Edit settings

### Roles Management (2 permissions)
- `roles.view` - View roles
- `roles.create` - Create roles
- `roles.edit` - Edit roles
- `roles.delete` - Delete roles

---

## 💡 Common Tasks

### Create a New Role
1. Go to `/admin/roles`
2. Click "New Role"
3. Fill in:
   - Role Name: `content_manager` (lowercase, underscores)
   - Display Name: `Content Manager` (human readable)
   - Description: What this role can do (optional)
4. Select permissions from the checklist
5. Click "Create Role"

### Create a New Admin User
1. Go to `/admin/users`
2. Click "New Admin User"
3. Fill in:
   - Email: user@example.com
   - Full Name: John Doe
   - Password: Strong password (min 6 chars)
   - Role: Select from dropdown
4. Optionally check "Activate immediately"
5. Click "Create Admin User"

### Assign Role to Existing User
1. Go to `/admin/users`
2. Click the user's Edit button
3. Change the Role dropdown
4. Click "Update Admin User"

### Edit Role Permissions
1. Go to `/admin/roles`
2. Click the Edit button on the role
3. Check/uncheck permissions as needed
4. Click "Update Role"

### Delete a Role
1. Go to `/admin/roles`
2. Click the Delete button
3. Note: Only works if no users are assigned to the role
4. Confirm deletion

---

## ✅ Verify Installation

After running migrations and seeders, verify in your database:

```sql
-- Should show 5 roles
SELECT * FROM tbl_roles;

-- Should show 24 permissions
SELECT * FROM tbl_permissions;

-- Should show role-permission mappings
SELECT * FROM tbl_role_permissions;

-- Your churches table should have role_id column
DESC tbl_churches;
```

---

## 🔍 Using Permissions in Your Code

### In Controllers
```php
// Check permission in controller
public function deleteArticle($id) {
    if (!hasPermission('articles.delete')) {
        return redirect()->back()->with('error', 'Not allowed');
    }
    // Delete article...
}
```

### In Views
```php
<!-- Show button only if user can delete -->
<?php if (hasPermission('articles.delete')): ?>
    <button onclick="delete(<?= $id ?>)" class="btn btn-danger">Delete</button>
<?php endif; ?>
```

### In Routes
```php
// Protect route with permission
$routes->post('articles/delete', 'Articles::delete', 
    ['filter' => 'authorizePermission:articles.delete']);

// Protect route with role
$routes->get('admin/settings', 'Settings::admin',
    ['filter' => 'authorizeRole:admin,super_admin']);
```

---

## ⚡ Helper Functions Reference

```php
// Role checking
hasRole('admin')                    // Is user admin?
hasRole(['admin', 'manager'])       // Is user admin or manager?
isAdmin()                          // Is admin or super_admin?
isSuperAdmin()                     // Is super_admin?
getCurrentUserRoleId()             // Get user's role ID
getCurrentUserRoleName()           // Get user's role name

// Permission checking
hasPermission('articles.create')        // Has single permission?
hasAnyPermission(['articles.edit', ...]) // Has any permission?
hasAllPermissions(['articles.view', ...]) // Has all permissions?
getCurrentUserPermissions()              // Get all user permissions

// Data retrieval
getAllRoles()                      // Get all roles
getRolePermissions($roleId)        // Get role's permissions
getRole($roleId)                   // Get role by ID
getPermissionByName($name)         // Get permission by name
getAllPermissionsByModule()        // Get permissions grouped by module
```

---

## 🐛 Troubleshooting

### Issue: "Access Denied" when accessing admin pages
- **Solution:** Check that user has been assigned a role in database
- **Fix:** Run: `UPDATE tbl_churches SET role_id = 2 WHERE email = 'user@example.com';`

### Issue: Permission checks not working
- **Solution:** Make sure helper is loaded in controller
- **Fix:** Add to constructor: `helper(['form', 'url', 'AdminAuth']);`

### Issue: Can't see admin users/roles pages
- **Solution:** You need `users.view` or `roles.view` permission
- **Fix:** Assign a role with these permissions to your user

### Issue: Migration fails
- **Solution:** Another migration may have already created tables
- **Fix:** Check if `tbl_roles` table already exists, delete if needed

### Issue: Seeder fails
- **Solution:** Tables must exist first
- **Fix:** Run migrations first: `php spark migrate`

---

## 📞 Support

For detailed documentation, see:
- `RBAC_DOCUMENTATION.md` - Complete technical reference
- `RBAC_IMPLEMENTATION_CHECKLIST.md` - Full implementation checklist
- `ADMIN_ROLES_IMPLEMENTATION_STATUS.md` - Current status report

---

**Last Updated:** January 1, 2026  
**Status:** Ready for Production ✅
