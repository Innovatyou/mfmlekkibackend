<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['email_sms_list']; ?></h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newList" class="btn btn-primary btn-sm"> <?php echo $locale['new_list']; ?></a>
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
                  <th><?php echo $locale['title']; ?></th>
                  <th><?php echo $locale['members']; ?></th>
                  <th><?php echo $locale['date']; ?></th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $count = 1;
                foreach ($lists as $record) {
                ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $record->title; ?></td>
                    <td><?php echo $record->count; ?></td>
                    <td><?php echo $record->date; ?></td>
                    <td class="text-center">
                      <div class="dropdown">
                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                          <i class="dw dw-more"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                          <a class="dropdown-item" href="<?php echo base_url() . '/addMemberstoList/' . $record->id; ?>"><i class="dw dw-add"></i><?php echo $locale['add_members']; ?></a>
                          <a class="dropdown-item" href="<?php echo base_url() . '/viewListMembers/' . $record->id; ?>"><i class="icon-copy ion-ios-people"></i><?php echo $locale['view_members']; ?></a>
                          <a data-type="lists" data-id="<?php echo $record->id; ?>" class="dropdown-item" onclick="delete_item(event)">
                            <i data-type="lists" data-id="<?php echo $record->id; ?>" class="dw dw-delete-3"></i> <?php echo $locale['delete']; ?></a>
                        </div>
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