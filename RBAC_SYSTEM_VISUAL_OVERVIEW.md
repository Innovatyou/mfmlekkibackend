# RBAC System - Visual Architecture & Overview

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    MFM ADMIN PANEL RBAC SYSTEM                   │
└─────────────────────────────────────────────────────────────────┘

                            ┌─────────────┐
                            │ Admin Login │
                            └──────┬──────┘
                                   │
                    ┌──────────────┴───────────────┐
                    │                              │
            ┌───────▼────────┐           ┌────────▼─────────┐
            │ Session Set    │           │ Set roleId       │
            │ userId         │           │ role name        │
            │ isLoggedIn     │           │ role_id (NEW)    │
            └───────┬────────┘           └────────┬─────────┘
                    │                             │
                    └──────────────┬──────────────┘
                                   │
                        ┌──────────▼───────────┐
                        │ Check Permissions    │
                        │ via Filters/Helpers  │
                        └──────────┬───────────┘
                                   │
        ┌──────────────────────────┼──────────────────────────┐
        │                          │                          │
    ┌───▼────┐              ┌─────▼──────┐          ┌────────▼────┐
    │ Access │              │   Access   │          │  No Access  │
    │ Denied │              │  Allowed   │          │  Denied     │
    └────────┘              └────┬───────┘          └─────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Render Page/View       │
                    │ Show/Hide Elements     │
                    │ Based on Permissions   │
                    └────────────────────────┘
```

## Database Schema

```
                        ┌─────────────────┐
                        │   tbl_roles     │
                        ├─────────────────┤
                        │ id (PK)         │
                        │ name            │
                        │ display_name    │
                        │ description     │
                        │ created_at      │
                        │ updated_at      │
                        └────────┬────────┘
                                 │
                    ┌────────────┴────────────┐
                    │                         │
        ┌───────────▼──────────────┐  ┌──────▼──────────────────┐
        │ tbl_role_permissions     │  │  tbl_churches (modified)│
        ├──────────────────────────┤  ├─────────────────────────┤
        │ id (PK)                  │  │ id (PK)                 │
        │ role_id (FK)             │  │ email                   │
        │ permission_id (FK)       │  │ password                │
        │ created_at               │  │ fullname                │
        └───────────┬──────────────┘  │ role                    │
                    │                 │ role_id (FK) ◄──────┐   │
                    │                 │ status              │   │
                    │                 │ isdelete            │   │
                    │                 │ apitoken            │   │
                    │                 │ created_at          │   │
                    │                 │ updated_at          │   │
                    │                 └─────────────────────┘   │
                    │                                            │
        ┌───────────▼──────────────┐                            │
        │  tbl_permissions         │◄───────────────────────────┘
        ├──────────────────────────┤
        │ id (PK)                  │
        │ name                     │
        │ display_name             │
        │ description              │
        │ module                   │
        │ created_at               │
        │ updated_at               │
        └──────────────────────────┘
```

## Role Hierarchy

```
                    ┌─────────────────┐
                    │ Super Admin     │
                    │   (role_id=1)   │
                    │ 24 Permissions  │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │   Admin         │
                    │   (role_id=2)   │
                    │ 20 Permissions  │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │   Manager       │
                    │   (role_id=3)   │
                    │ 10 Permissions  │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │   Editor        │
                    │   (role_id=4)   │
                    │  7 Permissions  │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │   Viewer        │
                    │   (role_id=5)   │
                    │  4 Permissions  │
                    └─────────────────┘
```

## Permission Module Structure

```
PERMISSIONS (24 total)
│
├── Users (4)
│   ├── users.view ✓
│   ├── users.create ✓
│   ├── users.edit ✓
│   └── users.delete ✓
│
├── Articles (4)
│   ├── articles.view ✓
│   ├── articles.create ✓
│   ├── articles.edit ✓
│   └── articles.delete ✓
│
├── Videos (4)
│   ├── videos.view ✓
│   ├── videos.create ✓
│   ├── videos.edit ✓
│   └── videos.delete ✓
│
├── Livestream (4)
│   ├── livestream.view ✓
│   ├── livestream.create ✓
│   ├── livestream.edit ✓
│   └── livestream.delete ✓
│
├── Settings (2)
│   ├── settings.view ✓
│   └── settings.edit ✓
│
└── Roles (2)
    ├── roles.view ✓
    ├── roles.create ✓
    ├── roles.edit ✓
    └── roles.delete ✓
