# Zoom Live Service Backend - Complete Implementation Guide

## Overview
This is a lightweight CodeIgniter 4 backend module that enables a Flutter mobile app to fetch live Zoom church service details. The system automatically determines LIVE/OFFLINE status based on server time, without requiring any Zoom SDK or API integration.

---

## ✅ Database Table

### Table: `zoom_videos`

```sql
CREATE TABLE zoom_videos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  meeting_url TEXT NOT NULL,
  start_time TIME NOT NULL DEFAULT '20:00:00',
  end_time TIME NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    ON UPDATE CURRENT_TIMESTAMP
);
```

### Alternative: Direct SQL Insert

```sql
INSERT INTO zoom_videos (title, meeting_url, start_time, end_time)
VALUES (
  'SUNDAY NIGHT PRAYER MEETING',
  'https://us06web.zoom.us/j/4133262470?pwd=ajNyT05YTnhzVWtqa1JEKzhpczQ4dz09',
  '20:00:00',
  '22:30:00'
);
```

---

## 📁 Files Created/Modified

### 1. Migration Files

#### `app/Database/Migrations/2025-12-21-000001_CreateZoomVideosTable.php`
- Creates the `zoom_videos` table
- Defines all columns with proper types and constraints
- Run: `php spark migrate`

#### `app/Database/Migrations/2025-12-21-000002_SeedZoomVideosSampleData.php`
- Seeds sample Zoom meeting data
- Provides default title and meeting URL
- Run after table migration

### 2. Model

#### `app/Models/ZoomVideo_model.php`
- **Key Methods:**
  - `getLatestZoom()` - Fetches the most recent Zoom record
  - `isServiceLive($zoom)` - Determines if service is LIVE based on time & day
  - `updateZoomDetails($data)` - Updates Zoom meeting information (admin)
  - `getAdminData()` - Returns data for admin panel

