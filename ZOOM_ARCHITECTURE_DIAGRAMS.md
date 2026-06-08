# ZOOM LIVE SERVICE - ARCHITECTURE & FLOW DIAGRAMS

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        FLUTTER MOBILE APP                       │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Every 5-10 seconds on Sunday evening:                  │  │
│  │  1. Call GET /api/zoom/live                             │  │
│  │  2. Parse response (status: live/offline)               │  │
│  │  3. If LIVE → Display "Join" button                     │  │
│  │  4. If OFFLINE → Display schedule message               │  │
│  └──────────────────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────────────────┘
                       │
                       │ HTTP/HTTPS
                       │
        ┌──────────────▼──────────────┐
        │   API Request Processing    │
        │                             │
        │  Routes.php                 │
        │  /api/zoom/live → Zoom.php  │
        └──────────────┬──────────────┘
                       │
        ┌──────────────▼──────────────────┐
        │  Zoom Controller (Zoom.php)     │
        │                                 │
        │  1. Get latest Zoom record      │
        │  2. Check current time          │
        │  3. Check if today is Sunday    │
        │  4. Return JSON (LIVE/OFFLINE)  │
        └──────────────┬──────────────────┘
                       │
        ┌──────────────▼──────────────────────┐
        │  ZoomVideo Model Logic              │
        │                                     │
        │  isServiceLive():                   │
        │  • Check day === Sunday             │
        │  • Check time >= start_time         │
        │  • Check time < end_time            │
        │  • Return true/false                │
        └──────────────┬──────────────────────┘
                       │
        ┌──────────────▼──────────────────────┐
        │  Database                           │
        │  zoom_videos table                  │
        │                                     │
        │  id | title | meeting_url |         │
        │  start_time | end_time |            │
        └─────────────────────────────────────┘
```

---

## 📱 Mobile App Flow

```
┌─────────────────────────────────────────┐
│    User Opens App (Sunday Evening)      │
└────────────────┬────────────────────────┘
                 │
                 ▼
    ┌─────────────────────────────┐
    │  Show "Checking service..."  │
    └────────────┬────────────────┘
                 │
                 ▼
    ┌──────────────────────────────────────┐
    │  Call GET /api/zoom/live             │
    │  Wait for response                   │
    └────────────┬─────────────────────────┘
                 │
        ┌────────┴──────────┐
        │                   │
        ▼                   ▼
    ┌────────┐         ┌──────────┐
    │ status │         │ status   │
    │ = live │         │= offline │
    └────┬───┘         └────┬─────┘
         │                  │
         ▼                  ▼
    ┌───────────────┐   ┌──────────────────┐
    │ Show:         │   │ Show:            │
    │ • Title       │   │ "Service offline" │
    │ • "Join" btn  │   │ "Next: Sunday 8PM"│
    │ • Timer       │   └──────────────────┘
    └───┬───────────┘
        │
        ▼
    ┌────────────────────────┐
    │ User taps "Join Zoom"  │
    └────────┬───────────────┘
             │
             ▼
    ┌───────────────────────────┐
    │ Open Zoom (meeting_url)   │
    │ Join meeting              │
    └───────────────────────────┘
```

---

## 🕐 Time-Based State Machine

```
                    Sunday 00:00
                        │
        ┌───────────────┴───────────────┐
        │                               │
        ▼                               ▼
    ┌────────────┐               ┌────────────┐
    │            │               │            │
    │  OFFLINE   │               │  OFFLINE   │
    │            │               │            │
    │00:00-20:00 │               │22:30-23:59 │
    │            │               │            │
    └────────────┘               └────────────┘
        ▲                               │
        │                               │
        │   Sunday 20:00 (8:00 PM)     │
        │   ╔═══════════════════╗      │
        │   ║                   ║      │
        │   ║   LIVE WINDOW     ║      │
        │   ║   (2.5 hours)     ║      │
        │   ║                   ║      │
        │   ╚═════════╤═════════╝      │
        │             │                │
        │         20:15 PM            │
        │             │                │
        │         22:15 PM            │
        │             │                │
        │        Sunday 22:30          │
        └─────────────┬────────────────┘
                      │
        Monday 00:00  │
            │         │
            ▼         ▼
        ┌────────────────┐
        │     OFFLINE    │
        │    (Mon-Sat)   │
        └────────────────┘
