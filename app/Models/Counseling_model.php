<?php

namespace App\Models;

use App\Models\Basemodel;

class Counseling_model extends Basemodel
{
    public $status;
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->status  = 'error';
        $this->message = 'An error occurred.';
    }

    public function getDashboardStats()
    {
        $db = \Config\Database::connect('default');
        return [
            'total'           => (int) $db->table('tbl_counseling_cases')->countAllResults(),
            'open'            => (int) $db->table('tbl_counseling_cases')->where('status', 'open')->countAllResults(),
            'in_progress'     => (int) $db->table('tbl_counseling_cases')->where('status', 'in_progress')->countAllResults(),
            'closed_month'    => (int) $db->table('tbl_counseling_cases')->where('status', 'closed')->where('closed_at >=', date('Y-m-01 00:00:00'))->countAllResults(),
            'reminders_today' => (int) $db->table('tbl_counseling_reminders')->where('reminder_date', date('Y-m-d'))->where('is_done', 0)->countAllResults(),
            'overdue'         => (int) $db->query(
                "SELECT COUNT(*) AS num FROM tbl_counseling_cases
                 WHERE status IN ('open','in_progress')
                   AND next_followup IS NOT NULL
                   AND next_followup < CURDATE()"
            )->getRow()->num,
        ];
    }

    public function getTodayReminders()
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_reminders r')
            ->select('r.*, c.member_name, c.title AS case_title, c.status AS case_status')
            ->join('tbl_counseling_cases c', 'c.id = r.case_id', 'left')
            ->where('r.reminder_date', date('Y-m-d'))
            ->where('r.is_done', 0)
            ->orderBy('r.reminder_date', 'ASC')
            ->get()->getResult();
    }

    public function getUpcomingReminders($days = 7)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_reminders r')
            ->select('r.*, c.member_name, c.title AS case_title')
            ->join('tbl_counseling_cases c', 'c.id = r.case_id', 'left')
            ->where('r.is_done', 0)
            ->where('r.reminder_date >=', date('Y-m-d'))
            ->where('r.reminder_date <=', date('Y-m-d', strtotime("+{$days} days")))
            ->orderBy('r.reminder_date', 'ASC')
            ->get()->getResult();
    }

    public function getCaseListingData($search, $start, $length)
    {
        $db = \Config\Database::connect('default');
        $q  = $db->table('tbl_counseling_cases');
        if ($search !== '') {
            $q->groupStart()
                ->like('member_name', $search)
                ->orLike('member_email', $search)
                ->orLike('title', $search)
                ->orLike('assigned_to', $search)
              ->groupEnd();
        }
        return $q->orderBy('opened_at', 'DESC')->limit($length, $start)->get()->getResult();
    }

    public function getTotalCaseList($search)
    {
        $db = \Config\Database::connect('default');
        $q  = $db->table('tbl_counseling_cases');
        if ($search !== '') {
            $q->groupStart()
                ->like('member_name', $search)
                ->orLike('member_email', $search)
                ->orLike('title', $search)
                ->orLike('assigned_to', $search)
              ->groupEnd();
        }
        return (int) $q->countAllResults();
    }

    public function getCase($id)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_cases')->where('id', (int) $id)->get()->getRow();
    }

    public function createCase($data)
    {
        $db = \Config\Database::connect('default');
        $data['opened_at'] = date('Y-m-d H:i:s');
        try {
            $db->table('tbl_counseling_cases')->insert($data);
            $this->status  = 'ok';
            $this->message = 'Counseling case opened successfully.';
            return $db->insertID();
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Failed to create case.';
            return false;
        }
    }

    public function updateCase($id, $data)
    {
        $db = \Config\Database::connect('default');
        try {
            $db->table('tbl_counseling_cases')->where('id', (int) $id)->update($data);
            $this->status  = 'ok';
            $this->message = 'Case updated.';
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Update failed.';
        }
    }

    public function deleteCase($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_counseling_sessions')->where('case_id', (int) $id)->delete();
        $db->table('tbl_counseling_reminders')->where('case_id', (int) $id)->delete();
        $db->table('tbl_counseling_cases')->where('id', (int) $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Case deleted.';
    }

    public function getCaseSessions($case_id)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_sessions')
            ->where('case_id', (int) $case_id)
            ->orderBy('session_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get()->getResult();
    }

    public function logSession($data)
    {
        $db = \Config\Database::connect('default');
        $data['created_at'] = date('Y-m-d H:i:s');
        try {
            $db->table('tbl_counseling_sessions')->insert($data);
            $this->status  = 'ok';
            $this->message = 'Session logged successfully.';
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Failed to log session.';
        }
    }

    public function deleteSession($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_counseling_sessions')->where('id', (int) $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Session deleted.';
    }

    public function getCaseReminders($case_id)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_reminders')
            ->where('case_id', (int) $case_id)
            ->orderBy('is_done', 'ASC')
            ->orderBy('reminder_date', 'ASC')
            ->get()->getResult();
    }

    public function addReminder($data)
    {
        $db = \Config\Database::connect('default');
        $data['created_at'] = date('Y-m-d H:i:s');
        try {
            $db->table('tbl_counseling_reminders')->insert($data);
            $this->status  = 'ok';
            $this->message = 'Reminder added.';
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Failed to add reminder.';
        }
    }

    public function markReminderDone($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_counseling_reminders')->where('id', (int) $id)->update(['is_done' => 1]);
        $this->status  = 'ok';
        $this->message = 'Reminder marked as done.';
    }

    public function deleteReminder($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_counseling_reminders')->where('id', (int) $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Reminder deleted.';
    }

    // Mobile API — member submits a counseling request from the app
    public function submitRequest($data)
    {
        $db = \Config\Database::connect('default');
        $data['opened_at'] = date('Y-m-d H:i:s');
        $data['status']    = 'open';
        $data['is_confidential'] = 1;
        try {
            $db->table('tbl_counseling_cases')->insert($data);
            $this->status  = 'ok';
            $this->message = 'Your counseling request has been submitted. A pastor will be in touch soon.';
            return $db->insertID();
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Failed to submit request.';
            return false;
        }
    }

    // Mobile API — member views their own cases (no confidential notes exposed)
    public function getMemberCases($email)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_counseling_cases')
            ->select('id, category, title, status, priority, assigned_to, opened_at, next_followup')
            ->where('member_email', $email)
            ->orderBy('opened_at', 'DESC')
            ->get()->getResult();
    }

    // ── Video / Audio Session Scheduling ─────────────────────────────

    public function scheduleVideoSession($data)
    {
        $db = \Config\Database::connect('default');
        $data['session_type'] = 'video';
        $data['meeting_status'] = 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');
        try {
            $db->table('tbl_counseling_sessions')->insert($data);
            $this->status  = 'ok';
            $this->message = 'Video session scheduled successfully.';
            return $db->insertID();
        } catch (\Exception $e) {
            $this->status  = 'error';
            $this->message = 'Failed to schedule video session.';
            return false;
        }
    }

    public function updateMeetingStatus($id, $status)
    {
        $db      = \Config\Database::connect('default');
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            $this->status  = 'error';
            $this->message = 'Invalid status.';
            return;
        }
        $db->table('tbl_counseling_sessions')->where('id', (int) $id)->update(['meeting_status' => $status]);
        $this->status  = 'ok';
        $this->message = 'Meeting status updated.';
    }

    public function getUpcomingVideoSessions($limit = 6)
    {
        $db = \Config\Database::connect('default');
        if (!$db->fieldExists('meeting_status', 'tbl_counseling_sessions')) {
            return [];
        }
        return $db->table('tbl_counseling_sessions s')
            ->select('s.*, c.member_name, c.title AS case_title, c.id AS case_id_ref')
            ->join('tbl_counseling_cases c', 'c.id = s.case_id', 'left')
            ->where('s.session_type', 'video')
            ->whereIn('s.meeting_status', ['pending', 'confirmed'])
            ->where('s.meeting_scheduled_at >=', date('Y-m-d H:i:s'))
            ->orderBy('s.meeting_scheduled_at', 'ASC')
            ->limit($limit)
            ->get()->getResult();
    }

    // Mobile API — member fetches their upcoming scheduled video calls
    public function getMemberUpcomingVideoSessions($email)
    {
        $db = \Config\Database::connect('default');
        if (!$db->fieldExists('meeting_status', 'tbl_counseling_sessions')) {
            return [];
        }
        return $db->table('tbl_counseling_sessions s')
            ->select('s.id, s.meeting_platform, s.meeting_link, s.meeting_scheduled_at, s.meeting_status, s.duration_minutes, c.title AS case_title, c.assigned_to')
            ->join('tbl_counseling_cases c', 'c.id = s.case_id', 'left')
            ->where('c.member_email', $email)
            ->where('s.session_type', 'video')
            ->whereIn('s.meeting_status', ['pending', 'confirmed'])
            ->where('s.meeting_scheduled_at >=', date('Y-m-d H:i:s'))
            ->orderBy('s.meeting_scheduled_at', 'ASC')
            ->get()->getResult();
    }
}
