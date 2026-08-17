<?php

namespace App\Controllers;

use App\Models\MemberCare_model as CareModel;
use App\Models\Members_model as MembersModel;

class MemberCare extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper(['form', 'url', 'AdminAuth']);
        $this->session = session();

        if ($this->session->get('status') != 0) {
            header('Location: ' . base_url());
            exit();
        }
    }

    public function dashboard()
    {
        if (!hasPermission('membercare.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $care = new CareModel();
        $this->viewdata['stats']        = $care->getDashboardStats();
        $this->viewdata['birthdays']    = $care->getUpcomingBirthdays(14);
        $this->viewdata['new_members']  = $care->getNewMembers(30);
        $this->viewdata['needs_care']   = $care->getMembersNeedingCare(10);
        $this->viewdata['recent_activity'] = $care->getRecentCareActivity(10);
        return $this->view('member_care/dashboard', $this->viewdata);
    }

    public function getCareList()
    {
        $care   = new CareModel();
        $draw   = intval($this->request->getPost('draw'));
        $start  = intval($this->request->getPost('start'));
        $length = intval($this->request->getPost('length'));
        $search = $this->request->getPost('search')['value'] ?? '';

        $rows  = $care->getCareListingData($search, $start, $length);
        $total = $care->getTotalCareList($search);

        $gradeLabel = ['high' => 'Active', 'medium' => 'Moderate', 'low' => 'Low', 'none' => 'Inactive'];
        $gradeColor = ['high' => '#10b981', 'medium' => '#f59e0b', 'low' => '#f97316', 'none' => '#ef4444'];

        $data = [];
        $count = $start + 1;
        foreach ($rows as $r) {
            $name     = esc($r->firstname . ' ' . $r->lastname);
            $score    = (int) $r->score;
            $grade    = $r->grade;
            $lastCare = $r->last_care_at
                ? date('M j, Y', strtotime($r->last_care_at))
                : '<span style="color:#94a3b8;">Never</span>';
            $events   = (int) $r->care_event_count;

            $data[] = [
                $count,
                esc($r->email),
                esc($r->firstname),
                esc($r->lastname),
                $score,
                $grade,
                $events,
                $lastCare,
                '<div style="display:flex;gap:5px;justify-content:center;">
                  <a href="' . base_url('memberCareProfile/' . $r->id) . '" class="mc-action-btn mc-btn-view" title="Care Profile">
                    <i class="dw dw-heart"></i>
                  </a>
                  <a href="' . base_url('viewMember/' . $r->id) . '" class="mc-action-btn mc-btn-info" title="Member Profile">
                    <i class="dw dw-eye"></i>
                  </a>
                </div>',
            ];
            $count++;
        }

        echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ]);
    }

    public function profile($member_id = 0)
    {
        $care    = new CareModel();
        $members = new MembersModel();

        $member = $members->getMemberInfo($member_id);
        if (!$member) {
            return redirect()->to(base_url('memberCare'));
        }

        $this->viewdata['member']      = $member;
        $this->viewdata['care_history'] = $care->getMemberCareHistory($member_id);
        $this->viewdata['notes']       = $care->getMemberNotes($member_id);
        $this->viewdata['engagement']  = $care->getMemberEngagementScore($member_id);
        return $this->view('member_care/profile', $this->viewdata);
    }

    public function logEvent()
    {
        $care      = new CareModel();
        $member_id = (int) $this->request->getPost('member_id');
        $type      = $this->request->getPost('event_type');
        $note      = $this->request->getPost('note');
        $allowed   = ['call', 'visit', 'email', 'prayer', 'message', 'other'];

        if (!$member_id || !in_array($type, $allowed)) {
            $this->session->setFlashdata('error', 'Invalid care event data.');
            return redirect()->back();
        }

        $care->logCareEvent([
            'member_id'  => $member_id,
            'event_type' => $type,
            'note'       => $note,
            'created_by' => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($care->status === 'ok') {
            $this->session->setFlashdata('success', $care->message);
        } else {
            $this->session->setFlashdata('error', $care->message);
        }
        return redirect()->to(base_url('memberCareProfile/' . $member_id));
    }

    public function addNote()
    {
        $care      = new CareModel();
        $member_id = (int) $this->request->getPost('member_id');
        $note      = trim($this->request->getPost('note'));
        $private   = $this->request->getPost('is_private') ? 1 : 0;

        if (!$member_id || $note === '') {
            $this->session->setFlashdata('error', 'Note cannot be empty.');
            return redirect()->back();
        }

        $care->addNote([
            'member_id'  => $member_id,
            'note'       => $note,
            'is_private' => $private,
            'created_by' => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($care->status === 'ok') {
            $this->session->setFlashdata('success', $care->message);
        } else {
            $this->session->setFlashdata('error', $care->message);
        }
        return redirect()->to(base_url('memberCareProfile/' . $member_id));
    }

    public function deleteNote($id = 0)
    {
        $care = new CareModel();
        $db   = \Config\Database::connect('default');

        // Find the member so we can redirect back to their profile
        $note = $db->table('tbl_member_care_notes')->where('id', $id)->get()->getRow();
        $mid  = $note ? $note->member_id : 0;

        $care->deleteNote($id);
        $this->session->setFlashdata('success', 'Note deleted.');
        return $mid ? redirect()->to(base_url('memberCareProfile/' . $mid)) : redirect()->to(base_url('memberCare'));
    }

    public function deleteEvent($id = 0)
    {
        $care = new CareModel();
        $db   = \Config\Database::connect('default');

        $event = $db->table('tbl_member_care_events')->where('id', $id)->get()->getRow();
        $mid   = $event ? $event->member_id : 0;

        $care->deleteCareEvent($id);
        $this->session->setFlashdata('success', 'Care event deleted.');
        return $mid ? redirect()->to(base_url('memberCareProfile/' . $mid)) : redirect()->to(base_url('memberCare'));
    }
}
