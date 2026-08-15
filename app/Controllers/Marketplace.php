<?php

namespace App\Controllers;

use App\Models\Marketplace_model as MarketplaceModel;
use App\Models\Settings_model as SettingsModel;

class Marketplace extends BaseController
{
    protected $session;

    private static $currencySymbols = [
        'USD' => '$',
        'GBP' => '£',
        'NGN' => '₦',
    ];

    public function __construct()
    {
        helper(['form', 'url', 'AdminAuth']);
        $this->session = session();

        if ($this->session->get('status') != 0) {
            header("Location: " . base_url());
            exit();
        }

        $settings = (new SettingsModel())->getSettings();
        $code     = $settings->marketplace_currency ?? 'USD';
        $this->viewdata['currency_code']   = $code;
        $this->viewdata['currency_symbol'] = self::$currencySymbols[$code] ?? '$';
    }

    // ─── Admin Listing (DataTables) ───────────────────────────────────

    public function index()
    {
        if (!hasPermission('marketplace.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $model = new MarketplaceModel();
        $this->viewdata['stats']      = $model->getDashboardStats();
        $this->viewdata['categories'] = $model->getCategories();
        return $this->view('marketplace/listing', $this->viewdata);
    }

    public function getItems()
    {
        $model  = new MarketplaceModel();
        $draw   = intval($_POST['draw']);
        $start  = intval($_POST['start']);
        $length = intval($_POST['length']);
        $search = $_POST['search']['value'] ?? '';

        $items = $model->getItemsListing($search, $start, $length);
        $total = $model->getTotalItems($search);

        $settings = \Config\Database::connect()->table('settings')->get()->getRow();
        $code     = $settings->marketplace_currency ?? 'USD';
        $sym      = self::$currencySymbols[$code] ?? '$';

        $dat   = [];
        $count = $start + 1;

        foreach ($items as $r) {
            $priceLabel = $r->is_free
                ? '<span class="badge badge-pill badge-success">Free</span>'
                : $sym . number_format((float)$r->price, 2);

            $statusBadge = match($r->status) {
                'active'  => '<span class="badge badge-pill badge-success">Active</span>',
                'pending' => '<span class="badge badge-pill badge-warning">Pending</span>',
                'sold'    => '<span class="badge badge-pill badge-info">Sold</span>',
                default   => '<span class="badge badge-pill badge-secondary">' . esc($r->status) . '</span>',
            };

            $condBadge = match($r->item_condition) {
                'new'  => '<span class="badge badge-pill" style="background:#e0f2fe;color:#0c4a6e;">New</span>',
                'used' => '<span class="badge badge-pill badge-secondary">Used</span>',
                'free' => '<span class="badge badge-pill badge-success">Free</span>',
                default => esc($r->item_condition),
            };

            $featuredIcon = $r->is_featured
                ? '<i class="dw dw-star" style="color:#f59e0b;" title="Featured"></i>'
                : '<i class="dw dw-star" style="color:#e2e8f0;" title="Not featured"></i>';

            $actions = '
                <div class="dropdown">
                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="' . base_url('viewMarketplaceItem/' . $r->id) . '">
                      <i class="dw dw-eye"></i> View
                    </a>
                    <a class="dropdown-item" href="' . base_url('editMarketplaceItem/' . $r->id) . '">
                      <i class="dw dw-edit2"></i> Edit
                    </a>
                    <a class="dropdown-item" href="' . base_url('approveMarketplaceItem/' . $r->id) . '" onclick="return confirm(\'Approve this listing?\')">
                      <i class="dw dw-check-circle-2"></i> Approve
                    </a>
                    <a class="dropdown-item" href="' . base_url('markItemSold/' . $r->id) . '" onclick="return confirm(\'Mark as sold?\')">
                      <i class="dw dw-wallet1"></i> Mark Sold
                    </a>
                    <a data-type="marketplace" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
                      <i data-type="marketplace" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete
                    </a>
                  </div>
                </div>';

            $dat[] = [
                $count,
                esc($r->title),
                esc($r->seller_name),
                esc($r->category_name ?? '—'),
                $priceLabel,
                $condBadge,
                $statusBadge,
                $featuredIcon . ' ' . number_format($r->views),
                date('M j, Y', strtotime($r->created_at)),
                $actions,
            ];
            $count++;
        }

        header('Content-Type: application/json'); echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $dat,
        ]);
    }

    // ─── Create Listing ───────────────────────────────────────────────

