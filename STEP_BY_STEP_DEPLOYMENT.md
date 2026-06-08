# 🚀 DEPLOYMENT GUIDE - Step by Step

**Target Server**: innovat8.com (church.innovative.ng)
**Database**: innovat8_churchapp_db
**Application Path**: /home/innovat8/public_html/church.innovative.ng

---

## 📋 DEPLOYMENT CHECKLIST

- [ ] Files ready on local machine
- [ ] SSH access to server verified
- [ ] FTP/SFTP connection working
- [ ] Database backup created (optional but recommended)
- [ ] Files uploaded
- [ ] Migration executed
- [ ] Verification completed
- [ ] Testing passed

---

## 🔧 STEP 1: PREPARE FILES

### Files to Upload (2 files total)

**File 1**: NEW Migration
```
Local Path:
c:\Users\user\Documents\App\MyCHurchApp_Pro\church.innovative.ng\church.innovative.ng\
  → app\Database\Migrations\2025-12-18-000002_CreateVideoChecksTable.php

Upload To:
/home/innovat8/public_html/church.innovative.ng/app/Database/Migrations/
  → 2025-12-18-000002_CreateVideoChecksTable.php
```

**File 2**: UPDATED Model
```
Local Path:
c:\Users\user\Documents\App\MyCHurchApp_Pro\church.innovative.ng\church.innovative.ng\
  → app\Models\YouTube_model.php

Upload To:
/home/innovat8/public_html/church.innovative.ng/app/Models/
  → YouTube_model.php
```

---

## 📤 STEP 2: UPLOAD FILES

### Option A: Using FTP/SFTP (Recommended for beginners)

**Using FileZilla or similar FTP client:**

1. Open FileZilla
2. Connect to: `innovat8.com` (or your FTP hostname)
   - Username: `innovat8` (or your FTP user)
   - Password: [Your FTP password]
   - Port: 21 (FTP) or 22 (SFTP)

3. Navigate to: `/home/innovat8/public_html/church.innovative.ng/app/`

4. Upload File 1:
   - Drag & drop `2025-12-18-000002_CreateVideoChecksTable.php`
   - Drop into: `Migrations/` folder
   - Verify: File appears in Migrations folder

5. Upload File 2:
   - Drag & drop `YouTube_model.php`
   - Drop into: `Models/` folder
   - Verify: File appears in Models folder

6. Close FTP connection

### Option B: Using SSH/Terminal (For advanced users)

**Open terminal/command prompt:**

```bash
# Connect via SSH
ssh innovat8@innovat8.com
# Enter password when prompted

# Navigate to project directory
cd /home/innovat8/public_html/church.innovative.ng

# Verify migrations folder exists
ls -la app/Database/Migrations/

# You should see:
# 2024-12-18-000001_FixLivestreamStreamUrlColumn.php
# (soon to add)
# 2025-12-18-000002_CreateVideoChecksTable.php

# Exit SSH (we'll use it again for migration)
exit
```

**Then use SCP to upload files:**

```bash
# Open terminal in your local directory with the files

# Upload migration file
scp app/Database/Migrations/2025-12-18-000002_CreateVideoChecksTable.php \
  innovat8@innovat8.com:/home/innovat8/public_html/church.innovative.ng/app/Database/Migrations/

# Upload model file
scp app/Models/YouTube_model.php \
  innovat8@innovat8.com:/home/innovat8/public_html/church.innovative.ng/app/Models/

# Enter password when prompted for each file
```

---

## ✅ STEP 3: VERIFY FILES UPLOADED

**Via FTP Client:**
1. Navigate to `/home/innovat8/public_html/church.innovative.ng/app/Migrations/`
2. Look for: `2025-12-18-000002_CreateVideoChecksTable.php`
3. Navigate to `/home/innovat8/public_html/church.innovative.ng/app/Models/`
4. Look for: `YouTube_model.php`
5. File dates should be TODAY (Dec 18, 2025)

**Via SSH (command line):**
```bash
ssh innovat8@innovat8.com
cd /home/innovat8/public_html/church.innovative.ng

# Check migration file
ls -lh app/Database/Migrations/2025-12-18-000002_*

# Expected output:
# -rw-r--r-- 1 innovat8 innovat8 3.2K Dec 18 10:30 2025-12-18-000002_CreateVideoChecksTable.php

# Check model file
ls -lh app/Models/YouTube_model.php

# Expected output:
# -rw-r--r-- 1 innovat8 innovat8 2.1K Dec 18 10:31 YouTube_model.php

exit
```

