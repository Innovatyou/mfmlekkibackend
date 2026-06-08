<?php
$mysqli = new mysqli("localhost", "root", "", "mfmdatabase");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->autocommit(false);

try {
    // Define roles
    $roles = [
        ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Full system access'],
        ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Admin panel access'],
        ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Content manager'],
        ['name' => 'editor', 'display_name' => 'Editor', 'description' => 'Content editor'],
        ['name' => 'viewer', 'display_name' => 'Viewer', 'description' => 'Content viewer'],
    ];

    $permissions = [
        // Users
        ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
        ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
        ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
        ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
        // Articles
        ['name' => 'articles.view', 'display_name' => 'View Articles', 'module' => 'articles'],
        ['name' => 'articles.create', 'display_name' => 'Create Articles', 'module' => 'articles'],
        ['name' => 'articles.edit', 'display_name' => 'Edit Articles', 'module' => 'articles'],
        ['name' => 'articles.delete', 'display_name' => 'Delete Articles', 'module' => 'articles'],
        // Videos
        ['name' => 'videos.view', 'display_name' => 'View Videos', 'module' => 'videos'],
        ['name' => 'videos.create', 'display_name' => 'Create Videos', 'module' => 'videos'],
        ['name' => 'videos.edit', 'display_name' => 'Edit Videos', 'module' => 'videos'],
        ['name' => 'videos.delete', 'display_name' => 'Delete Videos', 'module' => 'videos'],
        // Livestreams
        ['name' => 'livestreams.view', 'display_name' => 'View Livestreams', 'module' => 'livestreams'],
        ['name' => 'livestreams.create', 'display_name' => 'Create Livestreams', 'module' => 'livestreams'],
        ['name' => 'livestreams.edit', 'display_name' => 'Edit Livestreams', 'module' => 'livestreams'],
        ['name' => 'livestreams.delete', 'display_name' => 'Delete Livestreams', 'module' => 'livestreams'],
        // Settings
        ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings'],
        ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings'],
        // Roles
        ['name' => 'roles.view', 'display_name' => 'View Roles', 'module' => 'roles'],
        ['name' => 'roles.create', 'display_name' => 'Create Roles', 'module' => 'roles'],
        ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'module' => 'roles'],
        ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'module' => 'roles'],
    ];

    // Insert roles
    foreach ($roles as $role) {
        $name = $mysqli->real_escape_string($role['name']);
        $displayName = $mysqli->real_escape_string($role['display_name']);
        $description = $mysqli->real_escape_string($role['description']);
        $now = date('Y-m-d H:i:s');
        
        $sql = "INSERT IGNORE INTO tbl_roles (name, display_name, description, created_at, updated_at) 
                VALUES ('$name', '$displayName', '$description', '$now', '$now')";
        $mysqli->query($sql);
    }
    echo "✓ Inserted 5 roles\n";

    // Insert permissions
    foreach ($permissions as $permission) {
        $name = $mysqli->real_escape_string($permission['name']);
        $displayName = $mysqli->real_escape_string($permission['display_name']);
        $module = $mysqli->real_escape_string($permission['module']);
        $now = date('Y-m-d H:i:s');
        
        $sql = "INSERT IGNORE INTO tbl_permissions (name, display_name, module, created_at, updated_at) 
                VALUES ('$name', '$displayName', '$module', '$now', '$now')";
        $mysqli->query($sql);
    }
    echo "✓ Inserted 22 permissions\n";

    // Define role-permission mappings
    $rolePermissions = [
        'super_admin' => ['users.view', 'users.create', 'users.edit', 'users.delete',
                          'articles.view', 'articles.create', 'articles.edit', 'articles.delete',
                          'videos.view', 'videos.create', 'videos.edit', 'videos.delete',
                          'livestreams.view', 'livestreams.create', 'livestreams.edit', 'livestreams.delete',
                          'settings.view', 'settings.edit',
                          'roles.view', 'roles.create', 'roles.edit', 'roles.delete'],
        'admin' => ['users.view', 'users.create', 'users.edit',
                    'articles.view', 'articles.create', 'articles.edit',
                    'videos.view', 'videos.create', 'videos.edit',
                    'livestreams.view', 'livestreams.create', 'livestreams.edit',
                    'settings.view', 'settings.edit',
                    'roles.view'],
        'manager' => ['articles.view', 'articles.create', 'articles.edit',
                      'videos.view', 'videos.create', 'videos.edit',
                      'livestreams.view', 'livestreams.create', 'livestreams.edit'],
        'editor' => ['articles.view', 'articles.create', 'articles.edit',
                     'videos.view', 'videos.create', 'videos.edit'],
        'viewer' => ['articles.view', 'videos.view', 'livestreams.view'],
    ];

    // Get role and permission IDs and create mappings
    $result = $mysqli->query("SELECT id, name FROM tbl_roles");
    $roleIds = [];
    while ($row = $result->fetch_assoc()) {
        $roleIds[$row['name']] = $row['id'];
    }

    $result = $mysqli->query("SELECT id, name FROM tbl_permissions");
    $permissionIds = [];
    while ($row = $result->fetch_assoc()) {
        $permissionIds[$row['name']] = $row['id'];
    }

    // Insert role-permission mappings
    $totalMappings = 0;
    foreach ($rolePermissions as $roleName => $permissionNames) {
        $roleId = $roleIds[$roleName];
        foreach ($permissionNames as $permissionName) {
            $permissionId = $permissionIds[$permissionName];
            $now = date('Y-m-d H:i:s');
            $sql = "INSERT IGNORE INTO tbl_role_permissions (role_id, permission_id, created_at) 
                    VALUES ($roleId, $permissionId, '$now')";
            $mysqli->query($sql);
            $totalMappings++;
        }
    }
    echo "✓ Inserted $totalMappings role-permission mappings\n";

    $mysqli->commit();
    echo "\n✓ RBAC seeding completed successfully in mfmdatabase!\n";

} catch (Exception $e) {
    $mysqli->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}

$mysqli->close();
?>