    public function newListing()
    {
        $model = new MarketplaceModel();
        $this->viewdata['categories'] = $model->getCategories();
        return $this->view('marketplace/new', $this->viewdata);
    }

    public function saveNewListing()
    {
        $model  = new MarketplaceModel();
        $isFree = (bool)$this->request->getVar('is_free');

        $info = [
            'church_id'      => $this->session->get('userid') ?? 0,
            'seller_name'    => $this->request->getVar('seller_name'),
            'seller_email'   => $this->request->getVar('seller_email'),
            'seller_phone'   => $this->request->getVar('seller_phone'),
            'category_id'    => $this->request->getVar('category_id') ?: null,
            'title'          => $this->request->getVar('title'),
            'description'    => $this->request->getVar('description'),
            'price'          => $isFree ? 0.00 : (float)$this->request->getVar('price'),
            'is_free'        => $isFree ? 1 : 0,
            'item_condition' => $this->request->getVar('item_condition'),
            'location'       => $this->request->getVar('location'),
            'status'         => 'active',
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        $item_id = $model->addItem($info);

        // Upload photos (max 10)
        if ($item_id && !empty($_FILES['photos']['name'][0])) {
            $saved = $this->uploadMultiplePhotos('photos', 10);
            if (!empty($saved)) {
                $model->addPhotos($item_id, $saved);
                // Set first photo as thumbnail on the item row
                $model->editItem(['image' => $saved[0]], $item_id);
            }
        }

        if ($model->status === 'ok') {
            $this->session->setFlashdata('success', 'Listing created successfully.');
        } else {
            $this->session->setFlashdata('error', $model->message);
        }
        return redirect()->to(base_url('marketplaceListing'));
    }

    // ─── Edit Listing ─────────────────────────────────────────────────

    public function editListing($id = 0)
    {
        $model = new MarketplaceModel();
        $item  = $model->getItemInfo($id);
        if (!$item) {
            return redirect()->to(base_url('marketplaceListing'));
        }
        $this->viewdata['item']        = $item;
        $this->viewdata['photos']      = $model->getItemPhotos($id);
        $this->viewdata['photo_count'] = $model->countPhotos($id);
        $this->viewdata['categories']  = $model->getCategories();
        return $this->view('marketplace/edit', $this->viewdata);
    }

    public function editListingData()
    {
        $model  = new MarketplaceModel();
        $id     = $this->request->getVar('id');
        $isFree = (bool)$this->request->getVar('is_free');

        $info = [
            'seller_name'    => $this->request->getVar('seller_name'),
            'seller_email'   => $this->request->getVar('seller_email'),
            'seller_phone'   => $this->request->getVar('seller_phone'),
            'category_id'    => $this->request->getVar('category_id') ?: null,
            'title'          => $this->request->getVar('title'),
            'description'    => $this->request->getVar('description'),
            'price'          => $isFree ? 0.00 : (float)$this->request->getVar('price'),
            'is_free'        => $isFree ? 1 : 0,
            'item_condition' => $this->request->getVar('item_condition'),
            'location'       => $this->request->getVar('location'),
            'status'         => $this->request->getVar('status'),
        ];

        // Upload new photos up to remaining slots (max 10 total)
        if (!empty($_FILES['photos']['name'][0])) {
            $existing = $model->countPhotos($id);
            $slots    = max(0, 10 - $existing);
            if ($slots > 0) {
                $saved = $this->uploadMultiplePhotos('photos', $slots);
                if (!empty($saved)) {
                    $model->addPhotos($id, $saved);
                    // Refresh thumbnail to first photo if none set
                    $item = $model->getItemInfo($id);
                    if (empty($item->image)) {
                        $info['image'] = $saved[0];
                    }
                }
            }
        }

        $previousItem = $model->getItemInfo($id);
        $newStatus    = $this->request->getVar('status');

        $model->editItem($info, $id);

        if ($model->status === 'ok' && $previousItem && $newStatus === 'inactive'
            && $previousItem->status !== 'inactive' && !empty($previousItem->seller_email)) {
            $this->notify_user(
                $previousItem->seller_email,
                '',
                'Advert Not Approved. Your listing "' . $previousItem->title . '" was not approved. Contact the admin for details.'
            );
        }

        if ($model->status === 'ok') {
            $this->session->setFlashdata('success', $model->message);
        } else {
            $this->session->setFlashdata('error', $model->message);
        }
        return redirect()->to(base_url('editMarketplaceItem/' . $id));
    }

    public function deletePhoto($photo_id = 0)
    {
        $model = new MarketplaceModel();
        $photo = $model->getPhotoInfo($photo_id);
        if (!$photo) {
            return redirect()->to(base_url('marketplaceListing'));
        }
        $item_id = $photo->item_id;

        // Delete physical file
        $path = './uploads/marketplace/' . $photo->filename;
        if (file_exists($path)) unlink($path);

        $model->deletePhoto($photo_id);

        // If deleted photo was the thumbnail, update to next available
        $item = $model->getItemInfo($item_id);
        if ($item && $item->image === $photo->filename) {
            $remaining = $model->getItemPhotos($item_id);
            $model->editItem(['image' => $remaining ? $remaining[0]->filename : null], $item_id);
        }

        $this->session->setFlashdata('success', 'Photo deleted.');
        return redirect()->to(base_url('editMarketplaceItem/' . $item_id));
    }

    // ─── View Item ────────────────────────────────────────────────────

    public function viewItem($id = 0)
    {
        $model = new MarketplaceModel();
        $item  = $model->getItemInfo($id);
        if (!$item) {
            return redirect()->to(base_url('marketplaceListing'));
        }
        $model->incrementViews($id);
        $this->viewdata['item']      = $item;
        $this->viewdata['photos']    = $model->getItemPhotos($id);
        $this->viewdata['inquiries'] = $model->getInquiriesForItem($id);
        return $this->view('marketplace/view', $this->viewdata);
    }

    // ─── Delete ───────────────────────────────────────────────────────

    public function deleteListing($id = 0)
    {
        $model  = new MarketplaceModel();
        $photos = $model->getItemPhotos($id);
        foreach ($photos as $p) {
            $path = './uploads/marketplace/' . $p->filename;
            if (file_exists($path)) unlink($path);
        }
        $model->deleteAllPhotos($id);
        $model->deleteItem($id);
        if ($model->status === 'ok') {
            $this->session->setFlashdata('success', $model->message);
        } else {
            $this->session->setFlashdata('error', $model->message);
        }
        return redirect()->to(base_url('marketplaceListing'));
    }

    // ─── Pending Items DataTable ──────────────────────────────────────

    public function getPendingItems()
    {
        $model  = new MarketplaceModel();
        $draw   = intval($_POST['draw']);
        $start  = intval($_POST['start']);
        $length = intval($_POST['length']);
        $search = $_POST['search']['value'] ?? '';

        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_marketplace_items i')
            ->select('i.id, i.title, i.seller_name, i.seller_email, i.seller_phone,
                      i.price, i.is_free, i.item_condition, i.location, i.description, i.created_at,
                      c.name AS category_name')
            ->join('tbl_marketplace_categories c', 'c.id = i.category_id', 'left')
            ->where('i.status', 'pending');

        if ($search !== '') {
            $builder->groupStart()
                ->like('i.title', $search)
                ->orLike('i.seller_name', $search)
                ->orLike('i.seller_email', $search)
                ->groupEnd();
        }

        $total = (clone $builder)->countAllResults(false);
        $items = $builder->orderBy('i.created_at', 'ASC')->limit($length, $start)->get()->getResult();

        $settings = \Config\Database::connect()->table('settings')->get()->getRow();
        $sym      = self::$currencySymbols[$settings->marketplace_currency ?? 'USD'] ?? '$';

        $dat   = [];
        $count = $start + 1;
        foreach ($items as $r) {
            $price = $r->is_free
                ? '<span class="badge badge-pill badge-success">Free</span>'
                : $sym . number_format((float)$r->price, 2);

            $actions = '
                <div style="display:flex;gap:6px;justify-content:center;">
                  <a href="' . base_url('viewMarketplaceItem/' . $r->id) . '"
                     class="mp-act-btn mp-act-view" title="View"><i class="dw dw-eye"></i></a>
                  <a href="' . base_url('editMarketplaceItem/' . $r->id) . '"
                     class="mp-act-btn mp-act-edit" title="Edit"><i class="dw dw-edit2"></i></a>
                  <button type="button" data-id="' . $r->id . '"
                     class="mp-act-btn mp-act-approve approve-btn" title="Approve">
                     <i class="dw dw-check-circle-2"></i> Approve</button>
                  <button type="button" data-id="' . $r->id . '"
                     class="mp-act-btn mp-act-reject reject-btn" title="Reject">
                     <i class="dw dw-close-circle-1"></i> Reject</button>
                </div>';

            $dat[] = [
                $count,
                esc($r->title),
                esc($r->seller_name) . '<br><span style="font-size:.75rem;color:var(--t3);">' . esc($r->seller_email) . '</span>',
                esc($r->category_name ?? '—'),
                $price,
                date('M j, Y g:i A', strtotime($r->created_at)),
                $actions,
            ];
            $count++;
        }

        header('Content-Type: application/json'); echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $dat,
        ]);
    }

