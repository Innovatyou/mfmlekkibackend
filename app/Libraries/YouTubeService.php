<?php

namespace App\Libraries;

/**
 * YouTube Video Service Library
 * 
 * Handles YouTube video validation and embeddability checks
 * Can be extended to use YouTube Data API v3 for real validation
 * 
 * For now, provides basic validation without API calls
 */
class YouTubeService
{
    /**
     * Check if a YouTube video is valid and embeddable
     * 
     * @param string $videoId YouTube video ID (11-12 characters)
     * @return array Array with keys: is_embeddable, reason, privacy_status, content_details
     */
    public function checkVideo(string $videoId = ''): array
    {
        // Basic validation - check if it looks like a YouTube ID
        if (empty($videoId) || !$this->isValidYoutubeId($videoId)) {
            return [
                'is_embeddable' => false,
                'reason' => 'Invalid YouTube video ID format',
                'privacy_status' => 'unknown',
                'content_details' => null,
            ];
        }

        // For production use with real YouTube API validation:
        // 1. Get API key from config
        // 2. Call YouTube Data API v3
        // 3. Check videos.list endpoint with id and part=status,processingDetails
        // 4. Return actual embeddability status
        
        // For now, return default - assume embeddable if valid format
        return [
            'is_embeddable' => true,
            'reason' => null,
            'privacy_status' => 'public',
            'content_details' => [
                'duration' => 'PT0S',
                'dimension' => '2d',
                'definition' => 'sd',
                'caption' => false,
            ],
        ];
    }

    /**
     * Validate YouTube video ID format
     * YouTube IDs are 11-12 characters containing letters, numbers, hyphens, underscores
     * 
     * @param string $videoId Video ID to validate
     * @return bool True if valid format
     */
    private function isValidYoutubeId(string $videoId): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9_-]{11,12}$/', $videoId);
    }

    /**
     * Get YouTube watch URL from video ID
     * 
     * @param string $videoId YouTube video ID
     * @return string Full YouTube watch URL
     */
    public function getWatchUrl(string $videoId): string
    {
        return 'https://www.youtube.com/watch?v=' . $videoId;
    }

    /**
     * Get YouTube embed URL from video ID
     * 
     * @param string $videoId YouTube video ID
     * @return string Full YouTube embed URL
     */
    public function getEmbedUrl(string $videoId): string
    {
        return 'https://www.youtube.com/embed/' . $videoId;
    }

    /**
     * Get YouTube thumbnail URL for video
     * 
     * @param string $videoId YouTube video ID
     * @param string $size Thumbnail size: default, medium (mqdefault), high (hqdefault), standard (sddefault), maxres (maxresdefault)
     * @return string Thumbnail image URL
     */
    public function getThumbnailUrl(string $videoId, string $size = 'hqdefault'): string
    {
        return "https://img.youtube.com/vi/{$videoId}/{$size}.jpg";
    }

    /**
     * Extract YouTube video ID from various URL formats
     * 
     * Supports:
     * - https://www.youtube.com/watch?v=dQw4w9WgXcQ
     * - https://youtu.be/dQw4w9WgXcQ
     * - https://www.youtube.com/embed/dQw4w9WgXcQ
     * - dQw4w9WgXcQ (already just the ID)
     * 
     * @param string $url YouTube URL or video ID
     * @return string|null Video ID if found, null otherwise
     */
    public function extractVideoId(string $url): ?string
    {
        // If it looks like just a video ID, return as-is
        if ($this->isValidYoutubeId($url)) {
            return $url;
        }

        // Try to extract from various YouTube URL formats
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11,12})/',
            '/(?:youtube\.com\/watch\?.*v=)([a-zA-Z0-9_-]{11,12})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
