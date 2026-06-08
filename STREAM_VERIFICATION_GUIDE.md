# ✅ Stream Field Verification & Validation Guide

**Date**: December 19, 2025  
**Objective**: Ensure all stream/video ID fields are valid YouTube IDs and returned as STRING types

---

## 📋 OVERVIEW

This guide covers:
1. **Verification** - Check all stream IDs are valid
2. **Validation** - Ensure consistent string types
3. **Cleanup** - Fix invalid IDs automatically
4. **Testing** - Verify API responses

---

## 🔧 TOOLS CREATED

### **StreamIdHelper** (Helper Class)
**Location**: `app/Helpers/StreamIdHelper.php`

**Functions**:
- `isValidYoutubeId($id)` - Validates YouTube ID format (11-12 alphanumeric)
- `normalizeStreamId($value)` - Converts any type to valid string
- `sanitizeStreamId($id)` - Full validation with details
- `extractYoutubeId($url)` - Extract ID from various URL formats
- `validateStreamArray($streams)` - Validate entire arrays
- `generateValidationReport($records)` - Generate detailed report

### **VerifyStreams** (Console Command)
**Location**: `app/Commands/VerifyStreams.php`

**Usage**:
```bash
# Check for issues (no changes)
php spark verify:streams

# Fix issues automatically
php spark verify:streams --fix

# Generate detailed report
php spark verify:streams --report

# Show all records (verbose)
php spark verify:streams --verbose

# Combined
php spark verify:streams --fix --report --verbose
```

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Upload Files

Upload to production:
```
app/Helpers/StreamIdHelper.php        (NEW)
app/Commands/VerifyStreams.php        (NEW)
app/Helpers/StreamUrlHelper.php       (ALREADY EXISTS)
app/Models/Media_model.php            (UPDATED - string casting)
```

### Step 2: Clear Cache

```bash
rm -rf writable/cache/*
```

### Step 3: Run Verification

```bash
cd /home/innovat8/public_html/church.innovative.ng

# First, just check (no changes)
php spark verify:streams

# Review output
# If issues found, fix them
php spark verify:streams --fix

# Run again to confirm
php spark verify:streams
```

### Step 4: Generate Report

```bash
php spark verify:streams --report
```

---

## 🔍 WHAT GETS CHECKED

### Tables & Fields Verified

| Table | Fields | Purpose |
|-------|--------|---------|
| `tbl_media` | `source` | YouTube video ID for videos/audios |
| `tbl_media` | `link` | Backup video link field |
| `tbl_livestreams` | `link` | Livestream URL/ID |

### Validation Rules

✅ **Valid IDs**:
- 11-12 characters long
- Alphanumeric: `a-zA-Z0-9_-`
- Examples: `dQw4w9WgXcQ`, `ZYpyEJCCmJE`

❌ **Invalid IDs**:
- Integer: `12345`
- Null: `NULL`
- Zero: `0` or `"0"`
- Empty: `""` or whitespace
- Wrong format: `abc`, `video@123`, special chars

### Type Conversion

| From | To | Reason |
|------|----|----|
| `12345` (int) | `"12345"` (string) | Database returned as integer |
| `null` | `""` (empty string) | No video ID |
| `0` | `""` (empty string) | Invalid/placeholder |
| `false` | `""` (empty string) | Invalid value |
| `"  id  "` | `"id"` (trimmed) | Remove whitespace |

---

## 📊 VERIFICATION COMMAND EXAMPLES

### Example 1: Basic Check

```bash
php spark verify:streams
```

**Output**:
```
================================================================================
  STREAM ID VERIFICATION & VALIDATION
================================================================================

📊 Checking table: tbl_media
  Field: source
    Total records: 150
    ✅ Valid: 148
    ❌ Invalid: 2
  Field: link
    Total records: 150
    ✅ Valid: 150
    ❌ Invalid: 0

📊 Checking table: tbl_livestreams
  Field: link
    Total records: 5
    ✅ Valid: 5
    ❌ Invalid: 0

================================================================================
📋 VERIFICATION SUMMARY
================================================================================
Total issues found: 2
✅ All stream IDs are valid!
```

### Example 2: Fix Issues

```bash
php spark verify:streams --fix
```

**Output**:
```
...
  Field: source
    Total records: 150
    ✅ Valid: 148
    ❌ Invalid: 2
    🔧 Fixed 2 record(s)
...
Total issues fixed: 2
```

### Example 3: Detailed Report

```bash
php spark verify:streams --report
```

**Output**:
```
================================================================================
📈 DETAILED VALIDATION REPORT
================================================================================

Table: tbl_media | Field: source
  Valid: 148 | Invalid: 2 | Empty: 0 | Type Issues: 1
  Issues:
    • Record 45: Invalid YouTube ID format (should be 11-12 alphanumeric chars)
    • Record 67: Invalid YouTube ID format (should be 11-12 alphanumeric chars)
```

---

## ✅ API RESPONSE VERIFICATION

### Before (With Issues)

```json
{
  "status": "ok",
  "latest_media": [
    {
      "id": 1,
      "title": "Video 1",
      "source": 12345,              // ❌ Integer type
      "video_type": "youtube_video"
    },
    {
      "id": 2,
      "title": "Video 2",
      "source": null,               // ❌ Null type
      "video_type": "youtube_video"
    }
  ]
}
```

### After (All Fixed)

```json
{
  "status": "ok",
  "latest_media": [
    {
      "id": 1,
      "title": "Video 1",
      "source": "dQw4w9WgXcQ",      // ✅ String type
      "video_type": "youtube_video"
    },
    {
      "id": 2,
      "title": "Video 2",
      "source": "",                 // ✅ String (empty)
      "video_type": "youtube_video"
    }
  ]
}
```

