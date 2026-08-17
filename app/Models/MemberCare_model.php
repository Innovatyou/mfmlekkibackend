<?php

namespace App\Models;

use App\Models\Basemodel;

class MemberCare_model extends Basemodel
{
    public $status;
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->status  = 'error';
        $this->message = 'An error occurred.';
    }

    // ─── Dashboard Stats ──────────────────────────────────────────────

    public function getDashboardStats()
    {
        $db = \Config\Database::connect('default');

        $totalMembers = (int) $db->table('tbl_members')->selectCount('id', 'num')->get()->getRow()->num;

        $upcomingBirthdays = count($this->getUpcomingBirthdays(7));

        // New members in last 30 days
        $newMembers = (int) $db->table('tbl_members')
            ->where('date_inserted >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();

        // Members with any care event in last 90 days
        $recentlyCared = (int) $db->table('tbl_member_care_events')
            ->select('COUNT(DISTINCT member_id) as num')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-90 days')))
            ->get()->getRow()->num;

        // Members who have never had a care event
        $neverCared = (int) $db->query(
            'SELECT COUNT(*) as num FROM tbl_members m
             LEFT JOIN tbl_member_care_events e ON e.member_id = m.id
             WHERE e.id IS NULL'
        )->getRow()->num;

        // Total care events logged
        $totalEvents = (int) $db->table('tbl_member_care_events')->countAllResults();

        return [
            'total_members'    => $totalMembers,
            'upcoming_bdays'   => $upcomingBirthdays,
            'new_members'      => $newMembers,
            'recently_cared'   => $recentlyCared,
            'never_cared'      => $neverCared,
            'total_events'     => $totalEvents,
        ];
    }

    // ─── Upcoming Birthdays ───────────────────────────────────────────

    public function getUpcomingBirthdays($days = 14)
    {
        $db      = \Config\Database::connect('default');
        $results = [];
        $today   = new \DateTime();

        for ($i = 0; $i <= $days; $i++) {
            $check = (clone $today)->modify("+{$i} days");
            $m = (int) $check->format('m');
            $d = (int) $check->format('d');

            $rows = $db->table('tbl_members')
                ->select('id, firstname, lastname, email, thumbnail, dob, month, day')
                ->where('month', $m)
                ->where('day', $d)
                ->get()->getResult();

            foreach ($rows as $r) {
                $r->days_until = $i;
                $r->bday_date  = $check->format('M j');
                if ($r->thumbnail) {
                    $r->thumbnail = base_url('uploads/members/' . $r->thumbnail);
                }
                $results[] = $r;
            }
        }

        return $results;
    }

    // ─── New Members (last N days) ────────────────────────────────────

    public function getNewMembers($days = 30)
    {
        $db = \Config\Database::connect('default');
        $rows = $db->table('tbl_members')
            ->select('id, firstname, lastname, email, thumbnail, date_inserted')
            ->where('date_inserted >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
            ->orderBy('date_inserted', 'DESC')
            ->get()->getResult();

        foreach ($rows as $r) {
            if ($r->thumbnail) {
                $r->thumbnail = base_url('uploads/members/' . $r->thumbnail);
            }
        }
        return $rows;
    }

    // ─── Members Needing Attention ────────────────────────────────────

    public function getMembersNeedingCare($limit = 20)
    {
        $db = \Config\Database::connect('default');

        // Aggregate in a subquery to avoid ONLY_FULL_GROUP_BY restrictions
        $rows = $db->query(
            "SELECT m.id, m.firstname, m.lastname, m.email, m.thumbnail,
                    agg.last_care_at,
                    COALESCE(agg.total_care_events, 0) AS total_care_events
             FROM tbl_members m
             LEFT JOIN (
                 SELECT member_id,
                        MAX(created_at) AS last_care_at,
                        COUNT(id)       AS total_care_events
                 FROM tbl_member_care_events
                 GROUP BY member_id
             ) agg ON agg.member_id = m.id
             WHERE agg.member_id IS NULL
                OR agg.last_care_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
             ORDER BY agg.last_care_at ASC
             LIMIT {$limit}"
        )->getResult();

        foreach ($rows as $r) {
            if ($r->thumbnail) {
                $r->thumbnail = base_url('uploads/members/' . $r->thumbnail);
            }
        }
        return $rows;
    }

    // ─── Engagement Score ─────────────────────────────────────────────

    public function getMemberEngagementScore($member_id)
    {
        $db    = \Config\Database::connect('default');
        $score = 0;
        $flags = [];

        $member = $db->table('tbl_members')->where('id', $member_id)->get()->getRow();
        if (!$member) return ['score' => 0, 'flags' => [], 'grade' => 'none'];

        // Group membership
        $inGroup = (int) $db->table('tbl_group_members')
            ->where('email', $member->email)->countAllResults();
        if ($inGroup > 0) { $score += 20; $flags[] = 'in_group'; }

        // Donation history
        $hasDonated = (int) $db->table('tbl_donations')
            ->where('email', $member->email)->countAllResults();
        if ($hasDonated > 0) { $score += 20; $flags[] = 'donor'; }

        // Prayer requests
        $hasPrayer = (int) $db->query(
            "SELECT COUNT(*) as num FROM tbl_prayers WHERE email = ?", [$member->email]
        )->getRow()->num;
        if ($hasPrayer > 0) { $score += 10; $flags[] = 'prayer'; }

        // tbl_testimonies has no email column — skip testimony flag

        // Care events
        $careCount = (int) $db->table('tbl_member_care_events')
            ->where('member_id', $member_id)->countAllResults();
        $score += min($careCount * 10, 30);
        if ($careCount > 0) $flags[] = 'cared';

        // Recent care (< 30 days) bonus
        $recentCare = (int) $db->table('tbl_member_care_events')
            ->where('member_id', $member_id)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();
        if ($recentCare > 0) { $score += 10; $flags[] = 'recent_care'; }

        $score = min($score, 100);

        if ($score >= 70)      $grade = 'high';
        elseif ($score >= 40)  $grade = 'medium';
        elseif ($score >= 10)  $grade = 'low';
        else                   $grade = 'none';

        return ['score' => $score, 'flags' => $flags, 'grade' => $grade];
    }

    // ─── Care Listing (DataTables) ────────────────────────────────────

    public function getCareListingData($search, $start, $length)
    {
        $db = \Config\Database::connect('default');

        // Subquery JOIN avoids ONLY_FULL_GROUP_BY — aggregates are computed once per member
        $builder = $db->table('tbl_members m')
            ->select('m.id, m.firstname, m.lastname, m.email, m.thumbnail,
                      agg.last_care_at,
                      COALESCE(agg.care_event_count, 0) AS care_event_count')
            ->join(
                '(SELECT member_id,
                         MAX(created_at) AS last_care_at,
                         COUNT(id)       AS care_event_count
                  FROM tbl_member_care_events
                  GROUP BY member_id) agg',
                'agg.member_id = m.id',
                'left'
            );

        if ($search !== '') {
            $builder->groupStart()
                ->like('m.firstname', $search)
                ->orLike('m.lastname', $search)
                ->orLike('m.email', $search)
                ->groupEnd();
        }

        $rows = $builder->orderBy('last_care_at', 'ASC')->limit($length, $start)->get()->getResult();

        foreach ($rows as $r) {
            if ($r->thumbnail) {
                $r->thumbnail = base_url('uploads/members/' . $r->thumbnail);
            }
            $engagement = $this->getMemberEngagementScore($r->id);
            $r->score   = $engagement['score'];
            $r->grade   = $engagement['grade'];
            $r->flags   = $engagement['flags'];
        }

        return $rows;
    }

    public function getTotalCareList($search)
    {
        $db = \Config\Database::connect('default');
        $builder = $db->table('tbl_members');
        if ($search !== '') {
            $builder->groupStart()
                ->like('firstname', $search)
                ->orLike('lastname', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        return (int) $builder->countAllResults();
    }

    // ─── Care Event Logging ───────────────────────────────────────────

    public function logCareEvent($data)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_member_care_events')->insert([
            'member_id'  => $data['member_id'],
            'event_type' => $data['event_type'],
            'note'       => $data['note'] ?? '',
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->status  = 'ok';
        $this->message = 'Care event logged successfully.';
        return $db->insertID();
    }

    public function deleteCareEvent($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_member_care_events')->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Care event deleted.';
    }

    // ─── Care Notes ───────────────────────────────────────────────────

    public function addNote($data)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_member_care_notes')->insert([
            'member_id'  => $data['member_id'],
            'note'       => $data['note'],
            'is_private' => $data['is_private'] ?? 0,
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->status  = 'ok';
        $this->message = 'Note saved successfully.';
        return $db->insertID();
    }

    public function deleteNote($id)
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_member_care_notes')->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Note deleted.';
    }

    // ─── Member Care History ──────────────────────────────────────────

    public function getMemberCareHistory($member_id)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_member_care_events')
            ->where('member_id', $member_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResult();
    }

    public function getMemberNotes($member_id)
    {
        $db = \Config\Database::connect('default');
        return $db->table('tbl_member_care_notes')
            ->where('member_id', $member_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResult();
    }

    // ─── Recent Care Activity ─────────────────────────────────────────

    public function getRecentCareActivity($limit = 15)
    {
        $db = \Config\Database::connect('default');
        return $db->query(
            "SELECT e.*, m.firstname, m.lastname, m.email, m.thumbnail
             FROM tbl_member_care_events e
             JOIN tbl_members m ON m.id = e.member_id
             ORDER BY e.created_at DESC
             LIMIT {$limit}"
        )->getResult();
    }
}
