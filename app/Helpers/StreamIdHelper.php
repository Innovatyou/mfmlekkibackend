<?php

namespace App\Helpers;

/**
 * StreamUrlHelper - Validates and normalizes stream URLs and YouTube video IDs
 * 
 * This helper ensures all stream/video ID fields are:
 * 1. Always returned as STRING (never int, null, 0, false)
 * 2. Properly formatted YouTube video IDs
 * 3. Valid and embeddable
 */
class StreamIdHelper
{
    /**
     * Validate if string is a valid YouTube video ID
     * YouTube IDs are 11-12 alphanumeric characters (a-zA-Z0-9_-)
     * 
     * @param mixed $id - Value to validate
     * @return bool - True if valid YouTube ID format
     */
    public static function isValidYoutubeId($id): bool
    {
        if (!is_string($id)) {
            return false;
        }
        
        // YouTube ID format: 11-12 chars, alphanumeric + _ -
        return (bool) preg_match('/^[a-zA-Z0-9_-]{11,12}$/', trim($id));
    }

    /**
     * Normalize stream ID to guaranteed STRING type
     * 
     * Converts:
     * - 12345 (int) → "12345" (string)
     * - null → "" (empty string)
     * - 0 → "" (empty string)
     * - false → "" (empty string)
     * - "  video_id  " → "video_id" (trimmed string)
     * 
     * @param mixed $value - Raw value from database
     * @return string - Guaranteed string value
     */
    public static function normalizeStreamId($value): string
    {
        // Handle null, false, 0
        if ($value === null || $value === false || $value === 0 || $value === "0") {
            return '';
        }

        // Cast to string and trim
        $normalized = trim((string) $value);

        // If empty after trim, return empty string
        if ($normalized === '' || $normalized === '0') {
            return '';
        }

        return $normalized;
    }

    /**
     * Sanitize and validate stream ID for API response
     * 
     * @param mixed $id - Raw ID from database
     * @return array - ['value' => string, 'is_valid' => bool, 'reason' => string]
     */
    public static function sanitizeStreamId($id): array
    {
        $normalized = self::normalizeStreamId($id);

        // Empty ID is "valid" but empty
        if ($normalized === '') {
            return [
                'value' => '',
                'is_valid' => false,
                'reason' => 'Empty or null ID',
                'type' => 'string' // Always string type
            ];
        }

        // Check valid YouTube ID format
        if (!self::isValidYoutubeId($normalized)) {
            return [
                'value' => $normalized,
                'is_valid' => false,
                'reason' => 'Invalid YouTube ID format (should be 11-12 alphanumeric chars)',
                'type' => 'string' // Always string type
            ];
        }

        return [
            'value' => $normalized,
            'is_valid' => true,
            'reason' => null,
            'type' => 'string' // Always string type
        ];
    }

    /**
     * Extract YouTube video ID from various URL formats
     * 
     * Handles:
     * - https://www.youtube.com/watch?v=dQw4w9WgXcQ
     * - https://youtu.be/dQw4w9WgXcQ
     * - https://www.youtube.com/embed/dQw4w9WgXcQ
     * - dQw4w9WgXcQ (already just ID)
     * 
     * @param string $url - YouTube URL or ID
     * @return string|null - Extracted ID or null if invalid
     */
    public static function extractYoutubeId(string $url): ?string
    {
        // Already just an ID?
        if (self::isValidYoutubeId($url)) {
            return $url;
        }

        // Try to extract from URLs
        $patterns = [
            // Standard watch URL
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11,12})/',
            // Short youtu.be URL
            '/youtu\.be\/([a-zA-Z0-9_-]{11,12})/',
            // Embed URL
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11,12})/',
            // General pattern
            '/([a-zA-Z0-9_-]{11,12})/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                if (isset($matches[1]) && self::isValidYoutubeId($matches[1])) {
                    return $matches[1];
                }
            }
        }

        return null;
    }

    /**
     * Validate entire array of stream objects (for API responses)
     * Ensures all have string type IDs
     * 
     * @param array $streams - Array of stream objects
     * @return array - Validated streams with all IDs as strings
     */
    public static function validateStreamArray(array $streams): array
    {
        return array_map(function ($stream) {
            // Ensure it's an object or array
            $isObject = is_object($stream);
            $data = (array) $stream;

            // Normalize critical fields
            $fields = ['source', 'link', 'video_id', 'streamUrl'];
            
            foreach ($fields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = self::normalizeStreamId($data[$field]);
                }
            }

            // Convert back to object if it was originally
            return $isObject ? (object) $data : $data;
        }, $streams);
    }

    /**
     * Generate validation report for stream fields
     * 
     * @param array $records - Records to validate
     * @return array - Validation report with stats and issues
     */
    public static function generateValidationReport(array $records): array
    {
        $report = [
            'total_records' => count($records),
            'valid_ids' => 0,
            'invalid_ids' => 0,
            'empty_ids' => 0,
            'type_mismatches' => 0,
            'issues' => [],
            'summary' => ''
        ];

        foreach ($records as $index => $record) {
            $id = $record->source ?? $record->link ?? null;
            $validation = self::sanitizeStreamId($id);

            if ($validation['is_valid']) {
                $report['valid_ids']++;
            } elseif ($validation['value'] === '') {
                $report['empty_ids']++;
            } else {
                $report['invalid_ids']++;
                $report['issues'][] = [
                    'record_id' => $record->id ?? $index,
                    'value' => $validation['value'],
                    'reason' => $validation['reason']
                ];
            }

            // Check type
            if (!is_string($id) && $id !== null && $id !== false && $id !== 0) {
                $report['type_mismatches']++;
            }
        }

        // Generate summary
        $report['summary'] = sprintf(
            'Valid: %d | Invalid: %d | Empty: %d | Type Issues: %d',
            $report['valid_ids'],
            $report['invalid_ids'],
            $report['empty_ids'],
            $report['type_mismatches']
        );

        return $report;
    }
}
