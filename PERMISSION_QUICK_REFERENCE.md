# Permission System - Quick Reference

## Problem Solved ✅

**Issue**: Only Super Admin could see menus; all other roles saw only Dashboard

**Root Cause**: All menu items were wrapped in a single `if (status == 0)` condition that blocked visibility

**Solution**: Restructured menu hierarchy to apply permission checks individually per module, with status check only for Members

---

## What You Need to Know

### The Fix (One Line Summary)
Moved the outer status check from wrapping all menus to wrapping only the Members dropdown.

### Files Changed
- **`app/Views/templates/header.php`** (Lines 80-196)
  - This was the only file that needed fixing
  - Restructured menu hierarchy

### Testing
1. Log in as different roles (1-5)
2. Verify each role sees only their assigned menus
3. Use test script: `test_permissions_diagnostic.php`

---

## Menu Visibility by Role

| Module | Super Admin | Admin | Editor | Viewer | Contributor |
|--------|:-----------:|:-----:|:------:|:------:|:-----------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Members | ✅* | ✅* | ✅* | ✅* | ❌ |
| Donations | ✅ | ✅ | ✅ | ✅ | ❌ |
| Media | ✅ | ✅ | ✅ | ✅ | ✅ |
| Publications | ✅ | ✅ | ✅ | ✅ | ✅ |
| Connect | ✅ | ✅ | ✅ | ✅ | ✅ |
| Events | ✅ | ✅ | ✅ | ✅ | ✅ |
| Hymns | ✅ | ✅ | ✅ | ✅ | ❌ |
| Messaging | ✅ | ✅ | ✅ | ✅ | ❌ |
| Locations | ✅ | ✅ | ✅ | ✅ | ❌ |
| Settings | ✅ | ✅ | ❌ | ❌ | ❌ |
| Administration | ✅ | ❌ | ❌ | ❌ | ❌ |

*Members requires status=0 (active member status) in addition to permission

---

## How Permission Checking Works

```
User Logs In
    ↓
Session stores roleId (1-5)
    ↓
User navigates to page
    ↓
Template calls hasPermission('module.view')
    ↓
hasPermission() function:
  - Gets roleId from session
  - Looks up role in app/Config/Permissions.php
  - Checks if permission is in that role's array
  - Returns true/false
    ↓
Menu item shows if permission = true
Menu item hides if permission = false
```

---

## Key Code Locations

### Permission Check Function
📄 **`app/Helpers/AdminAuth_helper.php`** Line 36-58
```php
function hasPermission($permission) {
    $roleId = session()->get('roleId');
    $config = config('Permissions');
    return isset($config[$roleId]) && in_array($permission, $config[$roleId]);
}
```

### Permission Configuration
📄 **`app/Config/Permissions.php`** Line 1-68
```php
return [
    1 => ['members.view', 'members.edit', ...],  // Super Admin
    2 => ['members.view', 'members.edit', ...],  // Admin
    // ... etc for roles 3, 4, 5
];
```

### Menu Template
📄 **`app/Views/templates/header.php`** Line 80-196
```php
<?php if (hasPermission('module.view')): ?>
    <li><!-- Menu item --></li>
<?php endif; ?>
```

### Session Setup
📄 **`app/Controllers/Login.php`** Line 56
```php
session()->set('roleId', $auth_user->role_id);
```

---

## Troubleshooting Quick Fixes

### Only Dashboard shows
```
1. Check: session()->get('roleId') is set to 1-5
2. Check: User's roleId exists in Permissions.php
3. Fix: Re-login to refresh session
```

### Some menus show, some don't
```
1. Check: Permission names exactly match in header.php and Permissions.php
2. Check: Use 'module.view' not 'module_view'
3. Fix: Update Permissions.php with correct permission names
```

### Error: hasPermission() undefined
```
1. Check: app/Helpers/AdminAuth_helper.php exists
2. Check: BaseController has protected $helpers = ['AdminAuth'];
3. Fix: Restart PHP/clear cache
```

---

## Making Changes

### To Give a Role New Permission
Edit `app/Config/Permissions.php`:
```php
3 => [ // Editor
    'members.view',
    'donations.view',     // Add this line
    'media.view', 'media.edit',
    // ...
],
```
Changes take effect immediately.

### To Create a New Menu Item
1. Add to `app/Config/Permissions.php`
2. Add permission check in `app/Views/templates/header.php`
```php
<?php if (hasPermission('newmodule.view')): ?>
    <li><a href="/newmodule">New Module</a></li>
<?php endif; ?>
```

### To Change Role Permissions via Admin Panel
1. Go to Admin → User Roles
2. Select user
3. Change role_id (1-5)
4. Save
5. User sees new menus on next login

---

## Verification Checklist

- ✅ Dashboard always visible
- ✅ Members requires permission AND status=0
- ✅ All other modules require permission only
- ✅ No outer wrapper blocking menus
- ✅ Each module has individual permission check
- ✅ hasPermission() function exists and is autoloaded
- ✅ Permissions.php config exists and is properly formatted
- ✅ Login controller sets roleId in session
- ✅ Super Admin has all permissions
- ✅ Other roles have appropriate permissions per config

---

## Support References

### Documentation Files
- `PERMISSION_SYSTEM_COMPLETE_FIX.md` - Full technical details
- `PERMISSION_FIX_COMPLETE.md` - Implementation summary
- `HEADER_STRUCTURE_VERIFICATION.md` - Code structure details
- `test_permissions_diagnostic.php` - Diagnostic script

### Framework
- CodeIgniter 4.1.3 (Config, Controllers, Views, Helpers)

### Database Tables
- `tbl_admin_users` (user accounts)
- `tbl_roles` (role definitions)
- `tbl_role_permissions` (optional, for database-based permissions)

---

## Remember

1. **Permission = Module Access**: 'members.view' = can view members module
2. **Config First**: Permissions.php is checked first, database second
3. **Session Required**: roleId must be in session for any checks to work
4. **Members Special**: Only module that checks status (legacy behavior)
5. **Super Admin Always**: isSuperAdmin() bypasses all checks as fallback

Permission system is now **fully functional**. Each role sees only what they're configured to see. ✅
