# 📚 CODE SNIPPETS & EXAMPLES

Quick reference for using the stream URL type safety implementation.

---

## 🎯 In Controllers

### Fetch and Return Livestreams
```php
<?php
namespace App\Controllers;

use App\Models\Livestream_model;

class Livestream extends BaseController
{
    public function fetchLivestreams()
    {
        $livestreammodel = new Livestream_model();
        $results = $livestreammodel->fetch_livestreams_app(0, $this->apitoken);
        
        // Sanitize output - ensures link is always string
        $results = array_map(function ($item) {
            $item->link = isset($item->link) 
                            ? (string) $item->link 
                            : '';
            return $item;
        }, $results);
        
        return json_encode([
            'status' => 'ok',
            'livestreams' => $results
        ]);
    }
}
```

### Create Livestream with Validation
```php
public function createLivestream()
{
    $link = (string) $this->request->getVar('link'); // Force string
    
    // Validate
    $rules = [
        'link' => 'permit_empty|string',
    ];
    
    if (!$this->validate($rules)) {
        return $this->failValidationErrors($this->validator->getErrors());
    }
    
    // Save
    $info = [
        'apitoken' => $this->apitoken,
        'link' => $link,
        'title' => $this->request->getVar('title'),
    ];
    
    $livestreammodel = new Livestream_model();
    $livestreammodel->addNewLivestream($info);
    
    return json_encode(['status' => 'ok']);
}
```

### Using Helper Functions
```php
<?php
namespace App\Controllers;

class Videos extends BaseController
{
    public function saveYoutubeVideo()
    {
        helper('StreamUrl');
        
        $youtube_url = $this->request->getVar('url');
        
        // Extract just the video ID
        $video_id = extractYoutubeId($youtube_url);
        
        // Validate it
        if (!isValidYoutubeId($video_id)) {
            return $this->fail('Invalid YouTube URL or ID');
        }
        
        // Save the ID (not full URL)
        $data = [
            'video_id' => $video_id,
            'title' => $this->request->getVar('title'),
        ];
        
        return json_encode($data);
    }
}
```

---

## 🗂️ In Models

### Model with Type Casting
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class Livestream_model extends Model
{
    protected $table = 'tbl_livestreams';
    
    // Force stream URL fields to always be strings
    protected $casts = [
        'link' => 'string',
    ];
    
    public function fetch_livestreams_app($page = 0, $apitoken = "")
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_livestreams');
        $builder->where('apitoken', $apitoken);
        $builder->where('status', 0);
        
        if ($page != 0) {
            $builder->limit(20, $page * 20);
        } else {
            $builder->limit(20);
        }
        
        $query = $builder->get();
        $result = $query->getResult();
        
        // $casts automatically ensures link is string
        return $result;
    }
}
```

---

## 🛠️ Using Helper Functions

### In Views
```php
<?php
helper('StreamUrl');
?>

<div class="livestream">
    <h3><?= $livestream->title; ?></h3>
    <p>Video ID: <?= $livestream->link; ?></p>
    
    <?php if (isValidYoutubeId($livestream->link)): ?>
        <iframe width="560" height="315" 
            src="https://www.youtube.com/embed/<?= $livestream->link; ?>" 
            frameborder="0" allowfullscreen></iframe>
    <?php endif; ?>
</div>
```

### Extract YouTube ID
```php
helper('StreamUrl');

$url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
$id = extractYoutubeId($url);
echo $id;  // Output: dQw4w9WgXcQ

// Works with different formats
extractYoutubeId('https://youtu.be/dQw4w9WgXcQ');     // dQw4w9WgXcQ
extractYoutubeId('https://www.youtube.com/embed/dQw4w9WgXcQ'); // dQw4w9WgXcQ
extractYoutubeId('dQw4w9WgXcQ');                       // dQw4w9WgXcQ
```

### Normalize Any Stream URL
```php
helper('StreamUrl');

// All return empty string
normalizeStreamUrl(0);          // ''
normalizeStreamUrl(null);       // ''
normalizeStreamUrl('');         // ''
normalizeStreamUrl('0');        // ''

// Returns as-is (string)
normalizeStreamUrl('dQw4w9WgXcQ');              // 'dQw4w9WgXcQ'
normalizeStreamUrl('https://youtube.com/...');  // 'https://youtube.com/...'
```

### Validate Stream URL
```php
helper('StreamUrl');

// Check if valid YouTube ID
if (isValidYoutubeId('dQw4w9WgXcQ')) {
    echo "Valid YouTube ID";
}

// Check if valid URL or ID
if (isValidStreamUrl('https://youtube.com/watch?v=dQw4w9WgXcQ')) {
    echo "Valid stream URL";
}

if (isValidStreamUrl('dQw4w9WgXcQ')) {
    echo "Valid YouTube ID";
}

if (!isValidStreamUrl(0)) {
    echo "Invalid";  // This will execute
}
```

---

## ✅ Validation Examples

### Simple String Validation
```php
$rules = [
    'link' => 'permit_empty|string',
];

if (!$this->validate($rules)) {
    return $this->fail($this->validator->getErrors());
}
```

### YouTube ID Specific Validation
```php
helper('StreamUrl');

$youtube_id = $this->request->getVar('youtube_id');

if (!empty($youtube_id) && !isValidYoutubeId($youtube_id)) {
    return $this->fail('Invalid YouTube video ID');
}
```

### URL or ID Validation
```php
helper('StreamUrl');

$stream_input = $this->request->getVar('stream_url');

if (!empty($stream_input) && !isValidStreamUrl($stream_input)) {
    return $this->fail('Invalid stream URL or YouTube ID');
}
```

---

## 📊 Database Examples

### Query for String Links
```sql
-- Find all livestreams with valid string links
SELECT id, title, link 
FROM tbl_livestreams 
WHERE link IS NOT NULL 
  AND link != '' 
  AND link != '0'
