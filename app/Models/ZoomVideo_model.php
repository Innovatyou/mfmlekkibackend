<?php

namespace App\Models;

use CodeIgniter\Model;

class ZoomVideo_model extends Model
{
    protected $table      = 'zoom_videos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $dateFormat  = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'title',
        'meeting_url',
        'start_time',
        'end_time',
        'privacy_status',
    ];

    /**
     * Get the latest Zoom video record
     * @return array|null
     */
    public function getLatestZoom()
    {
        return $this->select('id, title, meeting_url, start_time, end_time, privacy_status, updated_at')
            ->orderBy('updated_at', 'DESC')
            ->first();
    }

    /**
     * Check if current time falls within live service window
     * Rules:
     * - Must be Sunday (in app timezone, not UTC!)
     * - Must be between start_time and end_time (or start_time + 2.5 hours if end_time is null)
     * 
     * @param array $zoom Zoom record from database
     * @return bool true if service is LIVE
     */
    public function isServiceLive($zoom = null)
    {
        if (!$zoom) {
            $zoom = $this->getLatestZoom();
        }

        if (!$zoom) {
            return false;
        }

        // Use app timezone instead of UTC to match meeting schedule
        $appTimezone = app_timezone();
        $now = new \DateTime('now', new \DateTimeZone($appTimezone));
        $today = (int) $now->format('w'); // 0 = Sunday, 1 = Monday, etc.
        
        // Check if today is Sunday (0)
        if ($today !== 0) {
            return false;
        }

        $currentTime = $now->format('H:i:s');
        $startTime = $zoom['start_time'];
        
        // If end_time is not set, assume 2.5 hours duration (20:00 to 22:30)
        if ($zoom['end_time'] === null || $zoom['end_time'] === '') {
            // Default end time is 22:30 (10:30 PM) - 2.5 hours after 20:00
            $endTime = '22:30:00';
        } else {
            $endTime = $zoom['end_time'];
        }

        // Check if current time is within the live window
        return ($currentTime >= $startTime && $currentTime <= $endTime);
    }

    /**
     * Update Zoom meeting details (admin function)
     * 
     * @param array $data Array with 'title', 'meeting_url', 'start_time', 'end_time'
     * @return bool
     */
    public function updateZoomDetails($data)
    {
        $zoom = $this->getLatestZoom();
        
        if (!$zoom) {
            // Create new record if none exists
            return $this->insert($data);
        }

        // Update existing record
        return $this->update($zoom['id'], $data);
    }

    /**
     * Get admin panel data for Zoom settings
     * @return array
     */
    public function getAdminData()
    {
        return $this->getLatestZoom();
    }
}
