<?php

namespace App\Controllers;

use App\Models\Counseling_model as CounselingModel;
use App\Models\Members_model as MembersModel;

class Counseling extends BaseController
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
        if (!hasPermission('counseling.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $counseling = new CounselingModel();
        $this->viewdata['stats']              = $counseling->getDashboardStats();
        $this->viewdata['today_reminders']    = $counseling->getTodayReminders();
        $this->viewdata['upcoming_reminders'] = $counseling->getUpcomingReminders(7);
        $this->viewdata['upcoming_video']     = $counseling->getUpcomingVideoSessions(6);
        return $this->view('counseling/dashboard', $this->viewdata);
    }

    public function getCaseList()
    {
        $counseling = new CounselingModel();
        $draw   = intval($this->request->getPost('draw'));
        $start  = intval($this->request->getPost('start'));
        $length = intval($this->request->getPost('length'));
        $search = $this->request->getPost('search')['value'] ?? '';

        $rows  = $counseling->getCaseListingData($search, $start, $length);
        $total = $counseling->getTotalCaseList($search);

        $statusColor = [
            'open'        => '#3b82f6',
            'in_progress' => '#f59e0b',
            'on_hold'     => '#94a3b8',
            'closed'      => '#10b981',
            'referred'    => '#8b5cf6',
        ];
        $priorityColor = [
            'low'    => '#94a3b8',
            'normal' => '#3b82f6',
            'high'   => '#f97316',
            'urgent' => '#ef4444',
        ];
        $categoryIcon = [
            'marriage'     => 'dw-heart',
            'family'       => 'dw-group',
            'grief'        => 'dw-headphones',
            'addiction'    => 'dw-warning-2',
            'mental_health'=> 'dw-brain',
            'financial'    => 'dw-wallet1',
            'spiritual'    => 'dw-open-book',
            'relationship' => 'dw-torsos-all',
            'other'        => 'dw-more',
        ];

        $data  = [];
        $count = $start + 1;
        foreach ($rows as $r) {
            $sc  = $statusColor[$r->status]   ?? '#94a3b8';
            $pc  = $priorityColor[$r->priority] ?? '#94a3b8';
            $cat = ucfirst(str_replace('_', ' ', $r->category));
            $sts = ucfirst(str_replace('_', ' ', $r->status));
            $date     = $r->opened_at ? date('M j, Y', strtotime($r->opened_at)) : '—';
            $followup = $r->next_followup
                ? '<span style="color:' . (strtotime($r->next_followup) < time() ? '#ef4444' : 'var(--t2)') . ';">' . date('M j', strtotime($r->next_followup)) . '</span>'
                : '<span style="color:#94a3b8;">—</span>';

            $init = strtoupper(substr($r->member_name ?: '?', 0, 1));

            $data[] = [
                $count,
                '<div style="display:flex;align-items:center;gap:9px;">
                   <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);
                        display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0;">'
                        . $init . '</div>
                   <div>
                     <div style="font-weight:600;color:var(--t1);">' . esc($r->member_name) . '</div>
                     <div style="font-size:.74rem;color:var(--t3);">' . esc($r->member_email) . '</div>
                   </div>
                 </div>',
                $cat,
                '<span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;
                      background:' . $sc . '22;color:' . $sc . ';">' . $sts . '</span>',
                '<span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;
                      background:' . $pc . '22;color:' . $pc . ';">' . ucfirst($r->priority) . '</span>',
                esc($r->assigned_to) ?: '<span style="color:#94a3b8;">—</span>',
                $followup,
                $date,
                '<div style="display:flex;gap:5px;justify-content:center;">
                   <a href="' . base_url('counselingCase/' . $r->id) . '" class="mc-action-btn mc-btn-view" title="View Case">
                     <i class="dw dw-eye"></i>
                   </a>
                   <a href="' . base_url('deleteCounselingCase/' . $r->id) . '" class="mc-action-btn mc-btn-delete" title="Delete"
                      onclick="return confirm(\'Permanently delete this case and all its sessions?\')">
                     <i class="dw dw-delete-3"></i>
                   </a>
                 </div>',
            ];
            $count++;
        }

        header('Content-Type: application/json'); echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ]);
    }

    public function newCase()
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $db = \Config\Database::connect('default');
        $this->viewdata['members'] = $db->table('tbl_members')
            ->select('id, firstname, lastname, email, phonenumber')
            ->orderBy('firstname', 'ASC')
            ->get()->getResult();
        return $this->view('counseling/create', $this->viewdata);
    }

    public function saveNewCase()
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling   = new CounselingModel();
        $member_id    = (int) $this->request->getPost('member_id');
        $member_name  = trim($this->request->getPost('member_name'));
        $category     = $this->request->getPost('category');
        $title        = trim($this->request->getPost('title'));
        $priority     = $this->request->getPost('priority');
        $assigned_to  = trim($this->request->getPost('assigned_to'));
        $followup     = $this->request->getPost('next_followup');
        $initial_note = trim($this->request->getPost('initial_note'));

        $allowed_cat = ['marriage', 'family', 'grief', 'addiction', 'mental_health', 'financial', 'spiritual', 'relationship', 'other'];
        $allowed_pri = ['low', 'normal', 'high', 'urgent'];

        if (!$member_name || !$title || !in_array($category, $allowed_cat) || !in_array($priority, $allowed_pri)) {
            $this->session->setFlashdata('error', 'Please fill in all required fields.');
            return redirect()->back();
        }

        $email = '';
        $phone = '';
        if ($member_id) {
            $members = new MembersModel();
            $m = $members->getMemberInfo($member_id);
            if ($m) {
                $email = $m->email ?? '';
                $phone = $m->phonenumber ?? '';
            }
        }

        $case_id = $counseling->createCase([
            'member_id'       => $member_id ?: null,
            'member_name'     => $member_name,
            'member_email'    => $email,
            'member_phone'    => $phone,
            'category'        => $category,
            'title'           => $title,
            'priority'        => $priority,
            'assigned_to'     => $assigned_to,
            'next_followup'   => $followup ?: null,
            'is_confidential' => 1,
            'opened_by'       => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($counseling->status === 'ok' && $case_id && $initial_note !== '') {
            $counseling->logSession([
                'case_id'      => $case_id,
                'session_type' => 'other',
                'session_date' => date('Y-m-d'),
                'notes'        => $initial_note,
                'logged_by'    => $this->session->get('name') ?: $this->session->get('email'),
            ]);
        }

        if ($counseling->status === 'ok') {
            $this->session->setFlashdata('success', 'Case opened successfully.');
            return redirect()->to(base_url('counselingCase/' . $case_id));
        }
        $this->session->setFlashdata('error', $counseling->message);
        return redirect()->back();
    }

    public function viewCase($id = 0)
    {
        if (!hasPermission('counseling.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $case = $counseling->getCase($id);
        if (!$case) {
            return redirect()->to(base_url('counseling'));
        }

        $db = \Config\Database::connect('default');
        $pastors = $db->table('tbl_churches')
            ->select('fullname, email')
            ->where('isdelete', 1)
            ->orderBy('fullname', 'ASC')
            ->get()->getResult();

        $this->viewdata['case']      = $case;
        $this->viewdata['sessions']  = $counseling->getCaseSessions($id);
        $this->viewdata['reminders'] = $counseling->getCaseReminders($id);
        $this->viewdata['pastors']   = $pastors;
        return $this->view('counseling/case', $this->viewdata);
    }

    public function assignCase($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling  = new CounselingModel();
        $assigned_to = trim($this->request->getPost('assigned_to'));

        $counseling->updateCase($id, ['assigned_to' => $assigned_to ?: null]);
        $this->session->setFlashdata('success', $assigned_to ? "Case assigned to {$assigned_to}." : 'Assignment cleared.');
        return redirect()->to(base_url('counselingCase/' . $id));
    }

    public function updateStatus($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $status  = $this->request->getPost('status');
        $allowed = ['open', 'in_progress', 'on_hold', 'closed', 'referred'];
        if (!in_array($status, $allowed)) {
            $this->session->setFlashdata('error', 'Invalid status.');
            return redirect()->back();
        }
        $update = ['status' => $status];
        if ($status === 'closed') {
            $update['closed_at'] = date('Y-m-d H:i:s');
        }
        $counseling->updateCase($id, $update);
        $this->session->setFlashdata('success', 'Case status updated.');
        return redirect()->to(base_url('counselingCase/' . $id));
    }

    public function logSession()
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling    = new CounselingModel();
        $case_id       = (int) $this->request->getPost('case_id');
        $type          = $this->request->getPost('session_type');
        $date          = $this->request->getPost('session_date');
        $duration      = (int) $this->request->getPost('duration_minutes');
        $notes         = trim($this->request->getPost('notes'));
        $outcome       = trim($this->request->getPost('outcome'));
        $next_steps    = trim($this->request->getPost('next_steps'));
        $allowed_types = ['in_person', 'phone', 'video', 'email', 'prayer', 'other'];

        if (!$case_id || !in_array($type, $allowed_types) || $notes === '') {
            $this->session->setFlashdata('error', 'Session type and notes are required.');
            return redirect()->back();
        }

        $counseling->logSession([
            'case_id'          => $case_id,
            'session_type'     => $type,
            'session_date'     => $date ?: date('Y-m-d'),
            'duration_minutes' => $duration ?: null,
            'notes'            => $notes,
            'outcome'          => $outcome,
            'next_steps'       => $next_steps,
            'logged_by'        => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($counseling->status === 'ok') {
            $this->session->setFlashdata('success', $counseling->message);
        } else {
            $this->session->setFlashdata('error', $counseling->message);
        }
        return redirect()->to(base_url('counselingCase/' . $case_id));
    }

    public function deleteSession($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $db   = \Config\Database::connect('default');
        $sess = $db->table('tbl_counseling_sessions')->where('id', $id)->get()->getRow();
        $cid  = $sess ? $sess->case_id : 0;
        $counseling->deleteSession($id);
        $this->session->setFlashdata('success', 'Session deleted.');
        return $cid ? redirect()->to(base_url('counselingCase/' . $cid)) : redirect()->to(base_url('counseling'));
    }

    public function addReminder()
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling  = new CounselingModel();
        $case_id     = (int) $this->request->getPost('case_id');
        $date        = $this->request->getPost('reminder_date');
        $note        = trim($this->request->getPost('note'));

        if (!$case_id || !$date) {
            $this->session->setFlashdata('error', 'Reminder date is required.');
            return redirect()->back();
        }

        $counseling->addReminder([
            'case_id'       => $case_id,
            'reminder_date' => $date,
            'note'          => $note,
            'created_by'    => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($counseling->status === 'ok') {
            $this->session->setFlashdata('success', 'Follow-up reminder added.');
        } else {
            $this->session->setFlashdata('error', $counseling->message);
        }
        return redirect()->to(base_url('counselingCase/' . $case_id));
    }

    public function markReminderDone($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $db  = \Config\Database::connect('default');
        $rem = $db->table('tbl_counseling_reminders')->where('id', $id)->get()->getRow();
        $cid = $rem ? $rem->case_id : 0;
        $counseling->markReminderDone($id);
        $this->session->setFlashdata('success', 'Reminder marked as done.');
        return $cid ? redirect()->to(base_url('counselingCase/' . $cid)) : redirect()->to(base_url('counseling'));
    }

    public function deleteReminder($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $db  = \Config\Database::connect('default');
        $rem = $db->table('tbl_counseling_reminders')->where('id', $id)->get()->getRow();
        $cid = $rem ? $rem->case_id : 0;
        $counseling->deleteReminder($id);
        $this->session->setFlashdata('success', 'Reminder deleted.');
        return $cid ? redirect()->to(base_url('counselingCase/' . $cid)) : redirect()->to(base_url('counseling'));
    }

    public function deleteCase($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $counseling->deleteCase($id);
        $this->session->setFlashdata('success', 'Case deleted successfully.');
        return redirect()->to(base_url('counseling'));
    }

    // ── Mobile API ─────────────────────────────────────────────────────

    public function submitRequest()
    {
        $counseling  = new CounselingModel();
        $email       = trim($this->request->getPost('email'));
        $name        = trim($this->request->getPost('name'));
        $category    = $this->request->getPost('category') ?: 'other';
        $title       = trim($this->request->getPost('title') ?? '');
        $note        = trim($this->request->getPost('note') ?? '');

        $allowed_cat = ['marriage', 'family', 'grief', 'addiction', 'mental_health', 'financial', 'spiritual', 'relationship', 'other'];
        if (!$email || !$name) {
            header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Name and email are required.']);
            return;
        }
        if (!in_array($category, $allowed_cat)) $category = 'other';

        // Link to member if found
        $db  = \Config\Database::connect('default');
        $mem = $db->table('tbl_members')->where('email', $email)->get()->getRow();

        $case_id = $counseling->submitRequest([
            'member_id'    => $mem ? $mem->id : null,
            'member_name'  => $name,
            'member_email' => $email,
            'member_phone' => $mem->phonenumber ?? '',
            'category'     => $category,
            'title'        => $title ?: $category,
            'priority'     => 'normal',
            'opened_by'    => 'mobile_app',
        ]);

        if ($counseling->status === 'ok' && $case_id && $note !== '') {
            $counseling->logSession([
                'case_id'      => $case_id,
                'session_type' => 'other',
                'session_date' => date('Y-m-d'),
                'notes'        => $note,
                'logged_by'    => $email,
            ]);
        }

        header('Content-Type: application/json'); echo json_encode(['status' => $counseling->status, 'message' => $counseling->message]);
    }

    public function fetchMyCases()
    {
        $counseling = new CounselingModel();
        $email      = trim($this->request->getPost('email'));
        if (!$email) {
            header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Email required.']);
            return;
        }
        $cases = $counseling->getMemberCases($email);
        header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'data' => $cases]);
    }

    // ── Video / Audio Session Scheduling ──────────────────────────────

    public function scheduleVideoSession()
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling   = new CounselingModel();
        $case_id      = (int) $this->request->getPost('case_id');
        $platform     = $this->request->getPost('meeting_platform');
        $link         = trim($this->request->getPost('meeting_link'));
        $scheduled_at = $this->request->getPost('meeting_scheduled_at');
        $duration     = (int) $this->request->getPost('duration_minutes');
        $notes        = trim($this->request->getPost('notes'));

        $allowed_platforms = ['zoom', 'google_meet', 'teams', 'whatsapp'];

        if (!$case_id || !in_array($platform, $allowed_platforms) || !$scheduled_at) {
            $this->session->setFlashdata('error', 'Platform and scheduled date/time are required.');
            return redirect()->back();
        }

        $counseling->scheduleVideoSession([
            'case_id'              => $case_id,
            'meeting_platform'     => $platform,
            'meeting_link'         => $link,
            'meeting_scheduled_at' => date('Y-m-d H:i:s', strtotime($scheduled_at)),
            'session_date'         => date('Y-m-d', strtotime($scheduled_at)),
            'duration_minutes'     => $duration ?: null,
            'notes'                => $notes ?: 'Video session scheduled.',
            'logged_by'            => $this->session->get('name') ?: $this->session->get('email'),
        ]);

        if ($counseling->status === 'ok') {
            $this->session->setFlashdata('success', $counseling->message);
        } else {
            $this->session->setFlashdata('error', $counseling->message);
        }
        return redirect()->to(base_url('counselingCase/' . $case_id));
    }

    public function updateMeetingStatus($id = 0)
    {
        if (!hasPermission('counseling.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $counseling = new CounselingModel();
        $status     = $this->request->getPost('meeting_status');
        $db         = \Config\Database::connect('default');
        $sess       = $db->table('tbl_counseling_sessions')->where('id', $id)->get()->getRow();
        $cid        = $sess ? $sess->case_id : 0;

        $counseling->updateMeetingStatus($id, $status);

        if ($counseling->status === 'ok') {
            $this->session->setFlashdata('success', 'Meeting status updated.');
        } else {
            $this->session->setFlashdata('error', $counseling->message);
        }
        return $cid ? redirect()->to(base_url('counselingCase/' . $cid)) : redirect()->to(base_url('counseling'));
    }

    // Mobile API — member fetches upcoming video calls
    public function fetchMyVideoSessions()
    {
        $counseling = new CounselingModel();
        $email      = trim($this->request->getPost('email'));
        if (!$email) {
            header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Email required.']);
            return;
        }
        $sessions = $counseling->getMemberUpcomingVideoSessions($email);
        header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'data' => $sessions]);
    }
}
