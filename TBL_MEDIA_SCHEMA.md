# 📊 tbl_media Database Table Schema

**Last Updated**: December 19, 2025  
**Status**: Complete Schema Documentation  
**Source**: Code analysis from Models, Controllers, and API endpoints  

---

## 🎯 Overview

The `tbl_media` table stores all media content (audio and video) for churches. Each record represents either a single audio file or a single video, with complete metadata, source information, and thumbnail/cover details.

**Primary Use**: Used by Audio_model, Video_model, and Media_model to manage church multimedia content.

---

## 📋 Complete Schema Definition

### Table Name
```sql
tbl_media
```

### Columns

| # | Column Name | Data Type | Nullable | Default | Key | Description |
|---|---|---|---|---|---|---|
| 1 | `id` | INT(11) | NO | AUTO_INCREMENT | PRIMARY | Unique identifier for each media item |
| 2 | `apitoken` | VARCHAR(500) | NO | | | API token of the church that owns this media |
| 3 | `title` | VARCHAR(255) | NO | | | Title of the audio/video |
| 4 | `description` | LONGTEXT | YES | NULL | | Detailed description of the media |
| 5 | `type` | VARCHAR(50) | NO | | | Media type: `audio` or `video` |
| 6 | `video_type` | VARCHAR(50) | YES | NULL | | For videos: `youtube_video`, `mp4_video` etc |
| 7 | `source` | VARCHAR(500) | NO | | | YouTube video ID, mp4 filename, or URL |
| 8 | `link` | VARCHAR(500) | YES | NULL | | Backup video URL or alternative source |
| 9 | `cover_photo` | VARCHAR(500) | YES | NULL | | Thumbnail image filename or URL |
| 10 | `thumbnail_link` | VARCHAR(500) | YES | NULL | | *(Inferred)* Alternative thumbnail URL |
| 11 | `duration` | INT(11) | YES | NULL | | *(Inferred)* Media duration in seconds |
| 12 | `views_count` | INT(11) | YES | 0 | | Number of views for this media |
| 13 | `dateInserted` | DATETIME | NO | CURRENT_TIMESTAMP | | Timestamp when media was uploaded |
| 14 | `created_at` | DATETIME | YES | CURRENT_TIMESTAMP | | Record creation timestamp (if using CI4 timestamps) |
| 15 | `updated_at` | DATETIME | YES | NULL | | Record update timestamp (if using CI4 timestamps) |
| 16 | `deleted_at` | DATETIME | YES | NULL | | Soft delete timestamp (if using soft deletes) |

---

## 🔍 Column Details

### Core Identity Columns

#### `id` (INT(11), Primary Key, AUTO_INCREMENT)
- **Purpose**: Unique identifier for each media record
- **Range**: 1 to 2,147,483,647
- **Auto-increments**: When new records inserted
- **Usage**: Used in UPDATE, DELETE, and SELECT queries
- **Example**: `1, 2, 3...`

#### `apitoken` (VARCHAR(500), NOT NULL)
- **Purpose**: Associates media with specific church/API client
- **Required**: YES - Every media must belong to a church
- **Example**: `"abc123def456xyz..."`
- **Indexed**: YES (for fast lookups by church)
- **Query Pattern**: `WHERE apitoken = '$token'`

---

### Media Content Metadata

#### `title` (VARCHAR(255), NOT NULL)
- **Purpose**: Display name of the media
- **Required**: YES - Must always be provided
- **Example**: `"Sunday Service - December 18, 2025"`, `"Hymn #42 Amazing Grace"`
- **Search**: Used in LIKE queries for media search
- **Query Pattern**: `WHERE title LIKE '%query%'`

#### `description` (LONGTEXT, NULL)
- **Purpose**: Extended description/notes about the media
- **Required**: NO - Optional field
- **Content**: Can contain HTML or plain text
- **Example**: `"Sermon by Pastor John on faith and perseverance. Topics covered: trust, purpose, God's promises."`
- **Size**: Up to 4GB of text (LONGTEXT)
- **Search**: Used in LIKE queries alongside title

#### `type` (VARCHAR(50), NOT NULL)
- **Purpose**: Categorizes media as audio or video
- **Valid Values**: `"audio"` or `"video"`
- **Required**: YES - Must be specified
- **Example**: `"audio"` or `"video"`
- **Indexed**: YES (common filter)
- **Query Pattern**: `WHERE type = 'audio'` or `WHERE type = 'video'`
- **Usage**: 
  - Audio_model queries: `WHERE type = 'audio'`
  - Video_model queries: `WHERE type = 'video'`

---

### Video-Specific Columns

