<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['hymns']; ?></h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newHymn" class="btn btn-primary btn-sm"><?php echo $locale['new_hymn']; ?> </a>
          </div>
        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>
          <div style="overflow-x:auto;">
            <table id="hymns_table" class="table responsive table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th><?php echo $locale['id']; ?></th>
                  <th><?php echo $locale['title']; ?></th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>

    </div>
  </div>