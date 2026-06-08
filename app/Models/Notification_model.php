<?php
namespace App\Models;

use CodeIgniter\Model;

class Notification_model extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    protected $allowedFields = [
        'user_id', 'action', 'title', 'message', 'payload_json', 'is_read', 'created_at'
    ];
    protected $useTimestamps = false;

    // Create a notification record
    public function createNotification($data)
    {
        return $this->insert($data);
    }

    // Get notifications for a user, paginated, newest first
    public function getUserNotifications($user_id, $page = 0, $pageSize = 20)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->findAll($pageSize, $page * $pageSize);
    }

    // Get unread count for a user
    public function getUnreadCount($user_id)
    {
        return $this->where(['user_id' => $user_id, 'is_read' => false])->countAllResults();
    }

    // Mark all as read for a user
    public function markAllAsRead($user_id)
    {
        return $this->where('user_id', $user_id)->set('is_read', true)->update();
    }

    // Mark a specific notification as read
    public function markAsRead($notification_id, $user_id)
    {
        return $this->where(['notification_id' => $notification_id, 'user_id' => $user_id])->set('is_read', true)->update();
    }
}
