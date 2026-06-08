<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['church_loc']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_loc']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/savenewbranch" style="margin-top:30px;">

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['branch_name']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="name" placeholder="<?php echo $locale['branch_name']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_address']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="address" placeholder="<?php echo $locale['loc_address']; ?>" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_pastor']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="pastor" placeholder="<?php echo $locale['loc_pastor']; ?>" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_phone']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="phone" placeholder="<?php echo $locale['loc_phone']; ?>" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_email']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="email" placeholder="<?php echo $locale['loc_email']; ?>" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_latitude']; ?></label>
              <div class="form-line">
                <input type="number" step="any" class="form-control" name="latitude" placeholder="<?php echo $locale['loc_latitude']; ?>" autofocus="" value="0.0">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['loc_longitude']; ?></label>
              <div class="form-line">
                <input type="number" step="any" class="form-control" name="longitude" placeholder="<?php echo $locale['loc_longitude']; ?>" autofocus="" value="0.0">
              </div>
            </div>
            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['save_new']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>