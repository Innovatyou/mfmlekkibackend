<?php

namespace App\Controllers;

use App\Models\MobileAdvert_model as MobileAdvertModel;

class MobileAdverts extends BaseController
{
    public function index()
    {
        $this->viewdata['adverts'] = (new MobileAdvertModel())->orderBy('sort_order', 'ASC')->findAll();
        return $this->view('mobile_adverts/index', $this->viewdata);
    }

    public function store()
    {
        $image = $this->request->getFile('image');
        if (!$image || !$image->isValid()) return redirect()->back()->with('error', 'Please select a valid banner image.');
        if (!in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return redirect()->back()->with('error', 'Banner must be JPG, PNG, or WEBP.');
        }
        if ($image->getSize() > 8 * 1024 * 1024) return redirect()->back()->with('error', 'Banner must not exceed 8 MB.');
        $directory = FCPATH . 'uploads/mobile-adverts';
        if (!is_dir($directory)) mkdir($directory, 0755, true);
        $name = $image->getRandomName();
        $image->move($directory, $name);
        $link = trim((string) $this->request->getPost('link'));
        if ($link !== '' && !filter_var($link, FILTER_VALIDATE_URL)) return redirect()->back()->with('error', 'Enter a valid advert link.');
        (new MobileAdvertModel())->insert([
            'title' => trim((string) $this->request->getPost('title')),
            'image' => base_url('uploads/mobile-adverts/' . $name),
            'link' => $link,
            'active' => $this->request->getPost('active') === '0' ? 0 : 1,
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(base_url('mobileAdverts'))->with('success', 'Mobile advert created.');
    }

    public function toggle($id)
    {
        $model = new MobileAdvertModel();
        $advert = $model->find((int) $id);
        if ($advert) $model->update((int) $id, ['active' => $advert->active ? 0 : 1]);
        return redirect()->to(base_url('mobileAdverts'));
    }

    public function delete($id)
    {
        (new MobileAdvertModel())->delete((int) $id);
        return redirect()->to(base_url('mobileAdverts'))->with('success', 'Mobile advert deleted.');
    }

    public function feed()
    {
        return $this->response->setJSON(['status' => 'ok', 'adverts' => (new MobileAdvertModel())->activeAdverts()]);
    }
}