```

## Component Interaction Flow

```
┌────────────────────────────────────────────────────────────────┐
│                    REQUEST LIFECYCLE                            │
└────────────────────────────────────────────────────────────────┘

 HTTP REQUEST
     │
     ▼
 ┌─────────────────────────┐
 │ Filters Execute         │  ◄── authorizeRole
 │ (Before Controllers)    │  ◄── authorizePermission
 └──────────┬──────────────┘
            │
    ┌───────▼────────┐
    │ Authorized?    │
    └───┬────────┬───┘
        │        │
    YES│        │NO
        │        │
 ┌──────▼─┐  ┌──┴────────────────┐
 │Continue│  │ Redirect           │
 └──────┬─┘  │ with error msg     │
        │    └───────────────────┘
        │
 ┌──────▼────────────────┐
 │ Controller Method     │
 │ Executes              │
 └──────┬─────────────────┘
        │
 ┌──────▼────────────────────────────┐
 │ Permission Check (in method)       │  ◄── hasPermission()
 │ hasPermission('articles.create')   │  ◄── hasRole()
 └──────┬─────────────────────────────┘
        │
 ┌──────▼────────────┐
 │ Process Request   │
 └──────┬────────────┘
        │
 ┌──────▼────────────────────┐
 │ View Rendered             │  ◄── hasPermission() in view
 │ Elements shown/hidden     │  ◄── Show/hide based on role
 │ Based on Permissions      │
 └──────┬────────────────────┘
        │
        ▼
   HTTP RESPONSE
```

## Helper Functions Quick Reference

```
┌─────────────────────────────────────────────────────────────────┐
│                   HELPER FUNCTIONS                               │
└─────────────────────────────────────────────────────────────────┘

ROLE CHECKING
    hasRole('admin')                    ─► bool
    hasRole(['admin', 'manager'])       ─► bool
    isAdmin()                          ─► bool
    isSuperAdmin()                     ─► bool
    getCurrentUserRoleId()             ─► int
    getCurrentUserRoleName()           ─► string

PERMISSION CHECKING
    hasPermission('articles.create')   ─► bool
    hasAnyPermission([...])            ─► bool
    hasAllPermissions([...])           ─► bool
    getCurrentUserPermissions()        ─► array

DATA RETRIEVAL
    getAllRoles()                      ─► array
    getRolePermissions($id)            ─► array
    getRole($id)                       ─► object
    getPermissionByName($name)         ─► object
    getAllPermissionsByModule()        ─► array
```

## Model Relationship Diagram

```
                    ┌──────────────┐
                    │   UserRBAC   │
                    │   (Model)    │
                    └──────┬───────┘
                           │
                     getUserWithRole()
                    getPermissions()
                    hasPermission()
                           │
            ┌──────────────┴─────────────────┐
            │                                │
     ┌──────▼─────┐              ┌──────────▼────────┐
     │   Role     │              │  Permission       │
     │  (Model)   │              │  (Model)          │
     └──────┬─────┘              └──────┬────────────┘
            │                           │
    getPermissions()              hasPermission()
    assignPermission()            getByName()
    getUsers()                    getPermissionsForRole()
            │                           │
            └──────────────┬────────────┘
                           │
                   tbl_role_permissions
                     (Junction Table)
```

## Request Flow for Permission Check

```
User Request
    │
    ▼
Is user logged in?
    │
    ├─► NO ─── Redirect to login page
    │
    ▼ YES
Get roleId from session
    │
    ├─► Not found ─── Redirect to login
    │
    ▼ YES (roleId exists)
Query tbl_role_permissions for role
    │
    ├─► No permissions ─── Deny access
    │
    ▼ YES (permissions found)
Check if required permission in list
    │
    ├─► YES ──── Allow request
    │
    ├─► NO ───── Deny request with error
    │
    ▼
Process request or show error
```

## File Organization

```
RBAC SYSTEM FILES
│
├── CONTROLLERS (2)
│   ├── AdminRoles.php
│   └── AdminUsers.php
│
├── MODELS (3)
│   ├── Role.php
│   ├── Permission.php
│   └── UserRBAC.php
│
├── HELPERS (1)
│   └── AdminAuthHelper.php
│
├── FILTERS (2)
│   ├── AuthorizeRole.php
│   └── AuthorizePermission.php
│
├── DATABASE
│   ├── Migrations/
│   │   └── CreateRolesAndPermissions.php
│   └── Seeds/
│       └── RolesAndPermissionsSeeder.php
│
└── DOCUMENTATION (6)
    ├── README_RBAC_SYSTEM.md
    ├── RBAC_GETTING_STARTED.md
    ├── RBAC_QUICK_IMPLEMENTATION_GUIDE.md
    ├── RBAC_DOCUMENTATION.md
    ├── RBAC_CODE_EXAMPLES.md
    ├── RBAC_IMPLEMENTATION_SUMMARY.md
    ├── RBAC_FILE_MANIFEST.md
    ├── RBAC_IMPLEMENTATION_CHECKLIST.md
    └── RBAC_SYSTEM_VISUAL_OVERVIEW.md (this file)