```

---

## 🔀 Live Detection Algorithm

```
START: Request to /api/zoom/live
│
├─ Get current server time (e.g., 2025-12-21 20:15:00)
│
├─ Get day of week (e.g., Sunday = 0)
│
├─ Check: Is today Sunday?
│  ├─ NO  → return OFFLINE ✗
│  └─ YES → Continue
│
├─ Get zoom_videos record from database
│
├─ Extract start_time (20:00:00) and end_time (22:30:00)
│
├─ Check: current_time >= start_time?
│  ├─ NO  → return OFFLINE ✗
│  └─ YES → Continue
│
├─ Check: current_time < end_time?
│  ├─ NO  → return OFFLINE ✗
│  └─ YES → Continue
│
└─ Return LIVE ✓ with meeting details
```

---

## 📊 Admin Management Flow

```
┌──────────────────────────────────┐
│   Admin User accesses /zoomadmin │
└──────────────┬───────────────────┘
               │
               ▼
    ┌─────────────────────────┐
    │  Check Authentication   │
    │                         │
    │  Auth Filter: 'auth'    │
    └────────┬────────────────┘
             │
      ┌──────┴──────┐
      │             │
      ▼             ▼
   ✓ Auth      ✗ Auth
     │           │
     ▼           ▼
  Continue    Redirect
   Display      to Login
   Form
      │
      ▼
┌───────────────────────────────────┐
│  Admin Form Displayed             │
│  - Title input                    │
│  - Meeting URL textarea           │
│  - Start time picker              │
│  - End time picker                │
│  - Save button                    │
└────────┬──────────────────────────┘
         │
         ▼ Admin fills form
┌────────────────────────────────────┐
│  Admin clicks "Save Changes"       │
└────────┬───────────────────────────┘
         │
         ▼
┌────────────────────────────────────┐
│  Form submitted to                 │
│  POST /zoomadmin/update            │
└────────┬───────────────────────────┘
         │
         ▼
┌────────────────────────────────────┐
│  Server validates input            │
│  • Check required fields           │
│  • Validate URL format             │
│  • Check time format               │
└────────┬───────────────────────────┘
         │
      ┌──┴──┐
      │     │
      ▼     ▼
   Valid  Invalid
     │       │
     ▼       ▼
  Update   Show Error
   DB      Message
     │
     ▼
Redirect to
/zoomadmin
with success
message
```

---

## 🗂️ File Dependencies

```
app/Config/Routes.php
    │
    ├─→ app/Controllers/Zoom.php
    │       │
    │       └─→ app/Models/ZoomVideo_model.php
    │               │
    │               └─→ Database: zoom_videos
    │
    └─→ app/Controllers/ZoomAdmin.php
            │
            ├─→ app/Models/ZoomVideo_model.php
            │
            └─→ app/Views/admin/zoom_settings.php
```

---

## 📈 Request Lifecycle

```
1. REQUEST ARRIVES
   ├─ URL: GET /api/zoom/live
   ├─ Headers: Accept: application/json
   └─ Body: (empty for GET)
   
2. ROUTING
   ├─ Router matches: Zoom::live
   ├─ No auth filter applied
   └─ Controller instantiated
   
3. CONTROLLER EXECUTION
   ├─ Hide Debugbar
   ├─ Try/Catch error handling
   ├─ Call model method
   └─ Prepare response array
   
4. MODEL EXECUTION
   ├─ Query DB for latest record
   ├─ Call isServiceLive()
   ├─ Check day and time
   └─ Return true/false
   
5. RESPONSE BUILDING
   ├─ Format JSON response
   ├─ Set HTTP 200 status
   ├─ Set Content-Type header
   └─ Return to client
   
6. RESPONSE DELIVERY
   ├─ No HTML rendering
   ├─ No Debugbar output
   └─ Pure JSON returned
```

---

## 🔐 Security Layers

```
┌─────────────────────────────────────────┐
│     API Request (Public)                │
│     GET /api/zoom/live                  │
│     No authentication required          │
└──────────┬──────────────────────────────┘
           │
           ├─ Input sanitization: ✓ (GET has no input)
           ├─ CORS (if needed): Configure in middleware
           ├─ Rate limiting: ✓ (Optional, add later)
           └─ HTTPS: ✓ Recommended for production
                     │
                     ▼
┌─────────────────────────────────────────┐
│     Admin Request (Protected)           │
│     GET /zoomadmin                      │
│     Auth filter required                │
└──────────┬──────────────────────────────┘
           │
           ├─ Authentication check: ✓
           ├─ Input validation: ✓
           ├─ CSRF token: ✓
           ├─ SQL injection prevention: ✓ (ORM)
           ├─ XSS prevention: ✓ (escape output)
           └─ URL tampering: ✓ (routing)
                     │
                     ▼
