<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['church_loc']; ?></h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newBranch" class="btn btn-primary btn-sm"> <?php echo $locale['new_loc']; ?></a>
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
                  <th><?php echo $locale['id']; ?></th>
                  <th><?php echo $locale['name']; ?></th>
                  <th><?php echo $locale['address']; ?></th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $count = 1;
                foreach ($branches as $record) {
                ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $record->name; ?></td>
                    <td><?php echo $record->address; ?></td>
                    <td class="text-center">
                      <?php if ($record->id == 1) { ?>
                        ----
                      <?php } else { ?>
                        <div class="dropdown">
                          <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="dw dw-more"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                            <a class="dropdown-item" href="<?php echo base_url() . '/editBranch/' . $record->id; ?>"><i class="dw dw-edit2"></i><?php echo $locale['edit']; ?></a>
                            <a href="#" data-type="branch" data-id="<?php echo $record->id; ?>" class="dropdown-item" onclick="delete_item(event)">
                              <i class="dw dw-delete-3"></i><?php echo $locale['delete']; ?> </a>
                          </div>
                        </div>
                      <?php } ?>

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