    // ─── Status shortcuts ─────────────────────────────────────────────

    public function approveItem($id = 0)
    {
        $model = new MarketplaceModel();
        $item  = $model->getItemInfo($id);
        $model->updateStatus($id, 'active');

        if ($item && !empty($item->seller_email)) {
            $this->notify_user(
                $item->seller_email,
                '',
                '🎉 Advert Approved! Your listing "' . $item->title . '" is now live on the marketplace.'
            );
        }

        $this->session->setFlashdata('success', 'Listing approved and set to active.');
        return redirect()->to(base_url('marketplaceListing'));
    }

    public function markSold($id = 0)
    {
        $model = new MarketplaceModel();
        $item  = $model->getItemInfo($id);
        $model->updateStatus($id, 'sold');

        if ($item && !empty($item->seller_email)) {
            $this->notify_user(
                $item->seller_email,
                '',
                'Item Marked as Sold. Your listing "' . $item->title . '" has been marked as sold.'
            );
        }

        $this->session->setFlashdata('success', 'Listing marked as sold.');
        return redirect()->to(base_url('marketplaceListing'));
    }

    public function rejectItem($id = 0)
    {
        $model = new MarketplaceModel();
        $model->updateStatus($id, 'inactive');
        $this->session->setFlashdata('success', 'Listing rejected.');
        return redirect()->to(base_url('marketplaceListing'));
    }