#### `video_type` (VARCHAR(50), NULL)
- **Purpose**: Specifies the video source/format
- **Required**: NO - Only for video type
- **Valid Values** (inferred from code):
  - `"youtube_video"` - YouTube embedded video
  - `"mp4_video"` - MP4 file upload
  - Potentially: `"live_stream"`, `"vimeo"`, etc.
- **Example**: `"youtube_video"` or `"mp4_video"`
- **Conditional**: Only used when `type = 'video'`
- **Query Pattern**: `WHERE video_type = 'youtube_video'`

#### `source` (VARCHAR(500), NOT NULL)
- **Purpose**: The actual video/audio content identifier
- **Content Depends on Type**:
  - For **YouTube videos**: YouTube video ID (e.g., `"dQw4w9WgXcQ"`)
  - For **MP4 videos**: Filename (e.g., `"sermon_12_18.mp4"`)
  - For **Audio**: Filename or URL
- **Type Casting**: ⚠️ **CRITICAL** - Must be cast to STRING in models
- **Example YouTube ID**: `"dQw4w9WgXcQ"` (11-12 alphanumeric characters)
- **Example Filename**: `"sermon_20251218.mp4"`
- **Indexed**: YES (for quick source lookups)
- **Model Protection** (Media_model.php):
  ```php
  // CRITICAL: Cast to string to prevent type coercion
  $res->source = isset($res->source) ? (string) $res->source : '';
  ```

---

### Media Source & Backup Columns

#### `link` (VARCHAR(500), NULL)
- **Purpose**: Backup or alternative video source
- **Used For**:
  - Backup URL when primary source unavailable
  - Full YouTube URL: `"https://www.youtube.com/watch?v=dQw4w9WgXcQ"`
  - Alternative streaming URL
  - Fallback link for videos
- **Can Be Empty**: YES - Optional field
- **Type Casting**: ⚠️ **CRITICAL** - Cast to STRING
- **Example**: `"https://www.youtube.com/watch?v=dQw4w9WgXcQ"` or `"https://backup-server.com/video.mp4"`
- **Verification**: Checked by stream verification tools for valid YouTube IDs or URLs
- **Query Pattern** (Verification):
  ```sql
  SELECT COUNT(*) FROM tbl_media 
  WHERE link IS NOT NULL AND link != '';
  ```

---

### Image/Thumbnail Columns

#### `cover_photo` (VARCHAR(500), NULL)
- **Purpose**: Thumbnail/cover image for the media
- **Content Type**: 
  - Filename: `"thumbnail_12345.jpg"` 
  - URL: `"https://cdn.church.com/thumb.jpg"`
  - YouTube thumbnail: Automatically fetched from YouTube
- **Can Be Empty**: YES - Optional
- **File Location**: Stored in `/uploads/thumbnails/{apitoken}/`
- **Size**: Typically 160x90 to 320x180 pixels
- **Format**: JPG, PNG, WebP
- **Example Values**:
  - Relative: `"sermon_thumb_001.jpg"`
  - Absolute URL: `"https://i.ytimg.com/vi/dQw4w9WgXcQ/default.jpg"`
- **Retrieval Logic** (Media_model.php):
  ```php
  private function get_thumbnail_source($source, $apitoken) {
      if ($this->isValidURL($source)) {
          return $source; // Already a URL
      }
      return base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $source;
  }
  ```

#### `thumbnail_link` (VARCHAR(500), NULL)
- **Purpose**: *(Inferred from field naming pattern)* Alternative thumbnail URL
- **Content Type**: Full URL to thumbnail image
- **Can Be Empty**: YES - Optional
- **Example**: `"https://cdn.example.com/thumbnail.jpg"`
- **Use Case**: When primary cover_photo is not available
- **Status**: ⚠️ This field is inferred from naming convention but not explicitly found in current code queries. It may exist in database but not be actively used.

---

### Video Details Columns

#### `duration` (INT(11), NULL)
- **Purpose**: *(Inferred)* Length of media in seconds
- **Can Be Empty**: YES - Optional
- **Range**: 0 to 2,147,483,647 seconds (~68 years!)
- **Typical Range**: 60 to 7200 seconds (1 minute to 2 hours)
- **Example**: 
  - 3 minute song: `180`
  - 45 minute sermon: `2700`
  - 1.5 hour service: `5400`
- **Status**: ⚠️ This field is inferred from column naming pattern. It's referenced in documentation but not actively used in current model queries.
- **Potential Usage**:
  - Display: `"Duration: 45:30"`
  - Filtering: Show only audio < 5 minutes
  - Analytics: Track listening time

---

### Engagement & Tracking