┌─────────────────────────────────────────┐
│     Database Access                     │
│     zoom_videos table                   │
└──────────┬──────────────────────────────┘
           │
           ├─ Parameterized queries: ✓ (ORM)
           ├─ User permissions: ✓ (DB user)
           ├─ Encryption: ✓ (HTTPS in transit)
           └─ Backup: ✓ (Regular backups)
```

---

## 🌍 Deployment Architecture

```
┌─────────────────────────────────────────────┐
│          PRODUCTION ENVIRONMENT             │
│                                             │
│  ┌───────────────────────────────────────┐ │
│  │    Web Server (Apache/Nginx)          │ │
│  │    Port: 443 (HTTPS)                  │ │
│  └───────────────┬───────────────────────┘ │
│                  │                          │
│  ┌───────────────▼───────────────────────┐ │
│  │    CodeIgniter 4 Application          │ │
│  │                                       │ │
│  │  ├─ app/                             │ │
│  │  │  ├─ Controllers/                  │ │
│  │  │  ├─ Models/                       │ │
│  │  │  ├─ Views/                        │ │
│  │  │  └─ Database/                     │ │
│  │  └─ writable/                        │ │
│  │     ├─ cache/                        │ │
│  │     └─ logs/                         │ │
│  └───────────────┬───────────────────────┘ │
│                  │                          │
│  ┌───────────────▼───────────────────────┐ │
│  │    MySQL/MariaDB Database             │ │
│  │    Separate user with limited perms   │ │
│  │                                       │ │
│  │    zoom_videos table                  │ │
│  └───────────────────────────────────────┘ │
│                                             │
│  ┌───────────────────────────────────────┐ │
│  │    Logging & Monitoring               │ │
│  │    - Error logs                       │ │
│  │    - Query logs                       │ │
│  │    - Access logs                      │ │
│  └───────────────────────────────────────┘ │
└─────────────────────────────────────────────┘
        │
        │ HTTPS (Port 443)
        │
    ┌───▼──────────────────────────┐
    │  Flutter Mobile Application  │
    │                              │
    │  Calls: GET /api/zoom/live  │
    │  Interval: 5-10 seconds      │
    └──────────────────────────────┘
```

---

## 📞 Integration Points

```
┌──────────────────────────────────────────────────────────────┐
│                  INTEGRATION POINTS                          │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  1. FLUTTER APP                                              │
│     └─→ HTTP Request to /api/zoom/live                       │
│         └─→ Parse JSON response                              │
│             └─→ Update UI accordingly                        │
│                                                               │
│  2. ADMIN DASHBOARD                                          │
│     └─→ GET /zoomadmin (Web form)                            │
│         └─→ POST updates                                     │
│             └─→ Stored in database                           │
│                                                               │
│  3. ZOOM APPLICATION                                         │
│     └─→ Not directly integrated                              │
│         └─→ URL served to mobile app                         │
│             └─→ User opens in Zoom app                       │
│                                                               │
│  4. MESSAGING SERVICE (Optional Future)                      │
│     └─→ Can query /api/zoom/status                           │
│         └─→ Send notifications when LIVE                     │
│                                                               │
│  5. ANALYTICS (Optional Future)                              │
│     └─→ Track API calls in logs                              │
│         └─→ Monitor usage patterns                           │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

## 🎯 Data Flow Diagram

```
Admin Updates Meeting
        │
        ▼
┌─────────────────────┐
│ Admin Dashboard     │
│ /zoomadmin/update   │
└────────┬────────────┘
         │
         ▼
┌─────────────────────┐
│ Validation          │
│ (title, URL, time)  │
└────────┬────────────┘
         │ ✓ Valid
         ▼
┌─────────────────────┐
│ Update Database     │
│ zoom_videos table   │
└────────┬────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ Data now in database:               │
│ title, meeting_url, start_time,     │
│ end_time, updated_at                │
└────────┬────────────────────────────┘
         │
         │ Every time mobile app calls...
         ▼
┌─────────────────────┐
│ Mobile App Request  │
│ /api/zoom/live      │
└────────┬────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Controller fetches record   │
│ getLatestZoom()             │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Check time & day            │
│ isServiceLive()             │
└────────┬────────────────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
LIVE?     OFFLINE?
    │         │
    ▼         ▼
Return   Return
Details  Message
```

---

**Architecture Version:** 1.0
**Last Updated:** December 21, 2025
**Status:** ✅ Complete and Production-Ready
