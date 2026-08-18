<?php

namespace App\Controllers;

use App\Models\LandingContent_model as landingcontentmodel;
use App\Models\ServiceTimes_model as servicetimesmodel;
use App\Models\Leadership_model as leadershipmodel;
use App\Models\Members_model as membersmodel;
use App\Models\MembershipForm_model as membershipformmodel;
use App\Models\ContactMessage_model as contactmessagemodel;
use App\Models\Settings_model as settingsmodel;

class LandingContent extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();

        if ($this->session->get('status') != 0) {
            header("Location: " . base_url());
            exit();
        }
    }

    // ─── Content Editor ─────────────────────────────────────────────

    public function index()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $model = new landingcontentmodel();
        $this->viewdata['content'] = $model->getContent();
        $this->viewdata['pendingCount'] = (new membersmodel())->getPendingSignupsTotal();
        $this->viewdata['unreadMessages'] = (new contactmessagemodel())->getUnreadTotal();
        return $this->view('landing_content/edit', $this->viewdata);
    }

    public function update()
    {
        if (!hasPermission('landing.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new landingcontentmodel();

        $fields = [
            'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_link',
            'about_title', 'about_content',
            'service_times_title', 'service_times_subtitle',
            'events_title', 'events_subtitle',
            'sermons_title', 'sermons_subtitle',
            'live_title', 'live_subtitle', 'live_offline_message',
            'gallery_title', 'gallery_subtitle',
            'leadership_title', 'leadership_subtitle',
            'contact_title', 'contact_address', 'contact_phone', 'contact_email', 'contact_map_embed',
            'contact_form_title', 'contact_form_subtitle', 'contact_notification_email',
            'signup_title', 'signup_subtitle',
            'footer_text', 'primary_color', 'header_text', 'favicon_text',
            'web_app_url', 'web_app_login_text',
            'android_app_url', 'ios_app_url',
            'app_download_title', 'app_download_subtitle',
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
            'seo_twitter_handle', 'seo_google_site_verification', 'seo_google_analytics_id',
        ];
        $info = [];
        foreach ($fields as $f) {
            $info[$f] = $this->request->getVar($f);
        }

        $toggles = ['show_hero', 'show_about', 'show_service_times', 'show_events', 'show_sermons', 'show_live', 'show_gallery', 'show_leadership', 'show_contact', 'show_contact_form', 'show_signup', 'show_app_download', 'seo_robots_index'];
        foreach ($toggles as $t) {
            $info[$t] = $this->request->getVar($t) ? 1 : 0;
        }

        if (!empty($_FILES['hero_image']['name'])) {
            $upload = $this->uploadLandingImage('hero_image');
            if ($upload) $info['hero_image'] = $upload;
        }
        if (!empty($_FILES['about_image']['name'])) {
            $upload = $this->uploadLandingImage('about_image');
            if ($upload) $info['about_image'] = $upload;
        }
        if (!empty($_FILES['seo_og_image']['name'])) {
            $upload = $this->uploadLandingImage('seo_og_image');
            if ($upload) $info['seo_og_image'] = $upload;
        }
        if (!empty($_FILES['header_logo']['name'])) {
            $upload = $this->uploadLandingImage('header_logo');
            if ($upload) $info['header_logo'] = $upload;
        }
        if (!empty($_FILES['favicon_image']['name'])) {
            $upload = $this->uploadLandingImage('favicon_image');
            if ($upload) $info['favicon_image'] = $upload;
        }

        $model->updateContent($info);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('landingContent'));
    }

    private function uploadLandingImage($field)
    {
        if (!file_exists('./uploads/landing/')) {
            mkdir('./uploads/landing/', 0777, true);
        }
        $input = $this->validate([
            $field => [
                'uploaded[' . $field . ']',
                'mime_in[' . $field . ',image/jpg,image/jpeg,image/png,image/webp]',
                'max_size[' . $field . ',10024]',
            ]
        ]);
        if (!$input) {
            return null;
        }
        $img = $this->request->getFile($field);
        $img->move('./uploads/landing/');
        return $img->getName();
    }

    // ─── Service Times ──────────────────────────────────────────────

    public function serviceTimes()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $model = new servicetimesmodel();
        $this->viewdata['times'] = $model->fetchAll();
        return $this->view('landing_content/service_times', $this->viewdata);
    }

    public function newServiceTime()
    {
        return $this->view('landing_content/service_time_new', $this->viewdata);
    }

    public function saveNewServiceTime()
    {
        $model = new servicetimesmodel();
        $info = [
            'name'        => $this->request->getVar('name'),
            'day_of_week' => $this->request->getVar('day_of_week'),
            'time_label'  => $this->request->getVar('time_label'),
            'location'    => $this->request->getVar('location'),
            'description' => $this->request->getVar('description'),
            'sort_order'  => (int) $this->request->getVar('sort_order'),
            'status'      => $this->request->getVar('status') ?: 'active',
        ];
        $model->addNew($info);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('serviceTimesListing'));
    }

    public function editServiceTime($id = 0)
    {
        $model = new servicetimesmodel();
        $this->viewdata['item'] = $model->getInfo($id);
        if (!$this->viewdata['item']) {
            return redirect()->to(base_url('serviceTimesListing'));
        }
        return $this->view('landing_content/service_time_edit', $this->viewdata);
    }

    public function editServiceTimeData()
    {
        $model = new servicetimesmodel();
        $id = $this->request->getVar('id');
        $info = [
            'name'        => $this->request->getVar('name'),
            'day_of_week' => $this->request->getVar('day_of_week'),
            'time_label'  => $this->request->getVar('time_label'),
            'location'    => $this->request->getVar('location'),
            'description' => $this->request->getVar('description'),
            'sort_order'  => (int) $this->request->getVar('sort_order'),
            'status'      => $this->request->getVar('status') ?: 'active',
        ];
        $model->edit($info, $id);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('serviceTimesListing'));
    }

    public function deleteServiceTime($id = 0)
    {
        $model = new servicetimesmodel();
        $model->deleteItem($id);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('serviceTimesListing'));
    }

    // ─── Leadership ─────────────────────────────────────────────────

    public function leadership()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $model = new leadershipmodel();
        $this->viewdata['leaders'] = $model->fetchAll();
        return $this->view('landing_content/leadership', $this->viewdata);
    }

    public function newLeader()
    {
        return $this->view('landing_content/leadership_new', $this->viewdata);
    }

    public function saveNewLeader()
    {
        $model = new leadershipmodel();
        $info = [
            'name'       => $this->request->getVar('name'),
            'role_title' => $this->request->getVar('role_title'),
            'bio'        => $this->request->getVar('bio'),
            'email'      => $this->request->getVar('email'),
            'sort_order' => (int) $this->request->getVar('sort_order'),
            'status'     => $this->request->getVar('status') ?: 'active',
        ];
        if (!empty($_FILES['photo']['name'])) {
            $upload = $this->uploadLeaderPhoto();
            if ($upload) $info['photo'] = $upload;
        }
        $model->addNew($info);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('leadershipListing'));
    }

    public function editLeader($id = 0)
    {
        $model = new leadershipmodel();
        $this->viewdata['item'] = $model->getInfo($id);
        if (!$this->viewdata['item']) {
            return redirect()->to(base_url('leadershipListing'));
        }
        return $this->view('landing_content/leadership_edit', $this->viewdata);
    }

    public function editLeaderData()
    {
        $model = new leadershipmodel();
        $id = $this->request->getVar('id');
        $info = [
            'name'       => $this->request->getVar('name'),
            'role_title' => $this->request->getVar('role_title'),
            'bio'        => $this->request->getVar('bio'),
            'email'      => $this->request->getVar('email'),
            'sort_order' => (int) $this->request->getVar('sort_order'),
            'status'     => $this->request->getVar('status') ?: 'active',
        ];
        if (!empty($_FILES['photo']['name'])) {
            $upload = $this->uploadLeaderPhoto();
            if ($upload) $info['photo'] = $upload;
        }
        $model->edit($info, $id);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('editLeader/' . $id));
    }

    public function deleteLeader($id = 0)
    {
        $model = new leadershipmodel();
        $model->deleteItem($id);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('leadershipListing'));
    }

    private function uploadLeaderPhoto()
    {
        if (!file_exists('./uploads/leadership/')) {
            mkdir('./uploads/leadership/', 0777, true);
        }
        $input = $this->validate([
            'photo' => [
                'uploaded[photo]',
                'mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]',
                'max_size[photo,10024]',
            ]
        ]);
        if (!$input) {
            return null;
        }
        $img = $this->request->getFile('photo');
        $img->move('./uploads/leadership/');
        return $img->getName();
    }

    // ─── Signup Requests (approval queue) ───────────────────────────

    public function signupRequests()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        return $this->view('landing_content/signups', $this->viewdata);
    }

    public function getSignupRequests()
    {
        $model = new membersmodel();
        $draw = intval($_POST['draw']);
        $start = intval($_POST['start']);
        $length = intval($_POST['length']);
        $search = $_POST['search']['value'] ?? '';

        $rows = $model->getPendingSignupsListing($search, $start, $length);
        $total = $model->getPendingSignupsTotal($search);

        $dat = [];
        $count = $start + 1;
        foreach ($rows as $r) {
            $actions = '
                <div style="display:flex;gap:6px;justify-content:center;">
                  <a href="' . base_url('viewMember/' . $r->id) . '" class="mp-act-btn mp-act-view" title="View"><i class="dw dw-eye"></i></a>
                  <button type="button" data-id="' . $r->id . '" class="mp-act-btn mp-act-approve signup-approve-btn" title="Approve"><i class="dw dw-check-circle-2"></i> Approve</button>
                  <button type="button" data-id="' . $r->id . '" class="mp-act-btn mp-act-reject signup-reject-btn" title="Reject"><i class="dw dw-close-circle-1"></i> Reject</button>
                </div>';

            $dat[] = [
                $count,
                esc($r->firstname . ' ' . $r->lastname),
                esc($r->email) . '<br><span style="font-size:.75rem;color:var(--t3);">' . esc($r->phonenumber) . '</span>',
                esc($r->gender),
                $r->date_inserted ? date('M j, Y g:i A', strtotime($r->date_inserted)) : '—',
                $actions,
            ];
            $count++;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dat,
        ]);
    }

    public function approveSignupRequest($id = 0)
    {
        $model = new membersmodel();
        $model->approveSignup($id);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('signupRequests'));
    }

    public function rejectSignupRequest($id = 0)
    {
        $model = new membersmodel();
        $model->rejectSignup($id);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('signupRequests'));
    }

    // ─── Membership Form Builder ─────────────────────────────────────

    public function membershipForm()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        $model = new membershipformmodel();
        $this->viewdata['fields'] = $model->fetchAll();
        return $this->view('landing_content/membership_form', $this->viewdata);
    }

    public function newMembershipField()
    {
        return $this->view('landing_content/membership_field_new', $this->viewdata);
    }

    private function parseOptions(): ?string
    {
        $raw = (string) $this->request->getVar('options');
        $lines = array_filter(array_map('trim', explode("\n", $raw)), fn($l) => $l !== '');
        return !empty($lines) ? json_encode(array_values($lines)) : null;
    }

    public function saveNewMembershipField()
    {
        $model = new membershipformmodel();
        $label = trim((string) $this->request->getVar('label'));
        $type = $this->request->getVar('field_type');
        $key = $model->slugifyKey($label);

        if (!empty($model->checkKeyExists($key))) {
            $key .= '_' . substr(md5(uniqid()), 0, 4);
        }

        $needsOptions = in_array($type, ['select', 'radio', 'checkbox'], true);

        $info = [
            'field_key'   => $key,
            'label'       => $label,
            'field_type'  => $type,
            'options'     => $needsOptions ? $this->parseOptions() : null,
            'placeholder' => $this->request->getVar('placeholder') ?: null,
            'help_text'   => $this->request->getVar('help_text') ?: null,
            'required'    => $this->request->getVar('required') ? 1 : 0,
            'status'      => $this->request->getVar('status') ?: 'active',
        ];
        $model->addNew($info);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('membershipFormListing'));
    }

    public function editMembershipField($id = 0)
    {
        $model = new membershipformmodel();
        $this->viewdata['item'] = $model->getInfo($id);
        if (!$this->viewdata['item']) {
            return redirect()->to(base_url('membershipFormListing'));
        }
        return $this->view('landing_content/membership_field_edit', $this->viewdata);
    }

    public function editMembershipFieldData()
    {
        $model = new membershipformmodel();
        $id = (int) $this->request->getVar('id');
        $field = $model->getInfo($id);
        $type = $this->request->getVar('field_type');
        $needsOptions = in_array($type, ['select', 'radio', 'checkbox'], true);

        $info = [
            'label'       => trim((string) $this->request->getVar('label')),
            'field_type'  => $type,
            'options'     => $needsOptions ? $this->parseOptions() : null,
            'placeholder' => $this->request->getVar('placeholder') ?: null,
            'help_text'   => $this->request->getVar('help_text') ?: null,
            'required'    => $this->request->getVar('required') ? 1 : 0,
            'status'      => $this->request->getVar('status') ?: 'active',
        ];
        $model->edit($info, $id);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url($field && $field->is_core ? 'membershipFormListing' : 'editMembershipField/' . $id));
    }

    public function deleteMembershipField($id = 0)
    {
        $model = new membershipformmodel();
        $model->deleteField($id);
        $this->session->setFlashdata($model->status == $model->applocal['ok'] ? 'success' : 'error', $model->message);
        return redirect()->to(base_url('membershipFormListing'));
    }

    public function moveMembershipFieldUp($id = 0)
    {
        (new membershipformmodel())->moveUp($id);
        return redirect()->to(base_url('membershipFormListing'));
    }

    public function moveMembershipFieldDown($id = 0)
    {
        (new membershipformmodel())->moveDown($id);
        return redirect()->to(base_url('membershipFormListing'));
    }

    // ─── Contact Messages ────────────────────────────────────────────

    public function contactMessages()
    {
        if (!hasPermission('landing.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }
        return $this->view('landing_content/contact_messages', $this->viewdata);
    }

    public function getContactMessages()
    {
        $model = new contactmessagemodel();
        $draw = intval($_POST['draw']);
        $start = intval($_POST['start']);
        $length = intval($_POST['length']);
        $search = $_POST['search']['value'] ?? '';

        $rows = $model->getListing($search, $start, $length);
        $total = $model->getTotal($search);

        $statusBadge = [
            'unread'  => '<span class="badge badge-pill badge-warning">Unread</span>',
            'read'    => '<span class="badge badge-pill badge-secondary">Read</span>',
            'replied' => '<span class="badge badge-pill badge-success">Replied</span>',
        ];

        $dat = [];
        $count = $start + 1;
        foreach ($rows as $r) {
            $actions = '
                <div style="display:flex;gap:6px;justify-content:center;">
                  <a href="' . base_url('viewContactMessage/' . $r->id) . '" class="mp-act-btn mp-act-view" title="View / Reply"><i class="dw dw-eye"></i></a>
                  <a href="javascript:void(0)" class="mp-act-btn mp-act-reject" onclick="cmDelConfirm(' . $r->id . ')" title="Delete"><i class="dw dw-trash"></i></a>
                </div>';

            $dat[] = [
                $count,
                ($r->status === 'unread' ? '<strong>' . esc($r->name) . '</strong>' : esc($r->name))
                    . '<br><span style="font-size:.75rem;color:var(--t3);">' . esc($r->email) . '</span>',
                esc($r->subject ?: '(no subject)'),
                $statusBadge[$r->status] ?? esc($r->status),
                $r->created_at ? date('M j, Y g:i A', strtotime($r->created_at)) : '—',
                $actions,
            ];
            $count++;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dat,
        ]);
    }

    public function viewContactMessage($id = 0)
    {
        $model = new contactmessagemodel();
        $item = $model->getInfo($id);
        if (!$item) {
            return redirect()->to(base_url('contactMessages'));
        }
        $model->markAsRead($id);
        $this->viewdata['item'] = $item;
        return $this->view('landing_content/contact_message_view', $this->viewdata);
    }

    public function replyContactMessage()
    {
        $id = (int) $this->request->getVar('id');
        $reply = trim((string) $this->request->getVar('reply'));
        $model = new contactmessagemodel();
        $item = $model->getInfo($id);

        if (!$item || $reply === '') {
            $this->session->setFlashdata('error', 'Please write a reply before sending.');
            return redirect()->to(base_url('viewContactMessage/' . $id));
        }

        $model->saveReply($id, $reply);

        try {
            $settingsmodel = new settingsmodel();
            $church = $settingsmodel->getChurchProfile();
            $emailconfig = $settingsmodel->getEmailConfig();
            if ($church && $emailconfig && !empty($emailconfig->mail_username)) {
                $htmlContent = '<p>Hi ' . esc($item->name) . ',</p><p>' . nl2br(esc($reply)) . '</p>'
                    . '<p style="color:#888;font-size:.85em;">— In reply to your message' . ($item->subject !== '' ? ' "' . esc($item->subject) . '"' : '') . '</p>';
                $this->sendEmail(
                    'no-reply',
                    $emailconfig,
                    $item->email,
                    'Re: ' . ($item->subject ?: 'Your message to ' . $church->fullname),
                    $this->getChurchEmailTemplate($church->fullname, $htmlContent)
                );
            }
        } catch (\Throwable $e) {
            log_message('error', 'Contact reply email failed: ' . $e->getMessage());
        }

        $this->session->setFlashdata('success', 'Reply sent to ' . $item->email . '.');
        return redirect()->to(base_url('viewContactMessage/' . $id));
    }

    public function deleteContactMessage($id = 0)
    {
        $model = new contactmessagemodel();
        $model->deleteMessage($id);
        $this->session->setFlashdata('success', $model->message);
        return redirect()->to(base_url('contactMessages'));
    }
}
