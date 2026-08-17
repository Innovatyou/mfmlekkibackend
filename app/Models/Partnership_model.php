<?php

namespace App\Models;

use App\Models\Basemodel;

class Partnership_model extends Basemodel
{
    public $status;
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->status  = 'error';
        $this->message = 'An error occurred.';
    }

    // ─── Dashboard stats ─────────────────────────────────────────────

    public function getDashboardStats(): array
    {
        $db = \Config\Database::connect();
        $total        = (int) $db->table('tbl_partnerships')->countAllResults();
        $pending      = (int) $db->table('tbl_partnerships')->where('status', 'pending')->countAllResults();
        $active       = (int) $db->table('tbl_partnerships')->where('status', 'active')->countAllResults();
        $overdue      = (int) $db->table('tbl_partnerships')->where('status', 'overdue')->countAllResults();
        $totalPledged = (float) ($db->table('tbl_partnerships')->selectSum('pledge_amount')->get()->getRow()->pledge_amount ?? 0);
        $totalPaid    = (float) ($db->table('tbl_partnerships')->selectSum('paid_amount')->get()->getRow()->paid_amount ?? 0);

        return compact('total', 'pending', 'active', 'overdue', 'totalPledged', 'totalPaid');
    }

    public function getRecentPartnerships(int $limit = 5): array
    {
        $db = \Config\Database::connect();
        return $db->table('tbl_partnerships p')
            ->select('p.id, p.partner_name, p.pledge_amount, p.currency, p.status, p.created_at, t.name AS tier_name, t.color AS tier_color')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResult();
    }

    public function getTierStats(): array
    {
        $db = \Config\Database::connect();
        return $db->table('tbl_partnerships p')
            ->select('t.name AS tier_name, t.color AS tier_color, COUNT(p.id) AS total, SUM(p.pledge_amount) AS pledged')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
            ->where('p.status', 'active')
            ->groupBy('p.tier_id')
            ->orderBy('pledged', 'DESC')
            ->get()->getResult();
    }

    // ─── Partnerships — CRUD ─────────────────────────────────────────

    public function getListingData(string $search, int $start, int $length): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_partnerships p')
            ->select('p.id, p.partner_name, p.partner_email, p.pledge_amount, p.paid_amount, p.currency, p.frequency, p.status, p.start_date, p.created_at, t.name AS tier_name, t.color AS tier_color')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('p.partner_name', $search)
                ->orLike('p.partner_email', $search)
                ->orLike('t.name', $search)
                ->groupEnd();
        }

        return $builder->orderBy('p.created_at', 'DESC')->limit($length, $start)->get()->getResult();
    }

    public function getTotalCount(string $search): int
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_partnerships p')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('p.partner_name', $search)
                ->orLike('p.partner_email', $search)
                ->orLike('t.name', $search)
                ->groupEnd();
        }

        return (int) $builder->countAllResults();
    }

    public function getPartnership(int $id): ?object
    {
        return \Config\Database::connect()
            ->table('tbl_partnerships p')
            ->select('p.*, t.name AS tier_name')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
            ->where('p.id', $id)
            ->get()->getRow();
    }

    public function addPartnership(array $info): int
    {
        $db = \Config\Database::connect();
        $db->table('tbl_partnerships')->insert($info);
        $this->status  = 'ok';
        $this->message = 'Partnership record created successfully.';
        return (int) $db->insertID();
    }

    public function updatePartnership(int $id, array $info): void
    {
        \Config\Database::connect()->table('tbl_partnerships')->where('id', $id)->update($info);
        $this->status  = 'ok';
        $this->message = 'Partnership record updated successfully.';
    }

    public function deletePartnership(int $id): void
    {
        \Config\Database::connect()->table('tbl_partnerships')->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Partnership record deleted.';
    }

    // ─── Tiers ───────────────────────────────────────────────────────

    public function getAllTiers(): array
    {
        return \Config\Database::connect()
            ->table('tbl_partnership_tiers')
            ->where('status', 'active')
            ->orderBy('min_amount', 'ASC')
            ->get()->getResult();
    }

    public function getAllTiersWithCounts(): array
    {
        return \Config\Database::connect()
            ->table('tbl_partnership_tiers t')
            ->select('t.*, COUNT(p.id) AS partner_count')
            ->join('tbl_partnerships p', 'p.tier_id = t.id AND p.status = "active"', 'left')
            ->groupBy('t.id')
            ->orderBy('t.min_amount', 'ASC')
            ->get()->getResult();
    }

    public function addTier(array $info): void
    {
        \Config\Database::connect()->table('tbl_partnership_tiers')->insert($info);
        $this->status  = 'ok';
        $this->message = 'Tier created successfully.';
    }

    public function getTier(int $id): ?object
    {
        return \Config\Database::connect()
            ->table('tbl_partnership_tiers')
            ->where('id', $id)
            ->get()->getRow();
    }

    public function updateTier(int $id, array $info): void
    {
        \Config\Database::connect()->table('tbl_partnership_tiers')->where('id', $id)->update($info);
        $this->status  = 'ok';
        $this->message = 'Tier updated successfully.';
    }

    public function deleteTier(int $id): void
    {
        \Config\Database::connect()->table('tbl_partnership_tiers')->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Tier deleted.';
    }

    // ─── Payments ────────────────────────────────────────────────────

    public function recordPayment(array $info): void
    {
        $db            = \Config\Database::connect();
        $partnershipId = (int) $info['partnership_id'];

        $db->table('tbl_partnership_payments')->insert($info);

        // Recompute paid_amount from all recorded payments
        $paid = (float) ($db->table('tbl_partnership_payments')
            ->selectSum('amount')
            ->where('partnership_id', $partnershipId)
            ->get()->getRow()->amount ?? 0);

        $row = $db->table('tbl_partnerships')->where('id', $partnershipId)->get()->getRow();
        $newStatus = $row ? ($paid >= (float) $row->pledge_amount ? 'completed' : $row->status) : null;

        $update = ['paid_amount' => $paid, 'updated_at' => date('Y-m-d H:i:s')];
        if ($newStatus) $update['status'] = $newStatus;

        $db->table('tbl_partnerships')->where('id', $partnershipId)->update($update);

        $this->status  = 'ok';
        $this->message = 'Payment recorded successfully.';
    }

    public function getPaymentHistory(int $partnershipId): array
    {
        return \Config\Database::connect()
            ->table('tbl_partnership_payments')
            ->where('partnership_id', $partnershipId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResult();
    }

    public function getPartnershipForPayment(int $id): ?object
    {
        return \Config\Database::connect()
            ->table('tbl_partnerships p')
            ->select('p.*, t.name AS tier_name, t.color AS tier_color')
            ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
            ->where('p.id', $id)
            ->whereIn('p.status', ['active', 'overdue'])
            ->get()->getRow();
    }
}
