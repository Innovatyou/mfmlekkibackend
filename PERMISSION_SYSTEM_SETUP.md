# Permission System Setup Instructions

## Overview
The permission system is now fully integrated with role-based access control. Users will only see menu items for modules they have permission to access.

## Configuration-Based Permissions

The system now uses a configuration file (`app/Config/Permissions.php`) that defines what each role can access. This means the menu will work immediately without requiring database setup.

### Role Permissions

**Super Admin (Role ID: 1)** - Can access everything
- All modules with view and edit permissions

**Admin (Role ID: 2)** - Can access most modules except admin settings
- Members, Donations, Media, Publications, Connect, Events, Hymns, Messaging, Locations, Settings

**Editor (Role ID: 3)** - Can view and edit content
- Members (view), Donations (view), Media (view/edit), Publications (view/edit), Connect (view/edit), Events (view/edit), Hymns (view/edit), Messaging (view), Locations (view)

**Viewer (Role ID: 4)** - Can only view modules
- Members (view), Donations (view), Media (view), Publications (view), Connect (view), Events (view), Hymns (view), Messaging (view), Locations (view)

**Contributor (Role ID: 5)** - Can contribute content
- Media (view/edit), Publications (view/edit), Connect (view/edit), Events (view)

## Database Setup (Optional)

If you want to manage permissions through the admin interface, you need to populate the permissions database tables.

### Option 1: Using the Setup Endpoint

Visit the following URL in your browser:
```
http://your-domain/setup/permissions
```

This will:
1. Create all permission records in `tbl_permissions`
2. Assign permissions to each role in `tbl_role_permissions`

### Option 2: Manual Database Setup

Run these SQL commands:

```sql
-- Insert permissions
INSERT INTO tbl_permissions (name, display_name, module, description, created_at, updated_at) VALUES
('members.view', 'View Members', 'members', 'View member list and details', NOW(), NOW()),
('members.edit', 'Edit Members', 'members', 'Edit member information', NOW(), NOW()),
('donations.view', 'View Donations', 'donations', 'View donation records', NOW(), NOW()),
('donations.edit', 'Edit Donations', 'donations', 'Create and edit donations', NOW(), NOW()),
-- ... (see SetupController for complete list)
```

## How It Works

1. **User Session** - When a user logs in, their `roleId` is stored in the session
2. **Permission Check** - When rendering menus, the system checks:
   - First: Configuration-based permissions from `app/Config/Permissions.php`
   - Second: Database permissions (if database has been set up)
3. **Super Admin Override** - Super admin users always have `isSuperAdmin()` returning true, allowing them to access everything

## Testing Permissions

### As Editor Role:
- Should see: Dashboard, Members (view), Donations, Media (with edit), Publications, Connect, Events, Hymns, Messaging, Locations
- Should NOT see: Settings, Administration

### As Viewer Role:
- Should see: Dashboard, Members, Donations, Media, Publications, Connect, Events, Hymns, Messaging, Locations
- Should NOT see: Settings, Administration, any edit options

### As Super Admin:
- Should see: All modules including Settings and Administration

## Updating Permissions

### Through Database
1. Go to Admin > Roles
2. Click Edit on a role
3. Check/uncheck permissions
4. Click Update

This updates the `tbl_role_permissions` table.

### Through Configuration
Edit `app/Config/Permissions.php` and add/remove permissions for each role:

```php
3 => [ // Editor role
    'members.view',
    'donations.view',
    // ... add more permissions
],
```

## File Locations

- **Configuration**: [app/Config/Permissions.php](app/Config/Permissions.php)
- **Helper**: [app/Helpers/AdminAuth_helper.php](app/Helpers/AdminAuth_helper.php)
- **Setup Controller**: [app/Controllers/SetupController.php](app/Controllers/SetupController.php)
- **Permission Model**: [app/Models/Permission.php](app/Models/Permission.php)
- **Role Model**: [app/Models/Role.php](app/Models/Role.php)
- **Header Template**: [app/Views/templates/header.php](app/Views/templates/header.php)

## Troubleshooting

### Menu items not showing:
1. Verify the user's `roleId` is set correctly in the session
2. Check that the permission name matches exactly (case-sensitive)
3. Ensure the configuration file has the correct permission for the role
4. Run the setup endpoint to populate database permissions

### Super admin seeing nothing:
1. Check that `isSuperAdmin()` returns true (roleId == 1)
2. Verify session has `roleId` set to 1

### Updates not persisting:
1. Check that `tbl_role_permissions` table exists
2. Verify permissions were created in `tbl_permissions`
3. Check that the update form is submitting permission IDs
4. Review error logs for database errors
