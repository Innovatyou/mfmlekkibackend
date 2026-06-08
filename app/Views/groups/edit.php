<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['groups']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_details']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/editGroupData" enctype="multipart/form-data" style="margin-top:30px;">


            <input type="hidden" name="id" value="<?php echo $group->id; ?>">
            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['group_leader_name']; ?> </label>
              <div class="form-line">
                <input type="text" value="<?php echo $group->leader; ?>" class="form-control" name="leader" placeholder="<?php echo $locale['group_leader_name']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label> <?php echo $locale['group_title']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $group->title; ?>" class="form-control" name="title" placeholder="<?php echo $locale['group_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['group_desc']; ?> </label>
              <div class="form-line">
                <textarea type="text" class="form-control" name="description" placeholder="<?php echo $locale['group_desc']; ?>" required="" autofocus="" rows="5"><?php echo $group->description; ?></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['group_meeting_loc']; ?> </label>
              <div class="form-line">
                <input type="text" value="<?php echo $group->location; ?>" class="form-control" name="location" placeholder="<?php echo $locale['group_meeting_loc']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['meeting_days_extra']; ?> </label>
              <div class="form-line">
                <input type="text" value="<?php echo $group->time; ?>" class="form-control" name="time" placeholder="<?php echo $locale['meeting_days']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>