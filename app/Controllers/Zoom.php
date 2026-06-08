<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ZoomVideo_model as zoommodel;

class Zoom extends BaseController
{
    use ResponseTrait;

    protected $zoommodel;

    public function __construct()
    {
        $this->zoommodel = new zoommodel();
    }

    /**
     * GET /api/zoom/live
     * 
        * Returns minimal payload for Flutter UI:
        * - title: Sunday Night Prayer Meeting
        * - meeting_url: Zoom link when live, otherwise null
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function live()
    {
        // Disable debugbar for API response
        \Config\Services::toolbar()->hide();

        try {
            // Get the latest Zoom record
            $zoom = $this->zoommodel->getLatestZoom();

            // Check if service is currently LIVE
            $isLive = $this->zoommodel->isServiceLive($zoom);

            $response = [
                'title'       => 'Sunday Night Prayer Meeting',
                'meeting_url' => ($isLive && $zoom) ? ($zoom['meeting_url'] ?? null) : null,
            ];

            return $this->response->setJSON($response)->setStatusCode(200);
        } catch (\Exception $e) {
            // Log error but return graceful response
            log_message('error', 'Zoom API Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'title'       => 'Sunday Night Prayer Meeting',
                'meeting_url' => null,
            ])->setStatusCode(200);
        }
    }

    /**
     * GET /api/zoom/schedule
     * 
     * Returns the schedule information (meeting day and time)
     * This is informational only, for the mobile app to display meeting times
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function schedule()
    {
        // Disable debugbar for API response
        \Config\Services::toolbar()->hide();

        try {
            $zoom = $this->zoommodel->getLatestZoom();

            if ($zoom) {
                $startTime = $zoom['start_time'] ?: '20:00:00';
                $endTime = $zoom['end_time'] ?: '22:30:00';

                $response = [
                    'status' => 'success',
                    'data'   => [
                        'day'        => 'Sunday',
                        'start_time' => $startTime,
                        'end_time'   => $endTime,
                        'title'      => $zoom['title']
                    ]
                ];
            } else {
                $response = [
                    'status' => 'not_configured',
                    'message' => 'Zoom service not yet configured'
                ];
            }

            return $this->response->setJSON($response)->setStatusCode(200);
        } catch (\Exception $e) {
            log_message('error', 'Zoom Schedule API Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to retrieve schedule'
            ])->setStatusCode(200);
        }
    }
}
