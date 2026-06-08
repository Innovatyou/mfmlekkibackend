# Header.php Menu Structure - Verification

## Current Structure (FIXED)

### Location: `app/Views/templates/header.php` Lines 80-202

```
Line 80:  <ul id="accordion-menu">
Line 81:    <li> Dashboard (always visible)
Line 82:      <a href="/dashboard">
Line 83:        Dashboard
Line 84:      </a>
Line 85:    </li>

Line 86:    <?php if (hasPermission('members.view') || hasPermission('members.edit') || isSuperAdmin()): ?>
Line 87:      <?php if ($session->get('status') == 0) { ?>
Line 88:        <li> Members (permission + status=0 required)
Line 89:          <a href="javascript:;" class="dropdown-toggle">
Line 90:            Members
Line 91:          </a>
Line 92:          <ul class="submenu">
Line 93:            <li>All Members</li>
Line 94:            <li>Email/SMS List</li>
Line 95:          </ul>
Line 96:        </li>
Line 97:      <?php } ?>        ← Closes status check
Line 98:    <?php endif; ?>     ← Closes permission check

Line 99:    <?php if (hasPermission('donations.view') || hasPermission('donations.edit') || isSuperAdmin()): ?>
Line 100:     <li> Donations (permission only, no status check)
Line 101:       <a href="/donations">
Line 102:         Donations
Line 103:       </a>
Line 104:     </li>
Line 105:   <?php endif; ?>     ← Closes permission check

Line 106:   <?php if (hasPermission('media.view') || hasPermission('media.edit') || isSuperAdmin()): ?>
Line 107:     <li> Media (permission only)
Line 108:       ... Media submenu ...
Line 109:     </li>
Line 120:   <?php endif; ?>

Line 121:   <?php if (hasPermission('publications.view') || hasPermission('publications.edit') || isSuperAdmin()): ?>
Line 122:     <li> Publications (permission only)
Line 123:       ... Publications submenu ...
Line 124:     </li>
Line 132:   <?php endif; ?>

Line 133:   <?php if (hasPermission('connect.view') || hasPermission('connect.edit') || isSuperAdmin()): ?>
Line 134:     <li> Connect (permission only)
Line 135:       ... Connect submenu ...
Line 136:     </li>
Line 144:   <?php endif; ?>

Line 145:   <?php if (hasPermission('events.view') || hasPermission('events.edit') || isSuperAdmin()): ?>
Line 146:     <li> Events (permission only)
Line 147:       <a href="/eventsListing">Events</a>
Line 148:     </li>
Line 151:   <?php endif; ?>

Line 152:   <?php if (hasPermission('hymns.view') || hasPermission('hymns.edit') || isSuperAdmin()): ?>
Line 153:     <li> Hymns (permission only)
Line 154:       <a href="/hymnsListing">Hymns</a>
Line 155:     </li>
Line 158:   <?php endif; ?>

Line 159:   <?php if (hasPermission('messaging.view') || hasPermission('messaging.edit') || isSuperAdmin()): ?>
Line 160:     <li> Messaging (permission only)
Line 161:       ... Messaging submenu ...
Line 162:     </li>
Line 169:   <?php endif; ?>

Line 170:   <?php if (hasPermission('locations.view') || hasPermission('locations.edit') || isSuperAdmin()): ?>
Line 171:     <li> Locations (permission only)
Line 172:       <a href="/branchesListing">Locations</a>
Line 173:     </li>
Line 176:   <?php endif; ?>

Line 177:   <?php if (hasPermission('settings.view') || hasPermission('settings.edit') || isSuperAdmin()): ?>
Line 178:     <li> Settings (permission only, Super Admin only in config)
Line 179:       <a href="/settings">Settings</a>
Line 180:     </li>
Line 183:   <?php endif; ?>

Line 184:   <?php if (hasPermission('admin.users.view') || hasPermission('admin.roles.view') || isSuperAdmin()): ?>
Line 185:     <li> Administration (permission only, Super Admin only in config)
Line 186:       <a href="javascript:;" class="dropdown-toggle">
Line 187:         Administration
Line 188:       </a>
Line 189:       <ul class="submenu">
Line 190:         <li>Admin Users</li>
Line 191:         <li>User Roles</li>
Line 192:         <li>Legacy Admin List (Super Admin only)</li>
Line 193:       </ul>
Line 194:     </li>
Line 198:   <?php endif; ?>

Line 199:   </ul>         ← Closes menu
Line 200: </div>
Line 201: </div>
```

