# ZOOM LIVE SERVICE - QUICK REFERENCE CARD

## 🚀 Quick Start (5 Minutes)

```bash
# 1. Run migrations
php spark migrate

# 2. Test endpoint
curl http://localhost/churchapp/api/zoom/live

# 3. Access admin
http://localhost/churchapp/zoomadmin
```

---

## 📡 API Endpoints at a Glance

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/api/zoom/live` | GET | No | Get current status + URL |
| `/api/zoom/schedule` | GET | No | Get schedule info |
| `/api/zoom/status` | GET | Yes | Debug: detailed status |
| `/zoomadmin` | GET | Yes | Admin dashboard |
| `/zoomadmin/update` | POST | Yes | Update via form |
| `/api/zoomadmin/update` | POST | Yes | Update via JSON |

---

## 📊 Response Formats

### When LIVE (Sunday 8:00-10:30 PM)
```json
{
  "status": "live",
  "data": {
    "title": "SUNDAY NIGHT PRAYER MEETING",
    "platform": "zoom",
    "meeting_url": "https://...",
    "start_time": "2025-12-21 20:00:00"
  }
}
```

### When OFFLINE
```json
{
  "status": "offline",
  "message": "Zoom service holds every Sunday by 8:00 PM"
}
```

---

## 🔄 Live Status Logic

```
IF today == Sunday AND time >= 20:00 AND time < 22:30
  THEN status = LIVE
  ELSE status = OFFLINE
```

---

## 📁 Key Files

| File | Purpose |
|------|---------|
| `app/Models/ZoomVideo_model.php` | Time logic + LIVE detection |
| `app/Controllers/Zoom.php` | Mobile API endpoints |
| `app/Controllers/ZoomAdmin.php` | Admin management |
| `app/Views/admin/zoom_settings.php` | Admin dashboard form |

---

## 🧪 Quick Tests

```bash
# Test live endpoint
curl http://localhost/churchapp/api/zoom/live

# Test schedule
curl http://localhost/churchapp/api/zoom/schedule

# Update via JSON
curl -X POST http://localhost/churchapp/api/zoomadmin/update \
  -H "Content-Type: application/json" \
  -d '{"title":"New Title","meeting_url":"https://..."}'
```

---

## 🛠️ Common Customizations

### Change Meeting Time
Update in admin panel or database:
```sql
UPDATE zoom_videos SET start_time = '19:00:00', end_time = '21:00:00';
```

### Change Meeting Day
Edit `app/Models/ZoomVideo_model.php` line ~50:
```php
if ($today !== 0) {  // 0=Sunday, change to 5 for Friday
```

### Add Auth to Routes
Edit `app/Config/Routes.php`:
```php
$routes->get('zoomadmin', 'ZoomAdmin::index', ['filter' => 'auth']);
```

---

## 🔐 Security Checklist

- ✅ Zoom URLs loaded dynamically (not hardcoded in app)
- ✅ JSON responses only (no HTML exposure)
- ✅ Admin routes ready for auth filter
- ✅ Input validation on updates
- ✅ HTTPS recommended for production

---

## 📱 Flutter Integration Snippet

```dart
Future<void> checkZoom() async {
  final response = await http.get(
    Uri.parse('https://yourdomain.com/api/zoom/live'),
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    if (data['status'] == 'live') {
      // Open Zoom URL
      launch(data['data']['meeting_url']);
    }
  }
}
```

---

## 🚨 Troubleshooting

| Problem | Solution |
|---------|----------|
| Migration fails | Run `php spark db:create` first |
| 404 error | Check Routes.php has all routes |
| Always offline | Check server time/timezone |
| DB connection error | Verify credentials in `.env` |

---

## 📞 Need Help?

1. **Setup Issues:** See `ZOOM_COMPLETE_SETUP_GUIDE.md`
2. **API Questions:** See `ZOOM_API_TESTING_GUIDE.md`
3. **Implementation Details:** See `ZOOM_LIVE_SERVICE_IMPLEMENTATION.md`
4. **Code Review:** Check inline comments in controllers/models

---

## ✅ Deployment Commands

```bash
# Run migrations
php spark migrate

# Clear cache (if needed)
php spark cache:clear

# Run tests (if configured)
php spark test

# Start development server
php spark serve
```

---

## 🎯 Success Metrics

- ✅ API returns JSON with correct format
- ✅ Live status changes at 8:00 PM and 10:30 PM on Sundays
- ✅ Admin can update meeting details
- ✅ Flutter app receives correct status
- ✅ No errors in server logs

---

**Status:** ✅ Ready to Deploy
**Version:** 1.0
**Last Updated:** December 21, 2025
