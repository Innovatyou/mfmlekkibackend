<?php

namespace App\Models;

use App\Models\Basemodel;

class YouTube_model extends Basemodel
{
    public function getCheck($video_id)
    {
        try {
            $db = \Config\Database::connect('default');

            // Check if table exists
            if (!$db->tableExists('tbl_video_checks')) {
                if (function_exists('log_message')) {
                    log_message('warning', 'YouTube_model::getCheck - Table tbl_video_checks does not exist yet. Run migrations.');
                }
                return null;
            }

            $builder = $db->table('tbl_video_checks');
            $builder->select('*');
            $builder->where('video_id', $video_id);
            $query = $builder->get();
            
            if (!$query) {
                return null;
            }
            
            $row = $query->getRow(0);
            return $row;
        } catch (\Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', 'YouTube_model::getCheck DB error: ' . $e->getMessage());
            }
            return null;
        }
    }

    public function setCheck($video_id, $is_embeddable, $reason = null, $privacy_status = null, $content_details = null)
    {
        try {
            $db = \Config\Database::connect('default');

            // Check if table exists
            if (!$db->tableExists('tbl_video_checks')) {
                if (function_exists('log_message')) {
                    log_message('warning', 'YouTube_model::setCheck - Table tbl_video_checks does not exist yet. Run migrations.');
                }
                return false;
            }

            $builder = $db->table('tbl_video_checks');
            $now = date('Y-m-d H:i:s');

            $data = [
                'video_id' => $video_id,
                'is_embeddable' => $is_embeddable ? 1 : 0,
                'reason' => $reason,
                'privacy_status' => $privacy_status,
                'content_details' => $content_details ? json_encode($content_details) : null,
                'checked_at' => $now,
                'created_at' => $now,
            ];

            // Upsert
            $exists = $this->getCheck($video_id);
            if ($exists) {
                $builder->where('id', $exists->id);
                $builder->update($data);
            } else {
                $builder->insert($data);
            }
            return true;
        } catch (\Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', 'YouTube_model::setCheck DB error: ' . $e->getMessage());
            }
            return false;
        }
    }
}
