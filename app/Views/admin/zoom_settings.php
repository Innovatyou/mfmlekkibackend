<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Zoom Live Service</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Manage Zoom Meeting</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <div class="pd-20 card-box mb-30">
        <?= view('_flash') ?>

        <?php if (isset($message)): ?>
          <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Info:</strong> <?= $message ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('zoomadmin/update') ?>" style="margin-top: 20px;">
          <?= csrf_field() ?>

          <div class="form-group">
            <label><strong>Meeting Title</strong></label>
            <div class="form-line">
              <input type="text" class="form-control" name="title" value="<?= $zoom['title'] ?? 'SUNDAY NIGHT PRAYER MEETING' ?>" placeholder="e.g., SUNDAY NIGHT PRAYER MEETING" required>
            </div>
          </div>

          <div class="form-group">
            <label><strong>Zoom Meeting URL</strong></label>
            <div class="form-line">
              <textarea class="form-control" name="meeting_url" rows="3" placeholder="https://us06web.zoom.us/j/..." required><?= $zoom['meeting_url'] ?? '' ?></textarea>
            </div>
            <small class="text-muted">⚠️ Keep this URL secure. Do not share publicly.</small>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><strong>Start Time</strong></label>
                <div class="form-line">
                  <input type="time" class="form-control" name="start_time" value="<?= $zoom['start_time'] ?? '20:00:00' ?>" required>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><strong>End Time</strong></label>
                <div class="form-line">
                  <input type="time" class="form-control" name="end_time" value="<?= $zoom['end_time'] ?? '22:30:00' ?>" required>
                </div>
              </div>
            </div>
          </div>

          <div class="box-footer text-center" style="margin-top: 30px;">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-default waves-effect">Cancel</a>
            <button type="submit" class="btn btn-primary waves-effect">Save Changes</button>
          </div>
        </form>
      </div>

      <div class="pd-20 card-box mb-30">
        <h5 class="mb-20">Service Schedule</h5>
        <div class="form-group">
          <p><strong>Meeting Day:</strong> Every Sunday</p>
          <p><strong>Start Time:</strong> 8:00 PM (20:00)</p>
          <p><strong>End Time:</strong> 10:30 PM (22:30)</p>
          <p><strong>Status:</strong> Automatically determined based on current server time</p>
        </div>
      </div>

      <div class="pd-20 card-box mb-30">
        <h5 class="mb-20">API Information</h5>
        <div class="form-group">
          <p><strong>Live Status Endpoint:</strong></p>
          <code>GET /api/zoom/live</code>
          <p style="margin-top: 10px;"><small style="color: #666;">
            Returns the meeting URL when service is live. Mobile app displays a button to open the URL in browser.
          </small></p>
          
          <p style="margin-top: 15px;"><strong>Schedule Endpoint:</strong></p>
          <code>GET /api/zoom/schedule</code>
          <p style="margin-top: 10px;"><small style="color: #666;">
            Returns schedule information (day and times).
          </small></p>
          
          <p style="margin-top: 20px;"><strong>✓ How it works:</strong></p>
          <ul style="margin-left: 20px;">
            <li>When meeting is live, mobile app shows "Join Zoom Meeting" button</li>
            <li>Clicking button opens the Zoom meeting URL in the browser/Zoom app</li>
            <li>When meeting is offline, app shows "Service not live" message</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
