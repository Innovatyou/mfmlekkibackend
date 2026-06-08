<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['email_sms_list']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $list->title; ?></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['add_member_list']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/savenewmemberslist" enctype="multipart/form-data" style="margin-top:30px;">
            <input type="hidden" name="id" value="<?php echo $list->id; ?>">
            <div class="form-group" style="margin-top:20px;">

              <label><?php echo $locale['add_member_to_list']; ?> </label>
              <select name="members[]" class="selectpicker form-control" data-size="5" multiple data-actions-box="true" data-style="btn-outline-secondary">
                <?php foreach ($members as $res) { ?>
                  <option value="<?php echo $res->email; ?>"><?php echo $res->firstname . " " . $res->lastname . " (" . $res->email . ")"; ?></option>
                <?php } ?>
              </select>
            </div>



            <div class="box-footer text-center" style="margin-top:50px;">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['add_to_list']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>