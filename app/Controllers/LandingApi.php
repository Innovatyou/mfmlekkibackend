<?php

namespace App\Controllers;

use App\Models\Settings_model as settingsmodel;
use App\Models\LandingContent_model as landingcontentmodel;
use App\Models\ServiceTimes_model as servicetimesmodel;
use App\Models\Leadership_model as leadershipmodel;
use App\Models\Events_model as eventsmodel;
use App\Models\Photos_model as photosmodel;
use App\Models\Members_model as membersmodel;
use App\Models\MembershipForm_model as membershipformmodel;
use App\Models\Livestream_model as livestreammodel;
use App\Models\ContactMessage_model as contactmessagemodel;

/**
 * JSON API for the Next.js public frontend.
 * Public, unauthenticated, CORS-enabled — mirrors the data the PHP-rendered
 * landing page (app/Views/landing/index.php) already shows.
 */
class LandingApi extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['form', 'url']);
        $this->applyCors();
    }

    private function applyCors()
    {
        $origin = $this->request->getHeaderLine('Origin') ?: '*';
        $this->response->setHeader('Access-Control-Allow-Origin', $origin);
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $this->response->setHeader('Access-Control-Max-Age', '86400');
        $this->response->setHeader('Vary', 'Origin');
    }

    public function preflight()
    {
        return $this->response->setStatusCode(204);
    }

    private function json($data, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON($data);
    }

    public function landingContent()
    {
        $settingsmodel = new settingsmodel();
        $landingcontentmodel = new landingcontentmodel();
        $servicetimesmodel = new servicetimesmodel();
        $leadershipmodel = new leadershipmodel();
        $eventsmodel = new eventsmodel();
        $photosmodel = new photosmodel();

        $settings = $settingsmodel->getSettings();
        $church = $settingsmodel->getChurchProfile();
        $content = $landingcontentmodel->getContent();
        $live = (new livestreammodel())->getCurrentLive();

        return $this->json([
            'church' => [
                'name' => $church->fullname ?? ($settings->churchname ?? 'Our Church'),
                'logo' => !empty($church->logo) ? $church->logo : null,
            ],
            'settings' => [
                'facebook'  => $settings->facebook ?? '',
                'twitter'   => $settings->twitter ?? '',
                'instagram' => $settings->instagram ?? '',
                'youtube'   => $settings->youtube ?? '',
                'website'   => $settings->website ?? '',
            ],
            'content' => $content,
            'serviceTimes' => $servicetimesmodel->fetchActive(),
            'events' => $eventsmodel->getUpcomingEvents(),
            'sermons' => $this->getLatestSermons(6),
            'gallery' => $this->getGalleryImages($photosmodel, 8),
            'leadership' => $leadershipmodel->fetchActive(),
            'live' => $live ? [
                'title'       => $live->title,
                'description' => $live->description,
                'source'      => $live->source,
                'link'        => $live->link,
                'cover_photo' => $live->cover_photo,
            ] : null,
        ]);
    }

    public function membershipForm()
    {
        $model = new membershipformmodel();
        return $this->json(['fields' => $model->fetchActive()]);
    }

    public function join()
    {
        $data = $this->get_data();
        $formmodel = new membershipformmodel();
        $fields = $formmodel->fetchActive();

        if (empty($fields)) {
            return $this->json(['status' => 'error', 'message' => 'The membership form is not available right now.'], 500);
        }

        // Validate required fields
        foreach ($fields as $field) {
            $val = $data->{$field->field_key} ?? null;
            $isEmpty = $val === null || $val === '' || (is_array($val) && count($val) === 0);
            if ($field->required && $isEmpty) {
                return $this->json(['status' => 'error', 'message' => $field->label . ' is required.'], 422);
            }
        }
        $email = $data->email ?? '';
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['status' => 'error', 'message' => 'Please enter a valid email address.'], 422);
        }

        $core = [];
        $custom = [];
        foreach ($fields as $field) {
            $val = $data->{$field->field_key} ?? '';
            if (in_array($field->field_key, membershipformmodel::CORE_MEMBER_COLUMNS, true)) {
                $core[$field->field_key] = is_array($val) ? implode(', ', $val) : (string) $val;
            } else {
                $custom[] = ['field' => $field, 'value' => $val];
            }
        }

        $dob = $core['dob'] ?? '';
        $_date = $dob ? \DateTime::createFromFormat('Y-m-d', $dob) : false;

        $info = array_merge([
            'firstname' => '', 'lastname' => '', 'email' => '', 'phonenumber' => '',
            'gender' => '', 'dob' => '', 'address' => '',
        ], $core);
        $info['year'] = $_date ? (int) $_date->format('Y') : 0;
        $info['month'] = $_date ? (int) $_date->format('m') : 0;
        $info['day'] = $_date ? (int) $_date->format('d') : 0;
        $info['age'] = $_date ? (int) date_diff($_date, new \DateTime())->format('%y') : 0;
        $info['date_inserted'] = date('Y-m-d H:i:s');

        $membersmodel = new membersmodel();
        $memberId = $membersmodel->publicSignup($info);

        if ($membersmodel->status !== $membersmodel->applocal['ok'] || !$memberId) {
            return $this->json(['status' => 'error', 'message' => $membersmodel->message], 409);
        }

        foreach ($custom as $c) {
            $val = $c['value'];
            $isEmpty = $val === null || $val === '' || (is_array($val) && count($val) === 0);
            if ($isEmpty) continue;
            $formmodel->saveAnswer($memberId, $c['field'], $val);
        }

        $this->notifyChurchOfNewSignup($info);

        return $this->json(['status' => 'ok', 'message' => 'Thank you! Your membership request has been received and is awaiting review.']);
    }

    public function contactUs()
    {
        $data = $this->get_data();
        $name = trim((string) ($data->name ?? ''));
        $email = trim((string) ($data->email ?? ''));
        $phone = trim((string) ($data->phone ?? ''));
        $subject = trim((string) ($data->subject ?? ''));
        $message = trim((string) ($data->message ?? ''));

        if ($name === '' || $email === '' || $message === '') {
            return $this->json(['status' => 'error', 'message' => 'Please fill in your name, email and message.'], 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['status' => 'error', 'message' => 'Please enter a valid email address.'], 422);
        }

        $model = new contactmessagemodel();
        $id = $model->addMessage([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject,
            'message' => $message,
        ]);

        $this->notifyChurchOfNewContactMessage($name, $email, $phone, $subject, $message);

        return $this->json(['status' => $model->status, 'message' => $model->message]);
    }

    private function notifyChurchOfNewContactMessage($name, $email, $phone, $subject, $message)
    {
        try {
            $settingsmodel = new settingsmodel();
            $content = (new landingcontentmodel())->getContent();
            $church = $settingsmodel->getChurchProfile();
            $emailconfig = $settingsmodel->getEmailConfig();
            if (!$church || !$emailconfig || empty($emailconfig->mail_username)) {
                return;
            }
            $recipient = !empty($content->contact_notification_email) ? $content->contact_notification_email : $church->email;
            $htmlContent = '<p>A new message was submitted through your church website contact form.<br><br>
                Name: ' . esc($name) . '<br>
                Email: ' . esc($email) . '<br>'
                . ($phone !== '' ? 'Phone: ' . esc($phone) . '<br>' : '')
                . ($subject !== '' ? 'Subject: ' . esc($subject) . '<br>' : '') . '<br>
                Message:<br>' . nl2br(esc($message)) . '<br><br>
                Reply from the "Contact Messages" page in your admin dashboard.</p>';
            $this->sendEmail(
                'no-reply',
                $emailconfig,
                $recipient,
                'New Contact Form Message' . ($subject !== '' ? ': ' . $subject : ''),
                $this->getChurchEmailTemplate($church->fullname, $htmlContent)
            );
        } catch (\Throwable $e) {
            log_message('error', 'Contact form notification email failed: ' . $e->getMessage());
        }
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
