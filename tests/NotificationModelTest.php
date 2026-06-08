<?php
use PHPUnit\Framework\TestCase;
use App\Models\Notification_model;

class NotificationModelTest extends TestCase
{
    protected $notificationModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationModel = new Notification_model();
    }

    public function testCreateNotification()
    {
        $data = [
            'user_id' => 'testuser',
            'action' => 'Event',
            'title' => 'Test Event',
            'message' => 'Event details',
            'payload_json' => json_encode(['action' => 'Event', 'id' => 1]),
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $id = $this->notificationModel->createNotification($data);
        $this->assertIsNumeric($id);
    }

    public function testGetUserNotifications()
    {
        $user_id = 'testuser';
        $results = $this->notificationModel->getUserNotifications($user_id, 0, 10);
        $this->assertIsArray($results);
    }

    public function testGetUnreadCount()
    {
        $user_id = 'testuser';
        $count = $this->notificationModel->getUnreadCount($user_id);
        $this->assertIsInt($count);
    }

    public function testMarkAllAsRead()
    {
        $user_id = 'testuser';
        $result = $this->notificationModel->markAllAsRead($user_id);
        $this->assertTrue($result !== false);
    }

    public function testMarkAsRead()
    {
        $user_id = 'testuser';
        $data = [
            'user_id' => $user_id,
            'action' => 'Event',
            'title' => 'Test Event',
            'message' => 'Event details',
            'payload_json' => json_encode(['action' => 'Event', 'id' => 2]),
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $id = $this->notificationModel->createNotification($data);
        $result = $this->notificationModel->markAsRead($id, $user_id);
        $this->assertTrue($result !== false);
    }
}
