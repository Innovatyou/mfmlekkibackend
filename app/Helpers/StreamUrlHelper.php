<?php

/**
 * Stream URL Helper
 * 
 * Utilities for handling YouTube video IDs and stream URLs
 * Ensures stream URLs are always returned as strings, never as integers
 */

if (!function_exists('extractYoutubeId')) {
    /**
     * Extract YouTube video ID from various YouTube URL formats
     * 
     * Supports:
     * - https://www.youtube.com/watch?v=dQw4w9WgXcQ
     * - https://youtu.be/dQw4w9WgXcQ
     * - https://www.youtube.com/embed/dQw4w9WgXcQ
     * - dQw4w9WgXcQ (already just the ID)
     * 
     * @param string $url YouTube URL or video ID
     * @return string YouTube video ID (always string)
     */
    function extractYoutubeId(string $url = ''): string
    {
        if (empty($url)) {
            return '';
        }

        // If it looks like just a video ID (11-12 character alphanumeric string), return as-is
        if (preg_match('/^[a-zA-Z0-9_-]{11,12}$/', $url)) {
            return (string) $url;
        }

        // Try to extract from various YouTube URL formats
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11,12})/',
            '/(?:youtube\.com\/watch\?.*v=)([a-zA-Z0-9_-]{11,12})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return (string) $matches[1];
            }
        }

        // If no match found, return the original URL as string
        return (string) $url;
    }
}

if (!function_exists('normalizeStreamUrl')) {
    /**
     * Normalize stream URL to ensure it's a valid string
     * 
     * Ensures:
     * - Returns string type (never int, never null)
     * - Empty values become empty strings
     * - Zeros become empty strings
     * 
     * @param mixed $url Stream URL or YouTube ID
     * @return string Normalized stream URL (always string)
     */
    function normalizeStreamUrl($url = ''): string
    {
        // Handle null, false, or integer 0
        if ($url === null || $url === false || $url === 0 || $url === '0') {
            return '';
        }

        // Convert to string
        $url = (string) $url;

        // Trim whitespace
        $url = trim($url);

        // Return as string
        return $url;
    }
}

if (!function_exists('isValidYoutubeId')) {
    /**
     * Check if string is a valid YouTube video ID format
     * 
     * @param string $id YouTube video ID
     * @return bool True if valid YouTube ID format
     */
    function isValidYoutubeId(string $id = ''): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9_-]{11,12}$/', $id);
    }
}

if (!function_exists('isValidStreamUrl')) {
    /**
     * Check if string is a valid stream URL or YouTube ID
     * 
     * @param mixed $url Stream URL or YouTube ID to validate
     * @return bool True if valid URL or YouTube ID
     */
    function isValidStreamUrl($url = ''): bool
    {
        if (empty($url) || !is_string($url)) {
            return false;
        }

        $url = trim($url);

        // Check if valid URL
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        // Check if valid YouTube ID
        if (isValidYoutubeId($url)) {
            return true;
        }

        return false;
    }
}
