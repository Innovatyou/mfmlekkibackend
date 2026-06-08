<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['email_sms_list_members']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $list->title; ?></li>
              </ol>
            </nav>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="<?php echo base_url() . '/addMemberstoList/' . $list->id; ?>" class="btn btn-primary btn-sm"><?php echo $locale['add_members']; ?> </a>
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
                  <th><?php echo $locale['email']; ?></th>
                  <th><?php echo $locale['name']; ?></th>
                  <th><?php echo $locale['date']; ?></th>
                  <th class="text-center"><?php echo $locale['action']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $count = 1;
                foreach ($members as $record) {
                ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $record->email; ?></td>
                    <td><?php echo $record->name; ?></td>
                    <td><?php echo $record->date; ?></td>
                    <td class="text-center">
                      <a data-type="listmember" data-id="<?php echo $record->id; ?>" data-list="<?php echo $list->id; ?>" class="dropdown-item" onclick="delete_item(event)">
                        <i style="color:red;" data-type="listmember" data-list="<?php echo $list->id; ?>" data-id="<?php echo $record->id; ?>" class="dw dw-delete-3"></i></a>

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