<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['videos']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['video_listing']; ?></li>
              </ol>
            </nav>
          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newVideo" class="btn btn-primary btn-sm"><?php echo $locale['new_video']; ?> </a>
          </div>
        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>
          <div style="overflow-x:auto;">
            <table id="videos_table" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th><?php echo $locale['id']; ?></th>
                  <th><?php echo $locale['player']; ?></th>
                  <th><?php echo $locale['title']; ?></th>
                  <th><?php echo $locale['description']; ?></th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>

    </div>
  </div>