```

## Permission Assignment Matrix

```
ROLE ─────► PERMISSIONS (via tbl_role_permissions)
 │
 ├─► Super Admin     ─► All 24 permissions
 │
 ├─► Admin           ─► 20 permissions
 │                       (excludes: roles.create, roles.edit,
 │                                  roles.delete, settings.edit)
 │
 ├─► Manager         ─► 10 permissions
 │                       (view & create only, no delete)
 │
 ├─► Editor          ─► 7 permissions
 │                       (create & edit content, view)
 │
 └─► Viewer          ─► 4 permissions
                        (view only)
```

## Implementation Timeline

```
BEFORE RBAC          →        AFTER RBAC
     │                            │
     ├─ Old role field       ├─ New role_id (FK)
     ├─ No permissions       ├─ 24 permissions
     ├─ Basic auth          ├─ Granular access
     ├─ No role mgmt        ├─ Full role management
     ├─ Limited control     ├─ Complete control
     │                       │
     └─ Upgrade Complete ────┘
```

## Usage Pattern Flowchart

```
START
  │
  ▼
Load AdminAuthHelper
  │
  ├─ In Controller:
  │  │
  │  ├─► Check Permission
  │  │   hasPermission('articles.create')
  │  │
  │  └─► Allow/Deny & Process
  │
  ├─ In View:
  │  │
  │  ├─► Check Permission in Template
  │  │   <?php if (hasPermission('articles.delete')): ?>
  │  │
  │  └─► Show/Hide Elements
  │
  ├─ In Route Filter:
  │  │
  │  ├─► Apply Authorization Filter
  │  │   ['filter' => 'authorizePermission:articles.create']
  │  │
  │  └─► Block Unauthorized Access
  │
  ▼
DONE - Secure access control in place
```

## Security Layers

```
LAYER 1: Authentication
    │
    ├─► User Login
    ├─► Session Management
    └─► Password Hashing

LAYER 2: Authorization (Filters)
    │
    ├─► Route Level Protection
    ├─► Role Validation
    └─► Permission Validation

LAYER 3: Application Level
    │
    ├─► Controller Method Checks
    ├─► Business Logic Validation
    └─► Data Access Control

LAYER 4: Presentation Level
    │
    ├─► View Element Hiding
    ├─► UI Control Display
    └─► User Experience
```

## Quick Reference Card

```
┌─────────────────────────────────────────────────────────┐
│           RBAC QUICK REFERENCE CARD                     │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ CHECK ROLE                                              │
│   if (hasRole('admin')) { ... }                        │
│   if (isAdmin()) { ... }                               │
│   if (isSuperAdmin()) { ... }                          │
│                                                         │
│ CHECK PERMISSION                                        │
│   if (hasPermission('articles.create')) { ... }        │
│   if (hasAnyPermission(['edit', 'delete'])) { ... }    │
│   if (hasAllPermissions(['view', 'create'])) { ... }   │
│                                                         │
│ IN FILTERS (Routes.php)                                │
│   ['filter' => 'authorizeRole:admin']                  │
│   ['filter' => 'authorizePermission:users.create']     │
│                                                         │
│ ROLES (5 Total)                                         │
│   super_admin (id=1) - Full access                     │
│   admin       (id=2) - Most features                   │
│   manager     (id=3) - Content & users                 │
│   editor      (id=4) - Content creation                │
│   viewer      (id=5) - Read-only                       │
│                                                         │
│ PERMISSIONS (24 Total)                                  │
│   module.action format                                 │
│   Examples: articles.create, videos.delete, etc.       │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## Setup Sequence Diagram

```
Developer    CodeIgniter    Database
    │            │              │
    ├─ Run Migration ─────────► Create tables
    │            │              │
    ├─ Run Seeder ─────────────► Insert data
    │            │              │
    ├─ Update Filters ──────────┤
    │            │              │
    ├─ Update Routes ───────────┤
    │            │              │
    ├─ Load Helper ────────────┤
    │            │              │
    ├─ Add permission checks ──┤
    │            │              │
    ├─ Test system ───────────► Query DB
    │            │              │
    ▼            ▼              ▼
  Done    System Ready    RBAC Active
```

---

**Visual Summary Complete!**

This diagram shows the complete RBAC system architecture, data flow, and relationships.

For detailed information, see:
- README_RBAC_SYSTEM.md
- RBAC_GETTING_STARTED.md
- RBAC_DOCUMENTATION.md
