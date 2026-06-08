<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['donations']; ?></h4>
            </div>

          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>
          <div style="overflow-x:auto;">
            <table id="donations_table" class="table responsive table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Id</th>
                  <th><?php echo $locale['reason']; ?></th>
                  <th><?php echo $locale['email']; ?></th>
                  <th><?php echo $locale['name']; ?></th>
                  <th><?php echo $locale['reference']; ?></th>
                  <th><?php echo $locale['amount']; ?></th>
                  <th><?php echo $locale['method']; ?></th>
                  <th><?php echo $locale['date']; ?></th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>

    </div>
  </div>