    // ─── Inquiries ────────────────────────────────────────────────────

    public function submitInquiry()
    {
        $model = new MarketplaceModel();
        $info  = [
            'item_id'    => $this->request->getVar('item_id'),
            'name'       => $this->request->getVar('name'),
            'email'      => $this->request->getVar('email'),
            'phone'      => $this->request->getVar('phone'),
            'message'    => $this->request->getVar('message'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $model->addInquiry($info);
        if ($model->status === 'ok') {
            $this->session->setFlashdata('success', 'Your inquiry has been sent to the seller.');
        } else {
            $this->session->setFlashdata('error', 'Failed to send inquiry. Please try again.');
        }
        return redirect()->to(base_url('viewMarketplaceItem/' . $info['item_id']));
    }

    public function deleteInquiry($id = 0)
    {
        $model  = new MarketplaceModel();
        $inquiry = \Config\Database::connect()->table('tbl_marketplace_inquiries')
            ->where('id', $id)->get()->getRow();
        $item_id = $inquiry->item_id ?? 0;
        $model->deleteInquiry($id);
        $this->session->setFlashdata('success', 'Inquiry deleted.');
        return redirect()->to(base_url('viewMarketplaceItem/' . $item_id));
    }

    // ─── Categories ───────────────────────────────────────────────────

    public function categories()
    {
        $model = new MarketplaceModel();
        $this->viewdata['categories'] = $model->getCategories();
        return $this->view('marketplace/categories', $this->viewdata);
    }

    public function saveNewCategory()
    {
        $model = new MarketplaceModel();
        $model->addCategory([
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
        if ($model->status === 'ok') {
            $this->session->setFlashdata('success', $model->message);
        } else {
            $this->session->setFlashdata('error', $model->message);
        }
        return redirect()->to(base_url('marketplaceCategories'));
    }

    public function deleteCategory($id = 0)
    {
        $model = new MarketplaceModel();
        $model->deleteCategory($id);
        $this->session->setFlashdata('success', 'Category deleted.');
        return redirect()->to(base_url('marketplaceCategories'));
    }

    // ─── Multi-Photo Upload ───────────────────────────────────────────

    private function uploadMultiplePhotos(string $field, int $max): array
    {
        if (!file_exists('./uploads/marketplace/')) {
            mkdir('./uploads/marketplace/', 0777, true);
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $files   = $this->request->getFileMultiple($field);
        $saved   = [];

        foreach ($files as $file) {
            if (count($saved) >= $max) break;
            if (!$file->isValid() || $file->hasMoved()) continue;
            if (!in_array($file->getMimeType(), $allowed)) continue;
            if ($file->getSize() > 10 * 1024 * 1024) continue;

            $file->move('./uploads/marketplace/');
            $saved[] = $file->getName();
        }

        return $saved;
    }
}

