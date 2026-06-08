-- notifications table migration
CREATE TABLE IF NOT EXISTS notifications (
    notification_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    action VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    payload_json JSON NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_created_at_is_read (user_id, created_at DESC, is_read),
    INDEX idx_user_is_read (user_id, is_read)
);

-- Optional: backfill script placeholder
-- INSERT INTO notifications (user_id, action, title, message, payload_json, is_read, created_at)
-- SELECT ... FROM tbl_notifications WHERE ... ;
