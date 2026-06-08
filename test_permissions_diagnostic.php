<?php

/**
 * Permission System Diagnostic
 * 
 * This script helps verify that the permission system is working correctly.
 * Place this in your project root and access via: http://localhost:8080/test-permissions
 */

// This assumes CodeIgniter is set up and you can access the framework
// Comment this out if running standalone

$config = config('Permissions');

echo "<h1>Permission System Configuration</h1>";
echo "<p>Current Permission Configuration in <code>app/Config/Permissions.php</code>:</p>";

echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%;'>";
echo "<thead>";
echo "<tr>";
echo "<th>Role ID</th>";
echo "<th>Role Name</th>";
echo "<th>Permissions</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$roleNames = [
    1 => 'Super Admin',
    2 => 'Admin',
    3 => 'Editor',
    4 => 'Viewer',
    5 => 'Contributor',
];

foreach ($config as $roleId => $permissions) {
    echo "<tr>";
    echo "<td>" . $roleId . "</td>";
    echo "<td>" . ($roleNames[$roleId] ?? 'Unknown') . "</td>";
    echo "<td>";
    echo "<ul>";
    foreach ($permissions as $permission) {
        echo "<li>" . htmlspecialchars($permission) . "</li>";
    }
    echo "</ul>";
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

echo "<h2>Session Information</h2>";
$session = session();
echo "<p><strong>Current User RoleId:</strong> " . ($session->get('roleId') ?? 'Not Set') . "</p>";
echo "<p><strong>Current User ID:</strong> " . ($session->get('userId') ?? 'Not Set') . "</p>";
echo "<p><strong>Current User Name:</strong> " . ($session->get('name') ?? 'Not Set') . "</p>";
echo "<p><strong>Current User Status:</strong> " . ($session->get('status') ?? 'Not Set') . "</p>";

echo "<h2>Permission Check Test</h2>";

$testPermissions = [
    'members.view',
    'members.edit',
    'donations.view',
    'media.view',
    'media.edit',
    'publications.view',
    'publications.edit',
    'admin.users.view',
    'admin.roles.view',
];

echo "<p>Testing permissions for current user (roleId: " . $session->get('roleId') . "):</p>";
echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%;'>";
echo "<thead>";
echo "<tr>";
echo "<th>Permission</th>";
echo "<th>Has Permission?</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

foreach ($testPermissions as $permission) {
    $hasIt = hasPermission($permission);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($permission) . "</td>";
    echo "<td>" . ($hasIt ? '<span style="color:green;font-weight:bold;">✓ YES</span>' : '<span style="color:red;font-weight:bold;">✗ NO</span>') . "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

echo "<h2>Menu Visibility Test</h2>";
echo "<p>Based on current role and permissions, you should see:</p>";

$roleId = $session->get('roleId');
$expectedModules = [];

if ($roleId) {
    $rolePerms = $config[$roleId] ?? [];
    
    $moduleMap = [
        'members.view' => 'Members',
        'donations.view' => 'Donations',
        'media.view' => 'Media',
        'publications.view' => 'Publications',
        'connect.view' => 'Connect',
        'events.view' => 'Events',
        'hymns.view' => 'Hymns',
        'messaging.view' => 'Messaging',
        'locations.view' => 'Locations',
        'settings.view' => 'Settings',
        'admin.users.view' => 'Administration',
        'admin.roles.view' => 'Administration',
    ];
    
    echo "<ul>";
    echo "<li style='background-color:#ddd;padding:5px;margin:5px;'><strong>Dashboard</strong> (Always visible)</li>";
    
    // Check Members - requires status == 0
    if (in_array('members.view', $rolePerms) || in_array('members.edit', $rolePerms)) {
        $status = $session->get('status');
        $statusOk = $status == 0 ? '✓' : '✗ (requires status=0)';
        echo "<li style='padding:5px;margin:5px;background-color:" . ($status == 0 ? '#efe' : '#fee') . ";'><strong>Members</strong> $statusOk</li>";
    }
    
    // Check other modules
    foreach ($moduleMap as $perm => $module) {
        if ($perm !== 'members.view' && in_array($perm, $rolePerms)) {
            echo "<li style='padding:5px;margin:5px;background-color:#efe;'><strong>" . $module . "</strong> ✓</li>";
        }
    }
    
    echo "</ul>";
} else {
    echo "<p style='color:red;'><strong>ERROR:</strong> No roleId in session. User may not be logged in.</p>";
}

echo "<h2>Troubleshooting</h2>";
echo "<ul>";
echo "<li>If roleId is not set: User may not be logged in. Try logging out and back in.</li>";
echo "<li>If no permissions show: Check that roleId matches one of: 1, 2, 3, 4, or 5</li>";
echo "<li>If only Dashboard shows: Status may be != 0. Check tbl_admin_users.status field.</li>";
echo "<li>If you see incorrect permissions: Check app/Config/Permissions.php is correct</li>";
echo "</ul>";

?>
