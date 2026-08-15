<?php

/**
 * Role-Based Permission Configuration
 * 
 * Maps roles to modules they can access
 */

return [
    // roleId => [permissions]
    1 => [ // Super Admin
        'members.view', 'members.edit',
        'donations.view', 'donations.edit',
        'media.view', 'media.edit',
        'publications.view', 'publications.edit',
        'connect.view', 'connect.edit',
        'events.view', 'events.edit',
        'hymns.view', 'hymns.edit',
        'messaging.view', 'messaging.edit',
        'locations.view', 'locations.edit',
        'settings.view', 'settings.edit',
        'counseling.view', 'counseling.edit',
        'membercare.view', 'membercare.edit',
        'marketplace.view', 'marketplace.edit',
        'partnership.view', 'partnership.edit',
        'admin.users.view', 'admin.users.edit',
        'admin.roles.view', 'admin.roles.edit',
    ],
    2 => [ // Admin
        'members.view', 'members.edit',
        'donations.view', 'donations.edit',
        'media.view', 'media.edit',
        'publications.view', 'publications.edit',
        'connect.view', 'connect.edit',
        'events.view', 'events.edit',
        'hymns.view', 'hymns.edit',
        'messaging.view', 'messaging.edit',
        'locations.view', 'locations.edit',
        'settings.view', 'settings.edit',
        'counseling.view', 'counseling.edit',
        'membercare.view', 'membercare.edit',
        'marketplace.view', 'marketplace.edit',
        'partnership.view', 'partnership.edit',
    ],
    3 => [ // Editor
        'members.view',
        'donations.view',
        'media.view', 'media.edit',
        'publications.view', 'publications.edit',
        'connect.view', 'connect.edit',
        'events.view', 'events.edit',
        'hymns.view', 'hymns.edit',
        'messaging.view',
        'locations.view',
        'counseling.view',
        'membercare.view',
        'marketplace.view', 'marketplace.edit',
        'partnership.view', 'partnership.edit',
    ],
    4 => [ // Viewer
        'members.view',
        'donations.view',
        'media.view',
        'publications.view',
        'connect.view',
        'events.view',
        'hymns.view',
        'messaging.view',
        'locations.view',
        'counseling.view',
        'membercare.view',
        'marketplace.view',
        'partnership.view',
    ],
    5 => [ // Contributor
        'media.view', 'media.edit',
        'publications.view', 'publications.edit',
        'connect.view', 'connect.edit',
        'events.view',
        'marketplace.view', 'marketplace.edit',
    ],
];
