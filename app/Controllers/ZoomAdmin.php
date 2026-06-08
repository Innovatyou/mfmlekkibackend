<?php

namespace App\Controllers;

use App\Models\ZoomVideo_model as zoommodel;

class ZoomAdmin extends BaseController
{
    protected $zoommodel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->zoommodel = new zoommodel();
    }

    /**
     * Admin page to manage Zoom settings
     * Auth required (implement your own auth filter)
     */
    public function index()
    {
        $zoom = $this->zoommodel->getAdminData();
        
        $this->viewdata['title']  = 'Manage Zoom Service';
        $this->viewdata['zoom']   = $zoom;

        return $this->view('admin/zoom_settings', $this->viewdata);
    }

    /**
     * Update Zoom meeting details
     * POST endpoint for form submission
     */
    public function updateZoom()
    {
        // You should add auth filter: ['filter' => 'auth']
        
        $title = $this->request->getPost('title');
        $meeting_url = $this->request->getPost('meeting_url');
        $start_time = $this->request->getPost('start_time') ?: '20:00:00';
        $end_time = $this->request->getPost('end_time') ?: '22:30:00';

        // Validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title'        => 'required|min_length[3]|max_length[150]',
            'meeting_url'  => 'required|valid_url',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        if (!$validation->run($_POST)) {
            return redirect('zoomadmin')->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'title'       => $title,
            'meeting_url' => $meeting_url,
            'start_time'  => $start_time,
            'end_time'    => $end_time,
        ];

        if ($this->zoommodel->updateZoomDetails($data)) {
            return redirect('zoomadmin')->with('success', 'Zoom meeting details updated successfully!');
        } else {
            return redirect('zoomadmin')->with('error', 'Failed to update Zoom meeting details.');
        }
    }

    /**
     * API endpoint to update Zoom settings (JSON)
     * Useful for dashboard widgets or AJAX calls
     */
    public function updateZoomJson()
    {
        $input = $this->request->getJSON();

        // Validate input
        if (!isset($input->title) || !isset($input->meeting_url)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Missing required fields: title, meeting_url'
            ])->setStatusCode(400);
        }

        $data = [
            'title'       => filter_var($input->title, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'meeting_url' => filter_var($input->meeting_url, FILTER_SANITIZE_URL),
            'start_time'  => $input->start_time ?? '20:00:00',
            'end_time'    => $input->end_time ?? '22:30:00',
        ];

        if ($this->zoommodel->updateZoomDetails($data)) {
            $updated = $this->zoommodel->getAdminData();
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Zoom meeting details updated',
                'data'    => $updated
            ])->setStatusCode(200);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update Zoom details'
            ])->setStatusCode(500);
        }
    }

    /**
     * View current Zoom status (debug endpoint)
     * Can be secured with auth filter
     */
    public function status()
    {
        $zoom = $this->zoommodel->getLatestZoom();
        $isLive = $this->zoommodel->isServiceLive($zoom);

        $data = [
            'status'       => $isLive ? 'LIVE' : 'OFFLINE',
            'is_live'      => $isLive,
            'current_time' => date('Y-m-d H:i:s'),
            'current_day'  => date('l'),
            'zoom_data'    => $zoom,
        ];

        return $this->response->setJSON($data);
    }
}
