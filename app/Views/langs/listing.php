<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['admin_dash_lang']; ?></h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newlang" class="btn btn-primary btn-sm"><?php echo $locale['new_lang']; ?> </a>
          </div>
        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>
          <div style="overflow-x:auto;">
            <table id="categories-table" class="table table-bordered table-striped table-hover exportable">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?php echo $locale['id']; ?></th>
                  <th>English</th>
                  <th>French</th>
                  <th>Spanish</th>
                  <th>German</th>
                  <th>Arabic</th>
                  <th>Portugese</th>
                  <th>Portugese-Br</th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $count = 1;
                foreach ($langs as $record) {
                ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $record->id; ?></td>
                    <td><?php echo $record->english; ?></td>
                    <td><?php echo $record->french; ?></td>
                    <td><?php echo $record->spanish; ?></td>
                    <td><?php echo $record->german; ?></td>
                    <td><?php echo $record->arabic; ?></td>
                    <td><?php echo $record->portugese; ?></td>
                    <td><?php echo $record->portugesebr; ?></td>
                    <td class="text-center">
                      <a class="dropdown-item" href="<?php echo base_url() . '/editlang/' . $record->id; ?>"><i class="dw dw-edit2"></i><?php echo $locale['edit']; ?> </a>

          </div>

          </td>
          </tr>
        <?php $count++;
                }
        ?>
        </tbody>
        </table>
        </div>
      </div>
    </div>

  </div>
</div>