ORDER BY id DESC;

-- Find problematic data
SELECT id, title, link 
FROM tbl_livestreams 
WHERE link IS NULL 
   OR link = ''
   OR link = '0';
```

### Update Examples
```sql
-- Set a livestream's link
UPDATE tbl_livestreams 
SET link = 'dQw4w9WgXcQ' 
WHERE id = 1;

-- Clear all invalid links
UPDATE tbl_livestreams 
SET link = NULL 
WHERE link = '0' OR link = '';

-- Copy from another column
UPDATE tbl_livestreams 
SET link = source 
WHERE source IS NOT NULL 
  AND link IS NULL;
```

---

## 🧪 Testing Examples

### Test Helper Function
```php
<?php
namespace Tests\Feature;

use Tests\TestCase;

class StreamUrlHelperTest extends TestCase
{
    public function testExtractYoutubeId()
    {
        helper('StreamUrl');
        
        $this->assertEquals(
            'dQw4w9WgXcQ',
            extractYoutubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
        
        $this->assertEquals(
            'dQw4w9WgXcQ',
            extractYoutubeId('dQw4w9WgXcQ')
        );
    }
    
    public function testNormalizeStreamUrl()
    {
        helper('StreamUrl');
        
        $this->assertEquals('', normalizeStreamUrl(0));
        $this->assertEquals('', normalizeStreamUrl(null));
        $this->assertIsString(normalizeStreamUrl('test'));
    }
    
    public function testIsValidYoutubeId()
    {
        helper('StreamUrl');
        
        $this->assertTrue(isValidYoutubeId('dQw4w9WgXcQ'));
        $this->assertFalse(isValidYoutubeId('invalid'));
        $this->assertFalse(isValidYoutubeId(''));
    }
}
```

### Test API Response Type
```php
<?php
namespace Tests\Feature;

class ApiTest extends TestCase
{
    public function testFetchLivestreamsReturnsStringLink()
    {
        // Assuming you're logged in as admin
        $response = $this->get('/api/fetchlivestreams?apitoken=test_token');
        
        $json = json_decode($response->getBody(), true);
        
        // Verify structure
        $this->assertArrayHasKey('livestreams', $json);
        $this->assertIsArray($json['livestreams']);
        
        // Verify each livestream has string link
        foreach ($json['livestreams'] as $livestream) {
            $this->assertArrayHasKey('link', $livestream);
            $this->assertIsString($livestream['link']);
            
            // Verify it's not a numeric string that got parsed as int
            $decoded = json_encode(['link' => $livestream['link']]);
            $this->assertStringContainsString('"link":"', $decoded);
        }
    }
}
```

---

## 🔄 Migration & Cleanup

### Running Migration
```bash
# Check migration status
php spark migrate:status

# Run all pending migrations
php spark migrate

# Rollback last migration
php spark migrate:rollback

# Refresh (rollback all, then migrate)
php spark migrate:refresh
```

### Using Cleanup Command
```bash
# Dry run - see what would be cleaned
php spark clean:streamurls --dry-run

# Actually run cleanup
php spark clean:streamurls

# With specific apitoken (if supported)
php spark clean:streamurls --apitoken=YOUR_TOKEN
```

---

## 🚀 Full Workflow Example

### Step 1: Save New Livestream
```php
// Controller
helper('StreamUrl');

$input_url = $_POST['youtube_url'];  // From user
$video_id = extractYoutubeId($input_url);

if (!isValidYoutubeId($video_id)) {
    return error("Invalid YouTube URL");
}

$livestream_model = new Livestream_model();
$livestream_model->insert([
    'link' => $video_id,  // Save just the ID
    'title' => $_POST['title'],
    'apitoken' => $this->apitoken,
]);
```

### Step 2: Retrieve from API
```php
// API Controller
$livestreams = $livestream_model->fetch_livestreams_app(0, $this->apitoken);

// Sanitize
$livestreams = array_map(function ($item) {
    $item->link = isset($item->link) ? (string) $item->link : '';
    return $item;
}, $livestreams);

// Return
echo json_encode(['status' => 'ok', 'livestreams' => $livestreams]);
```

### Step 3: Use in Frontend
```dart
// Flutter
final response = await api.get('/api/fetchlivestreams');
final livestream = response['livestreams'][0];

// link is guaranteed to be String
String videoId = livestream['link'];  // No casting needed!

if (videoId.isNotEmpty) {
    // Load YouTube video
    youtubePlayer.load(videoId);
}
```

---

## 💡 Best Practices

### DO ✅
```php
// 1. Load helper when needed
helper('StreamUrl');

// 2. Extract ID from URL before saving
$id = extractYoutubeId($url);

// 3. Validate before saving
if (!isValidYoutubeId($id)) {
    // Reject
}

// 4. Cast in models
protected $casts = ['link' => 'string'];

// 5. Sanitize in API
$item->link = (string) $item->link;
```

### DON'T ❌
```php
// 1. Don't save full URLs to database
// WRONG: $data['link'] = 'https://youtube.com/watch?v=...';

// 2. Don't forget to cast
// WRONG: echo $item->link;  // Might be int!

// 3. Don't mix validation approaches
// WRONG: if ($link) { /* Doesn't validate type */ }

// 4. Don't assume types in API responses
// WRONG: $id = (int) $response['link'];

// 5. Don't ignore NULL/0 values
// WRONG: if ($item->link) { /* 0 is falsy */ }
```

---

**Ready to use! Pick the snippet that matches your use case and adapt as needed.** 🎯