---

## 🧪 MANUAL TESTING

### Test 1: Verify Helper Functions

**In cPanel Terminal:**
```bash
cd /home/innovat8/public_html/church.innovative.ng

# Create test script
cat > test_stream_helper.php <<'EOF'
<?php
require 'vendor/autoload.php';
use App\Helpers\StreamIdHelper;

echo "Test 1: Valid ID\n";
var_dump(StreamIdHelper::isValidYoutubeId('dQw4w9WgXcQ'));  // true

echo "Test 2: Invalid ID\n";
var_dump(StreamIdHelper::isValidYoutubeId('abc'));  // false

echo "Test 3: Normalize int\n";
var_dump(StreamIdHelper::normalizeStreamId(12345));  // "12345"

echo "Test 4: Normalize null\n";
var_dump(StreamIdHelper::normalizeStreamId(null));  // ""

echo "Test 5: Extract from URL\n";
var_dump(StreamIdHelper::extractYoutubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));  // "dQw4w9WgXcQ"
EOF

php test_stream_helper.php
rm test_stream_helper.php
```

### Test 2: API Response Check

**Using Postman or cURL:**
```bash
curl -X POST https://church.innovative.ng/api/initapp \
  -H "Content-Type: application/json" \
  -d '{"token":"your_api_token"}'

# Look at response JSON
# Verify "source" field is ALWAYS a string
```

### Test 3: Database Check

```bash
mysql -u innovat8_user -p innovat8_churchapp_db << EOF
-- Check data types
SELECT id, source, link, TYPEOF(source) as source_type 
FROM tbl_media 
LIMIT 5;

-- Check for invalid IDs
SELECT id, source 
FROM tbl_media 
WHERE LENGTH(source) NOT IN (11, 12) 
  OR source NOT REGEXP '^[a-zA-Z0-9_-]+$' 
  OR source IS NULL;
EOF
```

---

## 📝 HELPER USAGE IN CODE

### In Controllers

```php
use App\Helpers\StreamIdHelper;

// Validate single ID
$id = $this->request->getVar('video_id');
$validated = StreamIdHelper::sanitizeStreamId($id);

if (!$validated['is_valid']) {
    return $this->response->setJSON([
        'status' => 'error',
        'message' => $validated['reason']
    ]);
}

// Use validated string ID
$videoId = $validated['value'];  // Guaranteed string
```

### In Models

```php
use App\Helpers\StreamIdHelper;

// Normalize array of results
$results = $query->getResult();
$results = StreamIdHelper::validateStreamArray($results);

// Now all source fields are strings
foreach ($results as $item) {
    echo $item->source;  // Always string, never int
}
```

### In API Responses

```php
// Ensure response consistency
$data = [
    'id' => 1,
    'source' => '12345'  // Cast before returning
];

echo json_encode($data);  // {"id":1,"source":"12345"}
```

---

## 🔄 VALIDATION REPORT EXAMPLE

```
Total records checked: 155
Valid IDs: 150
Invalid IDs: 2
Empty IDs: 3
Type mismatches: 1

Issues Found:
  • Record 45: Invalid YouTube ID format
  • Record 67: Invalid YouTube ID format

Recommendations:
  1. Run: php spark verify:streams --fix
  2. Review cleaned records
  3. Check API responses
  4. Monitor logs for issues
```

---

## ⚠️ IMPORTANT NOTES

1. **Automatic Fixes**: `--fix` flag converts invalid IDs to empty string (`""`)
2. **No Data Loss**: Original values kept in logs, can be recovered if needed
3. **Type Safety**: All normalized values ALWAYS STRING type
4. **Backward Compatible**: Doesn't break existing code
5. **Reversible**: Can restore from database backup if needed

---

## 🆘 TROUBLESHOOTING

### Issue: Command not found
```bash
# Clear command cache
php spark clear:cache

# Try again
php spark verify:streams
```

### Issue: Permission denied
```bash
# Check file permissions
ls -la app/Commands/VerifyStreams.php

# Fix if needed
chmod 644 app/Commands/VerifyStreams.php
```

### Issue: Database connection error
```bash
# Verify .env has correct credentials
cat .env | grep database

# Test connection
php spark db:connect
```

---

## 📞 QUICK COMMANDS

```bash
# Verify only
php spark verify:streams

# Fix all issues
php spark verify:streams --fix

# Detailed report
php spark verify:streams --report

# All options combined
php spark verify:streams --fix --report --verbose

# Clear cache after running
rm -rf writable/cache/*
```

---

## ✅ VERIFICATION CHECKLIST

- [ ] StreamIdHelper.php uploaded
- [ ] VerifyStreams.php uploaded
- [ ] Media_model.php updated
- [ ] Cache cleared
- [ ] Run `php spark verify:streams`
- [ ] Review output for issues
- [ ] Run `php spark verify:streams --fix` if needed
- [ ] Verify again with `php spark verify:streams`
- [ ] Generate report with `--report` flag
- [ ] Test API response types
- [ ] Test Flutter app (videos should play)
- [ ] Monitor logs for errors

---

## 🎯 SUCCESS CRITERIA

✅ All stream fields verified
✅ All IDs valid YouTube format or empty
✅ All IDs returned as STRING type in API
✅ No type errors in Flutter app
✅ No database errors in logs
✅ Video playback working

---

**Status**: Ready for deployment and verification

All stream IDs will be verified and validated across your application!