## Key Structural Elements

### 1. Dashboard (Always Visible)
```php
<li>
    <a href="<?php echo base_url(); ?>/dashboard">
        <span class="micon dw dw-home"></span><span class="mtext">Dashboard</span>
    </a>
</li>
```
- No permission check
- Always shows for all logged-in users

### 2. Members (Permission + Status Check)
```php
<?php if (hasPermission('members.view') || hasPermission('members.edit') || isSuperAdmin()): ?>
<?php if ($session->get('status') == 0) { ?>
    <li class="dropdown">
        <!-- Members menu content -->
    </li>
<?php } ?>
<?php endif; ?>
```
- Requires: permission AND status == 0
- Status check is member-specific (legacy behavior preserved)

### 3. All Other Modules (Permission Check Only)
```php
<?php if (hasPermission('donations.view') || hasPermission('donations.edit') || isSuperAdmin()): ?>
    <li>
        <!-- Module menu content -->
    </li>
<?php endif; ?>
```
- Requires: permission only
- No status check (applies to admin modules only)

## Permission Requirements per Module

### Members
- `members.view` OR `members.edit` OR super admin
- PLUS: `status == 0`

### Donations
- `donations.view` OR `donations.edit` OR super admin

### Media
- `media.view` OR `media.edit` OR super admin

### Publications
- `publications.view` OR `publications.edit` OR super admin

### Connect
- `connect.view` OR `connect.edit` OR super admin

### Events
- `events.view` OR `events.edit` OR super admin

### Hymns
- `hymns.view` OR `hymns.edit` OR super admin

### Messaging
- `messaging.view` OR `messaging.edit` OR super admin

### Locations
- `locations.view` OR `locations.edit` OR super admin

### Settings
- `settings.view` OR `settings.edit` OR super admin
- Note: Only Super Admin (roleId=1) has these in Permissions.php

### Administration
- `admin.users.view` OR `admin.roles.view` OR super admin
- Note: Only Super Admin (roleId=1) has these in Permissions.php

## Critical Fixes Applied

### Before (BROKEN)
```php
<?php if ($session->get('status') == 0) { ?>
    <!-- ALL MENUS INSIDE STATUS CHECK -->
    <?php if (hasPermission('members.view')) ?>Members<?php endif; ?>
    <?php if (hasPermission('donations.view')) ?>Donations<?php endif; ?>
    ... (all other modules)
<?php } ?>
```
**Problem**: All menus blocked if status != 0

### After (FIXED)
```php
<?php if (hasPermission('members.view')) ?>
    <?php if ($session->get('status') == 0) { ?>
        Members
    <?php } ?>
<?php endif; ?>

<?php if (hasPermission('donations.view')) ?>
    Donations
<?php endif; ?>
... (all other modules with permission only)
```
**Solution**: Status check only for Members, other modules use permission only

## Verification Checklist

- ✅ Dashboard always visible (no permission check)
- ✅ Members wrapped in both permission AND status checks
- ✅ All other modules wrapped in permission checks ONLY
- ✅ Each module has individual closing `<?php endif; ?>` or `<?php } ?>`
- ✅ No outer wrapper blocking all menus
- ✅ No orphaned closing tags
- ✅ Proper indentation maintained
- ✅ All syntax valid (no PHP errors)

## Expected Behavior

Users should now see:

| Role | Visible Modules |
|------|-----------------|
| Super Admin (1) | All modules (if config allows + status=0 for Members) |
| Admin (2) | Members, Donations, Media, Publications, Connect, Events, Hymns, Messaging, Locations, Settings |
| Editor (3) | Members, Donations, Media, Publications, Connect, Events, Hymns, Messaging, Locations |
| Viewer (4) | Members, Donations, Media, Publications, Connect, Events, Hymns, Messaging, Locations |
| Contributor (5) | Media, Publications, Connect, Events |

The permission system is now fully functional and structural issues have been resolved.