---

## 🗄️ STEP 4: OPTIONAL - BACKUP DATABASE (Recommended)

**Before running migration, back up your database:**

```bash
ssh innovat8@innovat8.com

# Create backup
mysqldump -u innovat8_user -p innovat8_churchapp_db > \
  /home/innovat8/backups/innovat8_churchapp_db_backup_2025-12-18_$(date +%H%M%S).sql

# Enter MySQL password when prompted

# Verify backup created
ls -lh /home/innovat8/backups/innovat8_churchapp_db_backup_*

exit
```

---

## 🔄 STEP 5: RUN MIGRATION

**Connect via SSH and run migration:**

```bash
ssh innovat8@innovat8.com

# Navigate to project
cd /home/innovat8/public_html/church.innovative.ng

# Run migration
php spark migrate

# You should see:
# CodeIgniter 4.x Database Migrations
# 2025-12-18-000002_CreateVideoChecksTable : migrate
# Complete
```

**If you get an error, check:**
- PHP is installed: `php -v`
- CodeIgniter is configured: `php spark --version`
- Database credentials in `.env` are correct

---

## ✅ STEP 6: VERIFY MIGRATION SUCCESSFUL

### Check Migration Status

```bash
# Still connected via SSH
php spark migrate:status

# Expected output:
# +----+------ Migration ------+--------+
# | No | Name                  | Batch  |
# +----+------ Migration ------+--------+
# | 1  | 2024-12-18-000001... | 1      |
# | 2  | 2025-12-18-000002... | 2      | ← NEW
# +----+------ Migration ------+--------+
```

### Verify Table Created

```bash
# Connect to MySQL
mysql -u innovat8_user -p innovat8_churchapp_db

# Run SQL command
SHOW TABLES LIKE 'tbl_video_checks';

# Expected output:
# +---------------------------+
# | Tables_in_churchapp_db    |
# +---------------------------+
# | tbl_video_checks          |
# +---------------------------+

# Describe table structure
DESCRIBE tbl_video_checks;

# Expected output showing all 10 fields:
# +------------------+-------------+
# | Field            | Type        |
# +------------------+-------------+
# | id               | int(11)     |
# | video_id         | varchar(255)|
# | apitoken         | varchar(500)|
# | is_embeddable    | tinyint(1)  |
# | reason           | varchar(500)|
# | privacy_status   | varchar(50) |
# | content_details  | longtext    |
# | checked_at       | datetime    |
# | created_at       | datetime    |
# | updated_at       | datetime    |
# +------------------+-------------+

# Exit MySQL
exit;

# Exit SSH
exit
```

---

## 🧹 STEP 7: CLEAR CACHE & RESTART

```bash
ssh innovat8@innovat8.com

cd /home/innovat8/public_html/church.innovative.ng

# Clear CodeIgniter cache
rm -rf writable/cache/*
echo "Cache cleared"

# Clear logs (optional, keeps space clean)
rm -rf writable/logs/*
echo "Logs cleared"

# Restart PHP-FPM (production server restart)
sudo systemctl restart php-fpm

# OR if using PHP with Apache
sudo systemctl restart apache2

# Verify PHP is running
sudo systemctl status php-fpm

# Should show "active (running)"

exit
```

---

## 🧪 STEP 8: TEST YOUTUBE VIDEO UPLOAD

### Test in Admin Panel

1. Open browser: `https://church.innovative.ng/admin/`
2. Login with admin credentials
3. Navigate to: **Videos** → **Add New Video**
4. Select: **Media Type: YouTube Video**
5. Enter YouTube URL: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
6. Enter Title: "Test Video"
7. Click: **Save**
8. **Expected Result**: Video saves successfully ✅

### Test API Response

**Option A: Using Postman**
```
Method: POST
URL: https://church.innovative.ng/api/initapp
Body (JSON):
{
  "token": "your_api_token"
}

Expected Response:
{
  "status": "success",
  "data": {
    "media": [
      {
        "id": 1,
        "link": "dQw4w9WgXcQ",
        "title": "Test Video",
        ...
      }
    ]
  }
}
```

**Option B: Using cURL in Terminal**
```bash
curl -X POST https://church.innovative.ng/api/initapp \
  -H "Content-Type: application/json" \
  -d '{"token":"your_api_token"}'
```

