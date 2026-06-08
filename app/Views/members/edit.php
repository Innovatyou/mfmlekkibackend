<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['members']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_member']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/editMemberData" enctype="multipart/form-data" style="margin-top:30px;">
            <input name="id" value="<?php echo $member->id; ?>" type="hidden" class="form-control" required="" autofocus="">


            <div class="form-group">
              <label><?php echo $locale['optional_member_photo']; ?></label>
              <div class="form-line">
                <input data-default-file="<?php echo $member->thumbnail; ?>" type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['first_name']; ?></label>
              <div class="form-line">
                <input value="<?php echo $member->firstname; ?>" type="text" class="form-control" name="firstname" required="" autofocus="">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['last_name']; ?></label>
              <div class="form-line">
                <input value="<?php echo $member->lastname; ?>" type="text" class="form-control" name="lastname" required="" autofocus="">
              </div>
            </div>

            <div class="form-group">
              <div class="form-line">
                <label class="weight-600"><?php echo $locale['gender']; ?></label>
                <div class="custom-control custom-radio mb-5">
                  <input <?php echo $member->gender == "Male" ? "checked" : ""; ?> type="radio" id="customRadio1" name="gender" class="custom-control-input" value="Male">
                  <label class="custom-control-label" for="customRadio1">Male</label>
                </div>
                <div class="custom-control custom-radio mb-5">
                  <input <?php echo $member->gender == "Female" ? "checked" : ""; ?> type="radio" id="customRadio2" name="gender" class="custom-control-input" value="Female">
                  <label class="custom-control-label" for="customRadio2">Female</label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['email_address']; ?></label>
              <div class="form-line">
                <input type="email" value="<?php echo $member->email; ?>" class="form-control" name="email" required="" autofocus="">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['phone_number']; ?></label>
              <div class="form-line">
                <input type="number" value="<?php echo $member->phonenumber; ?>" class="form-control" name="phonenumber" required="" autofocus="">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['dob']; ?></label>
              <div class="form-line">
                <input type="date" value="<?php echo $member->dob; ?>" class="form-control" name="dob" required="">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['address']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $member->address; ?>" class="form-control" name="address">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['occupation']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $member->occupation; ?>" class="form-control" name="occupation">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['facebook_profile']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $member->facebook; ?>" class="form-control" name="facebook">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['twitter_profile']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $member->twitter; ?>" class="form-control" name="twitter">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['linkedin_profile']; ?></label>
              <div class="form-line">
                <input type="text" value="<?php echo $member->linkedln; ?>" class="form-control" name="linkedln">
              </div>
            </div>











            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit">UPDATE<?php echo $locale['reason']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>