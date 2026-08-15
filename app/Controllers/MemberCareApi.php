<?php

namespace App\Controllers;

use App\Models\MemberCare_model;

class MemberCareApi extends BaseController
{
    // ─── POST /api/myWellnessProfile ──────────────────────────────────────────

    public function myWellnessProfile()
    {
        try {
        $email = trim($this->request->getPost('email') ?? '');

        if (!$email) {
            $this->apiJson(['status' => 'error', 'message' => 'Email is required.']);
            return;
        }

        $db     = \Config\Database::connect('default');
        $member = $db->table('tbl_members')->where('email', $email)->get()->getRow();

        if (!$member) {
            $this->apiJson(['status' => 'error', 'message' => 'Member not found.']);
            return;
        }

        $model      = new MemberCare_model();
        $engagement = $model->getMemberEngagementScore($member->id);

        // Care events (most recent 10)
        $rawEvents  = $db->table('tbl_member_care_events')
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()->getResult();

        $careEvents = [];
        foreach ($rawEvents as $e) {
            $careEvents[] = [
                'event_type' => (string) ($e->event_type ?? ''),
                'note'       => (string) ($e->note       ?? ''),
                'created_by' => (string) ($e->created_by ?? ''),
                'created_at' => (string) ($e->created_at ?? ''),
            ];
        }

        // Activity counts
        $groupsCount = (int) $db->table('tbl_group_members')
            ->where('email', $email)->countAllResults();

        $prayersCount = (int) $db->query(
            'SELECT COUNT(*) AS num FROM tbl_prayers WHERE email = ?', [$email]
        )->getRow()->num;

        // tbl_testimonies has no email column — cannot count by email
        $testimonyCount = 0;

        $donationCount = (int) $db->table('tbl_donations')
            ->where('email', $email)->countAllResults();

        // Last care date
        $lastCareRow = $db->table('tbl_member_care_events')
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get()->getRow();

        $lastCareAt = $lastCareRow ? (string) $lastCareRow->created_at : null;

        $this->apiJson([
            'status'      => 'ok',
            'score'       => (int) $engagement['score'],
            'grade'       => (string) $engagement['grade'],
            'flags'       => (array) $engagement['flags'],
            'care_events' => $careEvents,
            'activity'    => [
                'groups_count'    => $groupsCount,
                'prayers_count'   => $prayersCount,
                'testimony_count' => $testimonyCount,
                'donation_count'  => $donationCount,
            ],
            'last_care_at' => $lastCareAt,
        ]);
        } catch (\Throwable $e) {
            log_message('error', 'myWellnessProfile: ' . $e->getMessage());
            $this->apiJson(['status' => 'error', 'message' => 'Server error. Please try again.']);
        }
    }

    // ─── POST /api/requestPastoralCare ────────────────────────────────────────

    public function requestPastoralCare()
    {
        $email    = trim($this->request->getPost('email')     ?? '');
        $careType = trim($this->request->getPost('care_type') ?? '');
        $message  = trim($this->request->getPost('message')   ?? '');

        $allowedTypes = ['call', 'visit', 'prayer', 'counseling'];

        if (!$email) {
            $this->apiJson(['status' => 'error', 'message' => 'Email is required.']);
            return;
        }

        if (!in_array($careType, $allowedTypes, true)) {
            $this->apiJson(['status' => 'error', 'message' => 'Invalid care type.']);
            return;
        }

        if (strlen($message) > 500) {
            $this->apiJson(['status' => 'error', 'message' => 'Message must not exceed 500 characters.']);
            return;
        }

        $db     = \Config\Database::connect('default');
        $member = $db->table('tbl_members')->where('email', $email)->get()->getRow();

        if (!$member) {
            $this->apiJson(['status' => 'error', 'message' => 'Member not found.']);
            return;
        }

        $eventTypeMap = [
            'call'       => 'call',
            'visit'      => 'visit',
            'prayer'     => 'prayer',
            'counseling' => 'other',
        ];

        $fullName = trim(($member->firstname ?? '') . ' ' . ($member->lastname ?? ''));
        $note     = '[Member Request] ' . $message;

        $db->table('tbl_member_care_events')->insert([
            'member_id'  => $member->id,
            'event_type' => $eventTypeMap[$careType],
            'note'       => $note,
            'created_by' => $fullName . ' (self-requested)',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->apiJson([
            'status'  => 'ok',
            'message' => 'Care request submitted. Our team will reach out soon.',
        ]);
    }

    // ─── POST /api/groupMemberBirthdays ───────────────────────────────────────

    public function groupMemberBirthdays()
    {
        $email = trim($this->request->getPost('email') ?? '');
        $days  = (int) ($this->request->getPost('days') ?? 7);

        if (!$email) {
            $this->apiJson(['status' => 'error', 'message' => 'Email is required.']);
            return;
        }

        if ($days < 1 || $days > 90) $days = 7;

        $db = \Config\Database::connect('default');

        // Group IDs this member belongs to
        $groupRows = $db->table('tbl_group_members')
            ->select('groupid')
            ->where('email', $email)
            ->get()->getResult();

        if (empty($groupRows)) {
            $this->apiJson(['status' => 'ok', 'birthdays' => []]);
            return;
        }

        $groupIds = array_column($groupRows, 'groupid');

        // Other members in those groups
        $peerRows = $db->query(
            'SELECT DISTINCT email AS peer_email FROM tbl_group_members
             WHERE groupid IN (' . implode(',', array_map('intval', $groupIds)) . ')
             AND email != ?',
            [$email]
        )->getResult();

        if (empty($peerRows)) {
            $this->apiJson(['status' => 'ok', 'birthdays' => []]);
            return;
        }

        $peerEmails = array_column($peerRows, 'peer_email');

        // Match (month, day) within today → today+days
        $today   = new \DateTime();
        $results = [];
        $seen    = [];

        for ($i = 0; $i <= $days; $i++) {
            $check = (clone $today)->modify("+{$i} days");
            $m = (int) $check->format('m');
            $d = (int) $check->format('d');

            $placeholders = implode(',', array_fill(0, count($peerEmails), '?'));
            $params = array_merge($peerEmails, [$m, $d]);

            $rows = $db->query(
                "SELECT firstname, thumbnail, month, day
                 FROM tbl_members
                 WHERE email IN ({$placeholders})
                 AND month = ? AND day = ?",
                $params
            )->getResult();

            foreach ($rows as $r) {
                $key = ($r->firstname ?? '') . '|' . ($r->month ?? '') . '|' . ($r->day ?? '');
                if (isset($seen[$key])) continue;
                $seen[$key] = true;

                $thumbnail = !empty($r->thumbnail)
                    ? base_url('uploads/members/' . $r->thumbnail)
                    : null;

                $results[] = [
                    'firstname'  => (string) ($r->firstname ?? ''),
                    'thumbnail'  => $thumbnail,
                    'bday_label' => $check->format('M j'),
                    'days_until' => $i,
                ];
            }
        }

        $this->apiJson(['status' => 'ok', 'birthdays' => $results]);
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    private function apiJson(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        exit;
    }
}