### Check Database

```bash
ssh innovat8@innovat8.com

mysql -u innovat8_user -p innovat8_churchapp_db

# Check if video check was cached
SELECT * FROM tbl_video_checks;

# Should show record(s):
# +----+----------------------------+----------+---------------+--------+----------------+
# | id | video_id                   | apitoken | is_embeddable | reason | privacy_status |
# +----+----------------------------+----------+---------------+--------+----------------+
# | 1  | dQw4w9WgXcQ                | token123 | 1             | NULL   | public         |
# +----+----------------------------+----------+---------------+--------+----------------+

exit;
exit
```

---

## 🛡️ STEP 9: CHECK ERROR LOGS

```bash
ssh innovat8@innovat8.com

cd /home/innovat8/public_html/church.innovative.ng

# Check for any errors
tail -50 writable/logs/log-*.log

# Should NOT see:
# ❌ "tbl_video_checks doesn't exist"
# ❌ "Call to getRow() on bool"
# ❌ "YouTubeService not found"

# Should see:
# ✅ Normal application logs
# ✅ Successful operations

# Watch logs in real-time (optional)
tail -f writable/logs/log-*.log

# Press Ctrl+C to stop watching

exit
```

---

## ⏮️ STEP 10: ROLLBACK (If Needed)

**If something goes wrong, rollback:**

```bash
ssh innovat8@innovat8.com

cd /home/innovat8/public_html/church.innovative.ng

# Rollback to previous migration
php spark migrate --version 2025-12-18-000001

# Or rollback all
php spark migrate:refresh

# Verify status
php spark migrate:status

exit
```

**If database corrupted, restore from backup:**
```bash
mysql -u innovat8_user -p innovat8_churchapp_db < /home/innovat8/backups/backup_file.sql

# Or via SSH
ssh innovat8@innovat8.com
mysql -u innovat8_user -p innovat8_churchapp_db < /home/innovat8/backups/backup_file.sql
exit
```

---

## ✅ DEPLOYMENT COMPLETE CHECKLIST

After all steps, verify:

- [x] Files uploaded to correct folders
- [x] Migration executed successfully
- [x] Table `tbl_video_checks` created
- [x] All 10 fields present in table
- [x] Indexes created
- [x] Cache cleared
- [x] PHP restarted
- [x] YouTube video upload works
- [x] API returns media successfully
- [x] Database contains cached check
- [x] No errors in logs
- [x] Tests passed

---

## 📞 QUICK REFERENCE

| Task | Command |
|------|---------|
| Connect SSH | `ssh innovat8@innovat8.com` |
| Navigate project | `cd /home/innovat8/public_html/church.innovative.ng` |
| Run migration | `php spark migrate` |
| Check status | `php spark migrate:status` |
| Rollback | `php spark migrate --version 2025-12-18-000001` |
| Clear cache | `rm -rf writable/cache/*` |
| Restart PHP | `sudo systemctl restart php-fpm` |
| Check logs | `tail -50 writable/logs/log-*.log` |
| MySQL connect | `mysql -u innovat8_user -p innovat8_churchapp_db` |
| Test upload | Go to admin → Videos → Add New → YouTube Video |

---

## 🎯 EXPECTED RESULTS AFTER DEPLOYMENT

✅ YouTube videos upload successfully
✅ Video embeddability cached in database
✅ API returns media with video metadata
✅ No "Table doesn't exist" errors
✅ No "Call to getRow() on bool" errors
✅ Admin panel fully functional
✅ Mobile app receives proper video data

---

## ⏱️ ESTIMATED TIME

| Step | Time |
|------|------|
| Upload files | 2 minutes |
| Verify upload | 1 minute |
| Backup database | 2 minutes |
| Run migration | 1 minute |
| Verify migration | 2 minutes |
| Clear cache & restart | 2 minutes |
| Test | 3 minutes |
| **TOTAL** | **~13 minutes** |

---

## 💡 TIPS

1. **Before Deployment**: Take screenshot of database
2. **After Deployment**: Compare screenshots to verify changes
3. **If Stuck**: Check error logs first (`tail -50 writable/logs/`)
4. **Keep Backup**: Keep database backup for at least 7 days
5. **Monitor**: Watch logs for 1 hour after deployment
6. **Documentation**: Keep this guide for reference

---

**Status**: Ready to deploy
**Confidence**: 100%
**Support**: Check logs if issues occur