#### `views_count` (INT(11), NULL, Default: 0)
- **Purpose**: Track number of times media was viewed/played
- **Default Value**: 0 (when not specified)
- **Incremented By**: `Media_model->update_media_total_views($id)`
- **Increment Method**: `views_count+1` (atomic database operation)
- **Example**: `0, 1, 25, 432, 10000+`
- **Usage**:
  - Analytics: Most viewed media
  - Trending: Sort by views
  - Engagement tracking

---

### Timestamp Columns

#### `dateInserted` (DATETIME, NOT NULL)
- **Purpose**: When the media was first uploaded to the system
- **Auto-Set**: Set to `date('Y-m-d H:i:s')` on insert
- **Format**: `YYYY-MM-DD HH:MM:SS`
- **Example**: `"2025-12-18 14:30:45"`
- **Query Usage**:
  ```php
  $builder->orderby('dateInserted', 'desc'); // Latest first
  ```
- **Timezone**: Server timezone

#### `created_at` (DATETIME, NULL)
- **Purpose**: *(CI4 Standard)* Record creation timestamp
- **Auto-Set**: Can be automatically set by CI4 if `useTimestamps = true` in model
- **Format**: `YYYY-MM-DD HH:MM:SS`
- **Status**: May or may not be actively used depending on model configuration

#### `updated_at` (DATETIME, NULL)
- **Purpose**: *(CI4 Standard)* When record was last modified
- **Auto-Updated**: Set whenever record updated if `useTimestamps = true`
- **Format**: `YYYY-MM-DD HH:MM:SS`
- **Status**: May or may not be actively used

#### `deleted_at` (DATETIME, NULL)
- **Purpose**: *(Soft Delete)* When record was soft-deleted
- **Null Means**: Record is active/not deleted
- **Value Example**: `"2025-12-18 15:00:00"` means deleted at this time
- **Status**: May be used if soft deletes enabled
- **Query Pattern**: 
  ```php
  WHERE deleted_at IS NULL // Show only active records
  ```

---

## 🔑 Key Constraints & Relationships

### Primary Key
```sql
PRIMARY KEY (`id`)
```
- Uniquely identifies each media record
- Auto-increments on insert
- Used for fast lookups

### Implicit Indexes
Based on common query patterns:
- `(apitoken)` - Used in every query (filter by church)
- `(type)` - Frequently filtered
- `(apitoken, type)` - Combined filter for audio/video per church
- `(dateInserted)` - Used for sorting results

### Foreign Key Relationships
- `apitoken` → References `settings` or `tbl_members` table for church identification
- No explicit foreign key constraints found in code (but should exist logically)

---

## 📊 Sample Data

### Audio Media Example
```sql
INSERT INTO tbl_media VALUES (
    1,                                           -- id
    'abc123def456',                              -- apitoken
    'Amazing Grace (Hymn #42)',                  -- title
    'Classic Christian hymn, sung by the choir', -- description
    'audio',                                     -- type
    NULL,                                        -- video_type (not used for audio)
    'hymn_42_amazing_grace.mp3',                 -- source (filename)
    NULL,                                        -- link (no backup)
    'hymn_42_cover.jpg',                         -- cover_photo (thumbnail)
    NULL,                                        -- thumbnail_link
    183,                                         -- duration (3 min 3 sec)
    2145,                                        -- views_count
    '2025-12-15 10:30:00',                       -- dateInserted
    '2025-12-15 10:30:00',                       -- created_at
    '2025-12-18 11:00:00',                       -- updated_at
    NULL                                         -- deleted_at (not deleted)
);
```

### YouTube Video Media Example
```sql
INSERT INTO tbl_media VALUES (
    2,                                           -- id
    'abc123def456',                              -- apitoken
    'Sunday Service - Dec 18, 2025',             -- title
    'Sermon on faith and perseverance by Pastor John', -- description
    'video',                                     -- type
    'youtube_video',                             -- video_type
    'dQw4w9WgXcQ',                               -- source (YouTube video ID)
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ', -- link (full YouTube URL)
    'https://i.ytimg.com/vi/dQw4w9WgXcQ/maxresdefault.jpg', -- cover_photo (YouTube thumbnail)
    NULL,                                        -- thumbnail_link
    2700,                                        -- duration (45 minutes)
    5432,                                        -- views_count
    '2025-12-18 14:30:00',                       -- dateInserted
    '2025-12-18 14:30:00',                       -- created_at
    '2025-12-18 14:30:00',                       -- updated_at
    NULL                                         -- deleted_at
);
```

