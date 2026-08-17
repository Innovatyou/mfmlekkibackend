<?php

namespace App\Controllers;

use App\Models\Partnership_model as PartnershipModel;
use App\Models\Settings_model as SettingsModel;

class Partnership extends BaseController
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

    // ─── Dashboard ───────────────────────────────────────────────────

    public function dashboard()
    {
        if (!hasPermission('partnership.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $this->viewdata['stats']   = $model->getDashboardStats();
        $this->viewdata['recent']  = $model->getRecentPartnerships(6);
        $this->viewdata['byTier']  = $model->getTierStats();

        return $this->view('partnership/dashboard', $this->viewdata);
    }

    // ─── Partnerships listing ─────────────────────────────────────────

    public function index()
    {
        if (!hasPermission('partnership.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $this->viewdata['tiers']         = $model->getAllTiers();
        $stats = $model->getDashboardStats();
        $this->viewdata['pending_count'] = $stats['pending'];

        return $this->view('partnership/listing', $this->viewdata);
    }

    public function getList()
    {
        if (!hasPermission('partnership.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model  = new PartnershipModel();
        $draw   = (int) ($this->request->getPost('draw') ?? 1);
        $start  = (int) ($this->request->getPost('start') ?? 0);
        $length = (int) ($this->request->getPost('length') ?? 20);
        $search = trim($this->request->getPost('search')['value'] ?? '');

        $rows  = $model->getListingData($search, $start, $length);
        $total = $model->getTotalCount($search);

        $data  = [];
        $count = $start + 1;

        foreach ($rows as $r) {
            $statusBadge = match ($r->status) {
                'pending'   => '<span class="badge badge-pill badge-warning">Pending Review</span>',
                'active'    => '<span class="badge badge-pill badge-success">Active</span>',
                'completed' => '<span class="badge badge-pill badge-info">Completed</span>',
                'overdue'   => '<span class="badge badge-pill badge-danger">Overdue</span>',
                'cancelled' => '<span class="badge badge-pill badge-secondary">Cancelled</span>',
                default     => '<span class="badge badge-pill badge-secondary">' . esc($r->status) . '</span>',
            };

            $freqLabel = match ($r->frequency) {
                'one-time'  => 'One-time',
                'monthly'   => 'Monthly',
                'quarterly' => 'Quarterly',
                'annually'  => 'Annually',
                default     => esc($r->frequency),
            };

            $tierBadge = $r->tier_name
                ? '<span style="display:inline-block;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:700;background:' . esc($r->tier_color) . '22;color:' . esc($r->tier_color) . ';border:1px solid ' . esc($r->tier_color) . '44;">' . esc($r->tier_name) . '</span>'
                : '<span class="badge badge-pill badge-secondary">—</span>';

            $currency   = esc($r->currency ?? 'USD');
            $pledged    = $currency . ' ' . number_format((float) $r->pledge_amount, 2);
            $paid       = $currency . ' ' . number_format((float) $r->paid_amount, 2);
            $remaining  = (float) $r->pledge_amount - (float) $r->paid_amount;
            $remDisplay = $remaining > 0
                ? '<span style="color:#ef4444;font-weight:600;">' . $currency . ' ' . number_format($remaining, 2) . '</span>'
                : '<span style="color:#059669;font-weight:600;">Fulfilled</span>';

            $actions = '<div style="display:flex;gap:5px;">';
            if ($r->status === 'pending' && (hasPermission('partnership.edit') || isSuperAdmin())) {
                $actions .= '<a href="' . base_url('approvePartnership/' . $r->id) . '" class="lt-ab lt-approve" title="Approve" onclick="return confirm(\'Approve this partnership application?\')"><i class="dw dw-check-circle-2"></i></a>';
            }
            if (in_array($r->status, ['active', 'overdue'])) {
                $actions .= '<a href="' . base_url('partnerPayment/' . $r->id) . '" class="lt-ab lt-pay" title="Pay" target="_blank"><i class="dw dw-money-2"></i></a>';
            }
            if (hasPermission('partnership.edit') || isSuperAdmin()) {
                $actions .= '<a href="' . base_url('editPartnership/' . $r->id) . '" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>';
                $actions .= '<a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltPDelConfirm(' . $r->id . ')"><i class="dw dw-trash"></i></a>';
            }
            $actions .= '</div>';

            $data[] = [
                $count,
                esc($r->partner_name) . '<br><small style="color:var(--t3);">' . esc($r->partner_email ?? '') . '</small>',
                $tierBadge,
                $pledged,
                $paid,
                $remDisplay,
                $freqLabel,
                $statusBadge,
                $actions,
            ];
            $count++;
        }

        return $this->response->setContentType('application/json')->setBody(json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ]));
    }

    // ─── New partnership ──────────────────────────────────────────────

    public function newPartnership()
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $this->viewdata['tiers']   = $model->getAllTiers();
        $this->viewdata['members'] = \Config\Database::connect()
            ->table('tbl_members')
            ->select('id, firstname, lastname, email')
            ->orderBy('firstname', 'ASC')
            ->get()->getResult();

        return $this->view('partnership/new', $this->viewdata);
    }

    public function saveNewPartnership()
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();

        $info = [
            'member_id'     => $this->request->getPost('member_id') ?: null,
            'tier_id'       => $this->request->getPost('tier_id') ?: null,
            'partner_name'  => $this->cleanup($this->request->getPost('partner_name')),
            'partner_email' => $this->cleanup($this->request->getPost('partner_email')),
            'partner_phone' => $this->cleanup($this->request->getPost('partner_phone')),
            'pledge_amount' => (float) $this->request->getPost('pledge_amount'),
            'paid_amount'   => (float) ($this->request->getPost('paid_amount') ?? 0),
            'currency'      => $this->cleanup($this->request->getPost('currency')) ?: 'USD',
            'frequency'     => $this->cleanup($this->request->getPost('frequency')) ?: 'monthly',
            'start_date'    => $this->request->getPost('start_date') ?: null,
            'end_date'      => $this->request->getPost('end_date') ?: null,
            'status'        => 'pending',
            'notes'         => $this->cleanup($this->request->getPost('notes')),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $model->addPartnership($info);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipListing'));
    }

    // ─── Edit partnership ─────────────────────────────────────────────

    public function editPartnership(int $id)
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $partnership = $model->getPartnership($id);

        if (!$partnership) {
            $this->session->setFlashdata('error', 'Record not found.');
            return redirect()->to(base_url('partnershipListing'));
        }

        $this->viewdata['partnership'] = $partnership;
        $this->viewdata['tiers']       = $model->getAllTiers();
        $this->viewdata['payments']    = $model->getPaymentHistory($id);
        $this->viewdata['members']     = \Config\Database::connect()
            ->table('tbl_members')
            ->select('id, firstname, lastname, email')
            ->orderBy('firstname', 'ASC')
            ->get()->getResult();

        return $this->view('partnership/edit', $this->viewdata);
    }

    public function editPartnershipData()
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $id    = (int) $this->request->getPost('id');
        $model = new PartnershipModel();

        $info = [
            'member_id'     => $this->request->getPost('member_id') ?: null,
            'tier_id'       => $this->request->getPost('tier_id') ?: null,
            'partner_name'  => $this->cleanup($this->request->getPost('partner_name')),
            'partner_email' => $this->cleanup($this->request->getPost('partner_email')),
            'partner_phone' => $this->cleanup($this->request->getPost('partner_phone')),
            'pledge_amount' => (float) $this->request->getPost('pledge_amount'),
            'paid_amount'   => (float) $this->request->getPost('paid_amount'),
            'currency'      => $this->cleanup($this->request->getPost('currency')) ?: 'USD',
            'frequency'     => $this->cleanup($this->request->getPost('frequency')) ?: 'monthly',
            'start_date'    => $this->request->getPost('start_date') ?: null,
            'end_date'      => $this->request->getPost('end_date') ?: null,
            'status'        => $this->cleanup($this->request->getPost('status')) ?: 'active',
            'notes'         => $this->cleanup($this->request->getPost('notes')),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $model->updatePartnership($id, $info);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipListing'));
    }

    public function deletePartnership(int $id)
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $model->deletePartnership($id);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipListing'));
    }

    public function approvePartnership(int $id)
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $model->updatePartnership($id, ['status' => 'active', 'updated_at' => date('Y-m-d H:i:s')]);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error',
            $model->status === 'ok' ? 'Partnership approved and set to active.' : $model->message);

        return redirect()->to(base_url('partnershipListing'));
    }

    // ─── Tiers ────────────────────────────────────────────────────────

    public function tiers()
    {
        if (!hasPermission('partnership.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $this->viewdata['tiers'] = $model->getAllTiersWithCounts();

        return $this->view('partnership/tiers', $this->viewdata);
    }

    public function saveNewTier()
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $info  = [
            'name'        => $this->cleanup($this->request->getPost('name')),
            'description' => $this->cleanup($this->request->getPost('description')),
            'min_amount'  => (float) $this->request->getPost('min_amount'),
            'color'       => $this->cleanup($this->request->getPost('color')) ?: '#6366f1',
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $model->addTier($info);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipTiers'));
    }

    public function updateTierData()
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $id    = (int) $this->request->getPost('tier_id');
        $model = new PartnershipModel();

        $info = [
            'name'        => $this->cleanup($this->request->getPost('name')),
            'description' => $this->cleanup($this->request->getPost('description')),
            'min_amount'  => (float) $this->request->getPost('min_amount'),
            'color'       => $this->cleanup($this->request->getPost('color')) ?: '#6366f1',
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $model->updateTier($id, $info);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipTiers'));
    }

    public function deleteTier(int $id)
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new PartnershipModel();
        $model->deleteTier($id);
        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('partnershipTiers'));
    }

    // ─── Payments ────────────────────────────────────────────────────

    public function paymentPage(int $id)
    {
        $model       = new PartnershipModel();
        $partnership = $model->getPartnershipForPayment($id);

        if (!$partnership) {
            return $this->response->setStatusCode(404)->setBody('Partnership not found or already completed.');
        }

        $settings = (new SettingsModel())->getSettings();

        $this->viewdata['partnership'] = $partnership;
        $this->viewdata['settings']    = $settings;
        $this->viewdata['remaining']   = max(0, (float)$partnership->pledge_amount - (float)$partnership->paid_amount);

        return view('partnership/payment', $this->viewdata);
    }

    public function savePartnershipPayment()
    {
        $data          = $this->get_data();
        $partnershipId = isset($data->partnership_id) ? (int)$data->partnership_id : 0;
        $amount        = isset($data->amount) ? (float)$data->amount : 0;
        $method        = isset($data->type) ? $this->cleanup($data->type) : 'online';
        $reference     = isset($data->reference) ? $this->cleanup($data->reference) : '';

        if ($partnershipId === 0 || $amount <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid payment data']);
            exit;
        }

        $model       = new PartnershipModel();
        $partnership = $model->getPartnershipForPayment($partnershipId);

        if (!$partnership) {
            echo json_encode(['status' => 'error', 'message' => 'Partnership not found']);
            exit;
        }

        $model->recordPayment([
            'partnership_id' => $partnershipId,
            'amount'         => $amount,
            'currency'       => $partnership->currency,
            'method'         => $method,
            'reference'      => $reference,
            'recorded_by'    => 'partner',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['status' => $model->status, 'message' => $model->message]);
        exit;
    }

    public function stripePartnershipCharge()
    {
        $data          = $this->get_data();
        $partnershipId = isset($data->partnership_id) ? (int)$data->partnership_id : 0;
        $amount        = isset($data->amount) ? (float)$data->amount : 0;
        $token         = isset($data->token) ? $data->token : '';

        if ($partnershipId === 0 || $amount <= 0 || empty($token)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            exit;
        }

        $model       = new PartnershipModel();
        $partnership = $model->getPartnershipForPayment($partnershipId);

        if (!$partnership) {
            echo json_encode(['status' => 'error', 'message' => 'Partnership not found']);
            exit;
        }

        $settings = (new SettingsModel())->getSettings();

        try {
            \Stripe\Stripe::setApiKey($settings->stripe_secret);
            $charge = \Stripe\Charge::create([
                'amount'        => (int) round($amount * 100),
                'currency'      => strtolower($partnership->currency ?: 'usd'),
                'receipt_email' => $partnership->partner_email,
                'source'        => $token,
                'description'   => 'Partnership pledge — ' . $partnership->partner_name,
            ]);

            $model->recordPayment([
                'partnership_id' => $partnershipId,
                'amount'         => $amount,
                'currency'       => $partnership->currency,
                'method'         => 'stripe',
                'reference'      => $charge->id,
                'recorded_by'    => 'partner',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            echo json_encode(['status' => 'ok', 'message' => 'Payment successful. Thank you!']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function adminRecordPayment(int $id)
    {
        if (!hasPermission('partnership.edit') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model  = new PartnershipModel();
        $amount = (float) $this->request->getPost('amount');
        $method = $this->cleanup($this->request->getPost('method')) ?: 'manual';
        $ref    = $this->cleanup($this->request->getPost('reference'));
        $notes  = $this->cleanup($this->request->getPost('notes'));

        $partnership = $model->getPartnership($id);

        if (!$partnership || $amount <= 0) {
            $this->session->setFlashdata('error', 'Invalid payment data.');
            return redirect()->to(base_url('editPartnership/' . $id));
        }

        $model->recordPayment([
            'partnership_id' => $id,
            'amount'         => $amount,
            'currency'       => $partnership->currency ?? 'USD',
            'method'         => $method,
            'reference'      => $ref,
            'notes'          => $notes,
            'recorded_by'    => $this->session->get('name') ?? 'admin',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $this->session->setFlashdata($model->status === 'ok' ? 'success' : 'error', $model->message);

        return redirect()->to(base_url('editPartnership/' . $id));
    }
}