- **Time Logic:**
  - Checks if current day is Sunday (0 in PHP's date('w'))
  - Checks if current time is within start_time and end_time
  - Default window: 20:00 (8:00 PM) to 22:30 (10:30 PM)
  - Always returns true/false, never stores is_live in DB

### 3. Controllers

#### `app/Controllers/Zoom.php` (Mobile App API)
**Purpose:** Provides JSON endpoints for the Flutter mobile app

**Endpoints:**

##### `GET /api/zoom/live`
Returns current live service status and meeting details

**Response when LIVE:**
```json
{
  "status": "live",
  "data": {
    "title": "SUNDAY NIGHT PRAYER MEETING",
    "platform": "zoom",
    "meeting_url": "https://us06web.zoom.us/j/4133262470?pwd=ajNyT05YTnhzVWtqa1JEKzhpczQ4dz09",
    "start_time": "2025-12-21 20:00:00"
  }
}
```

**Response when OFFLINE:**
```json
{
  "status": "offline",
  "message": "Zoom service holds every Sunday by 8:00 PM"
}
```

**HTTP Status:** Always 200 OK

##### `GET /api/zoom/schedule`
Returns schedule information only (meeting day and times)

**Response:**
```json
{
  "status": "success",
  "data": {
    "day": "Sunday",
    "start_time": "20:00:00",
    "end_time": "22:30:00",
    "title": "SUNDAY NIGHT PRAYER MEETING"
  }
}
```

#### `app/Controllers/ZoomAdmin.php` (Admin Dashboard)
**Purpose:** Provides admin interface and methods to manage Zoom settings

**Endpoints:**

##### `GET /zoomadmin`
Admin dashboard page to manage Zoom settings
- Requires auth filter (modify as needed)
- Displays form with current Zoom details
- Allows updating title, URL, and times

##### `POST /zoomadmin/update`
Updates Zoom details via form submission
- Validates input (required fields, URL format)
- Updates existing record or creates new one
- Redirects with success/error message

##### `POST /api/zoomadmin/update`
JSON endpoint for programmatic updates
- Accepts JSON payload
- Returns JSON response
- Useful for dashboard widgets

**Request Example:**
```json
{
  "title": "SUNDAY SERVICE",
  "meeting_url": "https://us06web.zoom.us/j/...",
  "start_time": "20:00:00",
  "end_time": "22:30:00"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Zoom meeting details updated",
  "data": { ... }
}
```

##### `GET /api/zoom/status` (Debug)
Returns detailed status information
- Current time, day, and meeting status
- All Zoom database values
- Requires auth filter

---

## 🚀 Routes

### In `app/Config/Routes.php`:

```php
// Public API routes (Mobile App)
$routes->get('api/zoom/live', 'Zoom::live');
$routes->get('api/zoom/schedule', 'Zoom::schedule');

// Admin routes (Protected with auth filter)
$routes->get('zoomadmin', 'ZoomAdmin::index', ['filter' => 'auth']);
$routes->post('zoomadmin/update', 'ZoomAdmin::updateZoom', ['filter' => 'auth']);
$routes->post('api/zoomadmin/update', 'ZoomAdmin::updateZoomJson', ['filter' => 'auth']);
$routes->get('api/zoom/status', 'ZoomAdmin::status', ['filter' => 'auth']);
```

---

## ⚙️ Setup Instructions

### Step 1: Run Migrations
```bash
cd c:\xampp\htdocs\churchapp
php spark migrate
```

This will:
- Create `zoom_videos` table
- Insert sample Zoom meeting data

### Step 2: Verify Database
```sql
SELECT * FROM zoom_videos;
```

Expected output:
| id | title | meeting_url | start_time | end_time |
|---|---|---|---|---|
| 1 | SUNDAY NIGHT PRAYER MEETING | https://us06web.zoom.us/j/... | 20:00:00 | 22:30:00 |

### Step 3: Test API Endpoint
```
GET http://localhost/churchapp/api/zoom/live
```

### Step 4: Access Admin Panel
```
GET http://localhost/churchapp/zoomadmin
```

---

## 🔐 Security Considerations

✅ **Do NOT hardcode Zoom URLs** in the Flutter app - fetch from API
✅ **Always load URLs dynamically** from this backend
✅ **Keep meeting URLs secure** - don't expose in error messages
✅ **Enable Zoom Waiting Room** - members must be admitted by host
✅ **Disable screen sharing** if needed (Zoom settings)
✅ **Use strong meeting passwords** - included in meeting URL
✅ **Admin routes require authentication** - add proper auth filters
✅ **API returns JSON only** - no HTML or Debugbar output

---

## 📱 Flutter Integration Example

```dart
import 'package:http/http.dart' as http;

class ZoomService {
  final String apiUrl = 'https://yourdomain.com/churchapp';

  Future<Map<String, dynamic>> getLiveZoomStatus() async {
    try {
      final response = await http.get(
        Uri.parse('$apiUrl/api/zoom/live'),
      );

      if (response.statusCode == 200) {
        return json.decode(response.body);
      }
      return {'status': 'offline'};
    } catch (e) {
      print('Error: $e');
      return {'status': 'offline'};
    }
  }

  Future<void> joinZoomMeeting(String meetingUrl) async {
    if (await canLaunch(meetingUrl)) {
      await launch(meetingUrl);
    } else {
      throw 'Could not launch $meetingUrl';
    }
  }
}

// Usage in Flutter
void checkAndJoinZoom() async {
  final status = await zoomService.getLiveZoomStatus();
  
  if (status['status'] == 'live') {
    await zoomService.joinZoomMeeting(status['data']['meeting_url']);
  } else {
    // Show offline message
    showMessage('Zoom service will be available every Sunday at 8:00 PM');
  }
}
```

---

## 🕐 Time-Based Logic Explanation

### How LIVE Status is Determined

1. **Get current server time** (UTC or server timezone)
2. **Check if today is Sunday** (PHP `date('w') == 0`)
3. **Check if current time >= start_time AND < end_time**
4. **Return true if all conditions met, false otherwise**

### Default Schedule
- **Day:** Every Sunday
- **Start:** 20:00:00 (8:00 PM)
- **End:** 22:30:00 (10:30 PM)
- **Duration:** 2 hours 30 minutes

### Example Timeline
```
Saturday 23:59 → OFFLINE (not Sunday)
Sunday 07:59 AM → OFFLINE (before 8 PM)
Sunday 19:59 PM → OFFLINE (still before 8 PM)
Sunday 20:00 PM → LIVE ✅
Sunday 21:00 PM → LIVE ✅
Sunday 22:29 PM → LIVE ✅
Sunday 22:30 PM → OFFLINE (end time reached)
Monday 20:00 PM → OFFLINE (not Sunday)
```

---

## 🛠️ Admin Management

### Update Zoom Details

**Via Web Form:**
1. Navigate to `/zoomadmin`
2. Update fields: Title, Meeting URL, Start Time, End Time
3. Click "Save Changes"

**Via JSON API:**
```bash
curl -X POST http://localhost/churchapp/api/zoomadmin/update \
  -H "Content-Type: application/json" \
  -d '{
    "title": "SUNDAY PRAYER SERVICE",
    "meeting_url": "https://us06web.zoom.us/j/new_meeting_id?pwd=...",
    "start_time": "19:30:00",
    "end_time": "21:30:00"
  }'
```

### Check Current Status

```bash
curl http://localhost/churchapp/api/zoom/status
```

Response:
```json
{
  "status": "LIVE",
  "is_live": true,
  "current_time": "2025-12-21 20:15:30",
  "current_day": "Sunday",
  "zoom_data": { ... }
}
```

---

## 📊 Response Examples

### Scenario 1: Sunday at 8:15 PM
```
Current Time: 2025-12-21 20:15:00 (Sunday)
GET /api/zoom/live

Response:
{
  "status": "live",
  "data": {
    "title": "SUNDAY NIGHT PRAYER MEETING",
    "platform": "zoom",
    "meeting_url": "https://us06web.zoom.us/j/4133262470?pwd=...",
    "start_time": "2025-12-21 20:00:00"
  }
}
```

### Scenario 2: Tuesday at 8:00 PM
```
Current Time: 2025-12-23 20:00:00 (Tuesday)
GET /api/zoom/live

Response:
{
  "status": "offline",
  "message": "Zoom service holds every Sunday by 8:00 PM"
}
```

### Scenario 3: Sunday at 10:45 PM
```
Current Time: 2025-12-21 22:45:00 (Sunday)
GET /api/zoom/live

Response:
{
  "status": "offline",
  "message": "Zoom service holds every Sunday by 8:00 PM"
}
```

---

## 🧪 Testing

### Using Postman

1. **Import API Endpoints:**
   - `GET http://localhost/churchapp/api/zoom/live`
   - `GET http://localhost/churchapp/api/zoom/schedule`
   - `GET http://localhost/churchapp/api/zoom/status`

2. **Test Scenarios:**
   - Test on Sunday during meeting hours → should return LIVE
   - Test on weekdays → should return OFFLINE
   - Test after end_time → should return OFFLINE
   - Test before start_time → should return OFFLINE

### Using cURL

```bash
# Test live endpoint
curl -i http://localhost/churchapp/api/zoom/live

# Test schedule endpoint
curl -i http://localhost/churchapp/api/zoom/schedule

# Test status (with auth headers if needed)
curl -i http://localhost/churchapp/api/zoom/status
```

---

## 🔧 Customization

### Change Meeting Day
Edit `ZoomVideo_model.php` `isServiceLive()` method:
```php
// Change: $today !== 0 (0=Sunday, 1=Monday, etc.)
if ($today !== 5) { // Change to Friday (5)
    return false;
}
```

### Change Default Time
Edit migration or directly update database:
```sql
UPDATE zoom_videos 
SET start_time = '19:00:00', end_time = '21:00:00' 
WHERE id = 1;
```

### Add Multiple Services
The current design assumes one active service. To support multiple:
1. Modify the model to fetch all services
2. Check each service's schedule
3. Return array of active services

---

## 🎯 Deployment Checklist

- [ ] Run migrations: `php spark migrate`
- [ ] Verify `zoom_videos` table created
- [ ] Insert sample data via migration
- [ ] Test `GET /api/zoom/live` endpoint
- [ ] Test `GET /api/zoom/schedule` endpoint
- [ ] Access admin panel at `/zoomadmin`
- [ ] Add authentication to admin routes
- [ ] Update Zoom URL to correct meeting link
- [ ] Test on Flutter app
- [ ] Monitor error logs for API errors
- [ ] Set up HTTPS for production
- [ ] Enable Zoom waiting room

---

## 📝 Notes

- **No Zoom SDK Required:** This backend doesn't use Zoom API or SDK
- **Pure JSON Responses:** No HTML or Debugbar output for API endpoints
- **Dynamic Status:** Live status calculated in real-time, not stored in DB
- **Scalable:** Can be extended to support multiple services/churches
- **Mobile-Friendly:** Optimized for Flutter and mobile apps
- **Admin-Friendly:** Simple web interface for updating meeting details

---

## ❓ FAQ

**Q: Do I need a Zoom API key?**
A: No. This backend only stores the meeting URL and determines live status based on time.

**Q: Can the app work offline?**
A: No. The app must call the API endpoint to get the current status and meeting URL.

**Q: How often should the app call the endpoint?**
A: Recommended: Every 5-10 seconds when app is open on Sunday evenings.

**Q: Can I change the meeting time?**
A: Yes. Admin can update start_time and end_time via `/zoomadmin` or API.

**Q: What if the meeting ends early?**
A: Admin can update end_time in the database to reflect actual end time.

**Q: Is the meeting URL exposed?**
A: Yes, to mobile app only. Keep the URL secure and enable Zoom waiting room.

**Q: Can I add multiple services?**
A: Current design is for one service. Requires model modification for multiple.

---

## 📞 Support

For issues or questions:
1. Check API responses with Postman
2. Review server error logs
3. Verify database has correct data
4. Ensure auth filters are properly configured
5. Test on both web and mobile platforms