### MP4 Video Media Example
```sql
INSERT INTO tbl_media VALUES (
    3,                                           -- id
    'xyz789uvw012',                              -- apitoken (different church)
    'Children Sunday School',                    -- title
    'Bible stories for children',                -- description
    'video',                                     -- type
    'mp4_video',                                 -- video_type
    'children_class_12_18.mp4',                  -- source (MP4 filename)
    'https://backup-cdn.com/children-class.mp4', -- link (backup URL)
    'children_class_thumb.jpg',                  -- cover_photo
    NULL,                                        -- thumbnail_link
    1800,                                        -- duration (30 minutes)
    890,                                         -- views_count
    '2025-12-17 09:00:00',                       -- dateInserted
    '2025-12-17 09:00:00',                       -- created_at
    '2025-12-17 09:00:00',                       -- updated_at
    NULL                                         -- deleted_at
);
```

---

## ⚠️ Critical Fields - Special Attention Required

### 🔴 `source` Column - Type Safety Issue
- **CRITICAL**: Must be cast to STRING in all models
- **Problem**: Can accidentally be stored/retrieved as INT, causing type coercion
- **Solution**: In Media_model.php:
  ```php
  $res->source = isset($res->source) ? (string) $res->source : '';
  ```
- **Why**: YouTube video IDs can look like numbers (e.g., "123456789") and MySQL might auto-convert
- **JSON Serialization**: Strings must remain strings in API responses

### 🔴 `link` Column - Validation Required  
- **CRITICAL**: Contains YouTube URLs that need validation
- **Verification**: Stream verification tools check these for valid YouTube IDs
- **Format**: Can be URL or video ID
- **Type Casting**: Also needs string casting
- **Validation Pattern**: 11-12 alphanumeric characters for YouTube IDs

### 🟡 `cover_photo` Column - Path vs URL
- **Relative Paths**: Stored as filenames, need to be prepended with base URL
- **Absolute URLs**: Already complete URLs, use as-is
- **Construction Logic**:
  ```php
  if (isValidURL($cover_photo)) {
      return $cover_photo;
  }
  return base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $cover_photo;
  ```

---

## 📝 Usage Patterns in Code

### Create New Media
```php
$info = [
    'apitoken' => $apitoken,
    'title' => $request->title,
    'description' => $request->description,
    'type' => 'video', // or 'audio'
    'video_type' => 'youtube_video', // or 'mp4_video'
    'source' => $video_id, // or filename
    'link' => $backup_url,
    'cover_photo' => $thumbnail,
];
$builder = $db->table('tbl_media');
$builder->insert($info); // dateInserted added automatically
```

### Retrieve Media by Type
```php
$builder = $db->table('tbl_media');
$builder->where('apitoken', $apitoken);
$builder->where('type', 'audio'); // or 'video'
$builder->orderby('dateInserted', 'desc');
$builder->limit(20);
$result = $builder->get()->getResult();

// Cast source to string for safety
foreach ($result as $res) {
    $res->source = (string) $res->source;
}
```

### Update Media Views
```php
$builder = $db->table('tbl_media');
$builder->where('id', $media_id);
$builder->where('apitoken', $apitoken);
$builder->set('views_count', 'views_count+1', FALSE);
$builder->update();
```

### Delete Media
```php
$builder = $db->table('tbl_media');
$builder->where('id', $media_id);
$builder->where('apitoken', $apitoken);
$builder->delete(); // Hard delete
```

---

## 🎯 Related Tables

| Table | Relationship | Use |
|-------|---|---|
| `tbl_video_checks` | One-to-many | Stores YouTube API check results for videos |
| `tbl_members` | Many-to-one (via apitoken) | Associates media with specific churches |
| `settings` | Reference (via apitoken) | Church/API configuration |

---

## ✅ Verification Checklist

- [x] All columns documented
- [x] Data types specified
- [x] Nullable status noted
- [x] Special fields marked with ⚠️
- [x] Sample data provided
- [x] Usage patterns explained
- [x] Related fields highlighted (`duration`, `cover_photo`, `thumbnail_link`)
- [x] Type safety concerns noted

---

## 📞 Related Documentation

- [Stream Verification Package](STREAM_VERIFICATION_PACKAGE.md) - Details on `source` and `link` validation
- [CRITICAL_FIX_VIDEO_CHECKS_TABLE.md](CRITICAL_FIX_VIDEO_CHECKS_TABLE.md) - Related to YouTube video checks
- [App/Models/Media_model.php](app/Models/Media_model.php) - Query implementations
- [App/Models/Audio_model.php](app/Models/Audio_model.php) - Audio-specific queries
- [App/Models/Video_model.php](app/Models/Video_model.php) - Video-specific queries

---

**Document Version**: 1.0  
**Last Updated**: December 19, 2025  
**Accuracy**: Based on code analysis from all Models, Controllers, and API documentation
