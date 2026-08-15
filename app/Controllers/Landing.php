<?php

namespace App\Controllers;

use App\Models\Settings_model as settingsmodel;
use App\Models\LandingContent_model as landingcontentmodel;
use App\Models\ServiceTimes_model as servicetimesmodel;
use App\Models\Leadership_model as leadershipmodel;
use App\Models\Events_model as eventsmodel;
use App\Models\Photos_model as photosmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Members_model as membersmodel;

class Landing extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function index()
    {
        $settingsmodel = new settingsmodel();
        $landingcontentmodel = new landingcontentmodel();
        $servicetimesmodel = new servicetimesmodel();
        $leadershipmodel = new leadershipmodel();
        $eventsmodel = new eventsmodel();
        $photosmodel = new photosmodel();
        $branchesmodel = new branchesmodel();

        $data = [];
        $data['settings'] = $settingsmodel->getSettings();
        $data['church'] = $settingsmodel->getChurchProfile();
        $data['content'] = $landingcontentmodel->getContent();
        $data['serviceTimes'] = $servicetimesmodel->fetchActive();
        $data['events'] = $eventsmodel->getUpcomingEvents();
        $data['sermons'] = $this->getLatestSermons(6);
        $data['gallery'] = $this->getGalleryImages($photosmodel, 8);
        $data['leadership'] = $leadershipmodel->fetchActive();
        $data['branches'] = $branchesmodel->fetch_branches();
        $data['success'] = session()->getFlashdata('signup_success');
        $data['error'] = session()->getFlashdata('signup_error');
        $data['old'] = session()->getFlashdata('signup_old') ?? [];

        return view('landing/index', $data);
    }

    public function signup()
    {
        $rules = [
            'firstname'   => 'required|min_length[2]|max_length[100]',
            'lastname'    => 'required|min_length[2]|max_length[100]',
            'email'       => 'required|valid_email',
            'phonenumber' => 'required|min_length[6]|max_length[30]',
            'gender'      => 'required|in_list[Male,Female]',
            'dob'         => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('signup_error', 'Please fill in all required fields correctly.');
            session()->setFlashdata('signup_old', $this->request->getPost());
            return redirect()->to(base_url('/') . '#join-us');
        }

        $dob = $this->request->getVar('dob');
        $_date = \DateTime::createFromFormat('Y-m-d', $dob);

        $info = [
            'firstname'     => trim($this->request->getVar('firstname')),
            'lastname'      => trim($this->request->getVar('lastname')),
            'gender'        => $this->request->getVar('gender'),
            'email'         => trim($this->request->getVar('email')),
            'phonenumber'   => trim($this->request->getVar('phonenumber')),
            'address'       => trim((string) $this->request->getVar('address')),
            'dob'           => $dob,
            'year'          => $_date ? (int) $_date->format('Y') : 0,
            'month'         => $_date ? (int) $_date->format('m') : 0,
            'day'           => $_date ? (int) $_date->format('d') : 0,
            'age'           => $_date ? date_diff($_date, new \DateTime())->format('%y') : 0,
            'date_inserted' => date('Y-m-d H:i:s'),
        ];

        $membersmodel = new membersmodel();
        $id = $membersmodel->publicSignup($info);

        if ($membersmodel->status == $membersmodel->applocal['ok'] && $id) {
            $this->notifyChurchOfNewSignup($info);
            session()->setFlashdata('signup_success', $membersmodel->message);
        } else {
            session()->setFlashdata('signup_error', $membersmodel->message);
            session()->setFlashdata('signup_old', $this->request->getPost());
        }

        return redirect()->to(base_url('/') . '#join-us');
    }

    private function notifyChurchOfNewSignup($info)
    {
        try {
            $settingsmodel = new settingsmodel();
            $church = $settingsmodel->getChurchProfile();
            $emailconfig = $settingsmodel->getEmailConfig();
            if (!$church || !$emailconfig || empty($emailconfig->mail_username)) {
                return;
            }
            $htmlContent = '<p>A new membership request was submitted on your church website.<br><br>
                Name: ' . esc($info['firstname'] . ' ' . $info['lastname']) . '<br>
                Email: ' . esc($info['email']) . '<br>
                Phone: ' . esc($info['phonenumber']) . '<br><br>
                Review and approve this request from the "Signup Requests" page in your admin dashboard.</p>';
            $this->sendEmail(
                'no-reply',
                $emailconfig,
                $church->email,
                'New Member Signup Request',
                $this->getChurchEmailTemplate($church->fullname, $htmlContent)
            );
        } catch (\Throwable $e) {
            log_message('error', 'Landing signup notification email failed: ' . $e->getMessage());
        }
    }

    private function getLatestSermons(int $limit)
    {
        $db = \Config\Database::connect('default');
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*');
        $builder->orderBy('dateInserted', 'DESC');
        $builder->limit($limit);
        $result = $builder->get()->getResult();

        foreach ($result as $res) {
            $res->cover_photo = $this->resolveMediaAsset($res->cover_photo, 'thumbnails');
            $res->source = $this->resolveMediaAsset($res->source, $res->type == 'video' ? 'videos' : 'audios');
        }
        return $result;
    }

    private function resolveMediaAsset(?string $source, string $folder): string
    {
        if (empty($source)) return '';
        if (filter_var($source, FILTER_VALIDATE_URL)) return $source;
        return base_url('uploads/' . $folder . '/' . $source);
    }

    private function getGalleryImages($photosmodel, int $limit)
    {
        $photos = $photosmodel->fetch_photos(0);
        $images = [];
        foreach ($photos as $p) {
            if (!empty($p->thumbnail) && is_array($p->thumbnail)) {
                $images[] = ['image' => $p->thumbnail[0], 'title' => $p->title ?? ''];
            }
            if (count($images) >= $limit) break;
        }
        return $images;
    }
}
