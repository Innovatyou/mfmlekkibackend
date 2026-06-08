# Notification API Contract Examples

## 1. Fetch Notifications List

**Request:**
POST /api/fetch_notifications
```
{
  "user_id": "user123",
  "page": 0,
  "pageSize": 20
}
```

**Response:**
```
{
  "status": "ok",
  "notifications": [
    {
      "notification_id": 101,
      "user_id": "user123",
      "action": "Event",
      "title": "Upcoming Event",
      "message": "Don't miss our event!",
      "payload_json": {
        "action": "Event",
        "id": 55
      },
      "is_read": false,
      "created_at": "2026-04-23 10:00:00"
    },
    // ...more notifications...
  ],
  "isLastPage": false
}
```

---

## 2. Fetch Unread Notification Count

**Request:**
POST /api/getunseenmessages
```
{
  "user_id": "user123"
}
```

**Response:**
```
{
  "status": "ok",
  "notification_count": 3,
  "unseen_chat_count": 1
}
```

---

## 3. Mark Notifications as Read

**Request:**
POST /api/markNotificationsRead
```
{
  "user_id": "user123",
  "notification_ids": [101, 102]
}
```

**Response:**
```
{
  "status": "ok"
}
```

**To mark all as read:**
```
{
  "user_id": "user123"
}
```

---

# FCM Payload Examples (per action type)

## Event
```
{
  "title": "Upcoming Event",
  "action": "Event",
  "id": 55
}
```

## Article
```
{
  "title": "New Article",
  "action": "Article",
  "id": 77
}
```

## Devotional
```
{
  "title": "Daily Devotional",
  "action": "Devotional",
  "id": 88
}
```

## newMedia
```
{
  "title": "New Video Uploaded",
  "action": "newMedia",
  "media": { "media_id": 99, "type": "video" }
}
```

## inbox
```
{
  "title": "Inbox Message",
  "action": "inbox",
  "inbox": 123
}
```

## livestream
```
{
  "title": "Live Now!",
  "action": "livestream",
  "livestream": 321
}
```

## social_notify
```
{
  "title": "New Follower",
  "action": "social_notify",
  "email": "user@example.com",
  "avatar": "https://...",
  "message": "You have a new follower!"
}
```

## chat
```
{
  "title": "New Chat Message",
  "action": "chat",
  "chat": 555,
  "user": 42
}
```
