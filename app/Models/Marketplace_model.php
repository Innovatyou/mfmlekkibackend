<?php

namespace App\Models;

use App\Models\Basemodel;

class Marketplace_model extends Basemodel
{
    public $status;
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->status  = 'error';
        $this->message = 'An error occurred.';
    }

    // ─── Categories ───────────────────────────────────────────────────

    public function getCategories()
    {
        return \Config\Database::connect()->table('tbl_marketplace_categories')
            ->orderBy('name', 'ASC')->get()->getResult();
    }

    public function getCategoryInfo($id)
    {
        return \Config\Database::connect()->table('tbl_marketplace_categories')
            ->where('id', $id)->get()->getRow();
    }

    public function addCategory($info)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_marketplace_categories')->insert($info);
        $this->status  = 'ok';
        $this->message = 'Category added successfully.';
        return $db->insertID();
    }

    public function editCategory($info, $id)
    {
        \Config\Database::connect()->table('tbl_marketplace_categories')
            ->where('id', $id)->update($info);
        $this->status  = 'ok';
        $this->message = 'Category updated successfully.';
    }

    public function deleteCategory($id)
    {
        \Config\Database::connect()->table('tbl_marketplace_categories')
            ->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Category deleted.';
    }

    // ─── Item Photos ──────────────────────────────────────────────────

    public function addPhotos($item_id, array $filenames)
    {
        $db    = \Config\Database::connect();
        $order = (int) $db->table('tbl_marketplace_item_photos')
            ->where('item_id', $item_id)->countAllResults();

        foreach ($filenames as $filename) {
            $db->table('tbl_marketplace_item_photos')->insert([
                'item_id'    => $item_id,
                'filename'   => $filename,
                'sort_order' => $order++,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->status  = 'ok';
        $this->message = 'Photos saved.';
    }

    public function getItemPhotos($item_id)
    {
        return \Config\Database::connect()->table('tbl_marketplace_item_photos')
            ->where('item_id', $item_id)
            ->orderBy('sort_order', 'ASC')
            ->get()->getResult();
    }

    public function getPhotoInfo($photo_id)
    {
        return \Config\Database::connect()->table('tbl_marketplace_item_photos')
            ->where('id', $photo_id)->get()->getRow();
    }

    public function deletePhoto($photo_id)
    {
        \Config\Database::connect()->table('tbl_marketplace_item_photos')
            ->where('id', $photo_id)->delete();
        $this->status  = 'ok';
        $this->message = 'Photo deleted.';
    }

    public function deleteAllPhotos($item_id)
    {
        \Config\Database::connect()->table('tbl_marketplace_item_photos')
            ->where('item_id', $item_id)->delete();
    }

    public function countPhotos($item_id)
    {
        return (int) \Config\Database::connect()->table('tbl_marketplace_item_photos')
            ->where('item_id', $item_id)->countAllResults();
    }

    // ─── Items — CRUD ─────────────────────────────────────────────────

    public function addItem($info)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_marketplace_items')->insert($info);
        $this->status  = 'ok';
        $this->message = 'Listing created successfully.';
        return $db->insertID();
    }

    public function editItem($info, $id)
    {
        \Config\Database::connect()->table('tbl_marketplace_items')
            ->where('id', $id)->update($info);
        $this->status  = 'ok';
        $this->message = 'Listing updated successfully.';
    }

    public function deleteItem($id)
    {
        \Config\Database::connect()->table('tbl_marketplace_items')
            ->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Listing deleted.';
    }

    public function getItemInfo($id)
    {
        return \Config\Database::connect()->table('tbl_marketplace_items i')
            ->select('i.*, c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->where('i.id', $id)->get()->getRow();
    }

    public function incrementViews($id)
    {
        \Config\Database::connect()->query(
            'UPDATE tbl_marketplace_items SET views = views + 1 WHERE id = ?', [$id]
        );
    }

    public function updateStatus($id, $status)
    {
        \Config\Database::connect()->table('tbl_marketplace_items')
            ->where('id', $id)->update(['status' => $status]);
        $this->status  = 'ok';
        $this->message = 'Status updated.';
    }

    public function toggleFeatured($id, $flag)
    {
        \Config\Database::connect()->table('tbl_marketplace_items')
            ->where('id', $id)->update(['is_featured' => $flag]);
        $this->status  = 'ok';
        $this->message = 'Item updated.';
    }

    // ─── Items — DataTables listing (admin) ───────────────────────────

    public function getItemsListing($search, $start, $length)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_marketplace_items i')
            ->select('i.id, i.title, i.seller_name, i.seller_email, i.price, i.is_free,
                      i.item_condition, i.status, i.is_featured, i.views, i.created_at,
                      c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('i.title', $search)
                ->orLike('i.seller_name', $search)
                ->orLike('i.seller_email', $search)
                ->groupEnd();
        }

        return $builder->orderBy('i.created_at', 'DESC')->limit($length, $start)->get()->getResult();
    }

    public function getTotalItems($search)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_marketplace_items i');
        if ($search !== '') {
            $builder->groupStart()
                ->like('i.title', $search)
                ->orLike('i.seller_name', $search)
                ->orLike('i.seller_email', $search)
                ->groupEnd();
        }
        return (int) $builder->countAllResults();
    }

    // ─── Items — public browse ────────────────────────────────────────

    public function getActiveItems($category_id = null, $search = '', $limit = 20, $offset = 0)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_marketplace_items i')
            ->select('i.*, c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->where('i.status', 'active')
            ->orderBy('i.is_featured', 'DESC')
            ->orderBy('i.created_at', 'DESC');

        if ($category_id) $builder->where('i.category_id', $category_id);

        if ($search !== '') {
            $builder->groupStart()
                ->like('i.title', $search)
                ->orLike('i.description', $search)
                ->groupEnd();
        }

        return $builder->limit($limit, $offset)->get()->getResult();
    }

    public function countActiveItems($category_id = null, $search = '')
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_marketplace_items')->where('status', 'active');
        if ($category_id) $builder->where('category_id', $category_id);
        if ($search !== '') {
            $builder->groupStart()->like('title', $search)->orLike('description', $search)->groupEnd();
        }
        return (int) $builder->countAllResults();
    }

    // ─── Items — by seller email (member's own listings) ─────────────

    public function getItemsByEmail($email)
    {
        return \Config\Database::connect()->table('tbl_marketplace_items i')
            ->select('i.*, c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->where('i.seller_email', $email)
            ->orderBy('i.created_at', 'DESC')
            ->get()->getResult();
    }

    // ─── Dashboard stats ──────────────────────────────────────────────

    public function getDashboardStats()
    {
        $db = \Config\Database::connect();
        return [
            'total'    => (int) $db->table('tbl_marketplace_items')->countAllResults(),
            'active'   => (int) $db->table('tbl_marketplace_items')->where('status', 'active')->countAllResults(),
            'pending'  => (int) $db->table('tbl_marketplace_items')->where('status', 'pending')->countAllResults(),
            'sold'     => (int) $db->table('tbl_marketplace_items')->where('status', 'sold')->countAllResults(),
            'featured' => (int) $db->table('tbl_marketplace_items')->where('is_featured', 1)->countAllResults(),
            'inquiries_unread' => (int) $db->table('tbl_marketplace_inquiries')->where('is_read', 0)->countAllResults(),
        ];
    }

    public function getRecentListings($limit = 5)
    {
        return \Config\Database::connect()->table('tbl_marketplace_items i')
            ->select('i.id, i.title, i.seller_name, i.price, i.is_free, i.status, i.item_condition, i.created_at, c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->orderBy('i.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResult();
    }

    public function getPendingItems($limit = 5)
    {
        return \Config\Database::connect()->table('tbl_marketplace_items i')
            ->select('i.id, i.title, i.seller_name, i.seller_email, i.price, i.is_free, i.item_condition, i.created_at, c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->where('i.status', 'pending')
            ->orderBy('i.created_at', 'ASC')
            ->limit($limit)
            ->get()->getResult();
    }

    // ─── Inquiries ────────────────────────────────────────────────────

    public function addInquiry($info)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_marketplace_inquiries')->insert($info);
        $this->status  = 'ok';
        $this->message = 'Inquiry sent successfully.';
        return $db->insertID();
    }

    public function getInquiriesForItem($item_id)
    {
        return \Config\Database::connect()->table('tbl_marketplace_inquiries')
            ->where('item_id', $item_id)->orderBy('created_at', 'DESC')->get()->getResult();
    }

    public function markInquiryRead($id)
    {
        \Config\Database::connect()->table('tbl_marketplace_inquiries')
            ->where('id', $id)->update(['is_read' => 1]);
    }

    public function deleteInquiry($id)
    {
        \Config\Database::connect()->table('tbl_marketplace_inquiries')
            ->where('id', $id)->delete();
        $this->status  = 'ok';
        $this->message = 'Inquiry deleted.';
    }
}
