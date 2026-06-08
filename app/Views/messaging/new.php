<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['mail_sms']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_mail_sms']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/sendnewmessage" enctype="multipart/form-data" style="margin-top:30px;">

            <div class="form-group">
              <div class="form-line">
                <label><?php echo $locale['member_list']; ?></label>
                <select id="listpicker" class="form-control" name="list" required="" autofocus="">
                  <option value="0"><?php echo $locale['all_members']; ?></option>
                  <?php foreach ($lists as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->title; ?></option>
                  <?php  } ?>
                </select>
              </div>
            </div>
            <h5 style="margin-top:20px;"><?php echo $locale['member_format']; ?></h5>
            <hr>
            <div class="form-group">
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="custom-control custom-checkbox mb-5">

                    <input type="checkbox" class="custom-control-input" name="formats[]['sms']" id="smsgateway" value="sms" <?php if ($istwilioenabled == 1 && $istermiienabled == 1) { ?> disabled <?php } else { ?>checked<?php } ?>>
                    <label class="custom-control-label" for="smsgateway"><?php echo $locale['text_msg']; ?> <?php if ($istwilioenabled == 1 && $istermiienabled == 1) { ?> <small><?php echo $locale['enable_sms_gateway']; ?></small> <?php } ?></label>
                    <!-- To send SMS to members, Go to Settings to setup any of the sms gateways. -->
                  </div>
                  <div class="custom-control custom-checkbox mb-5">

                    <input type="checkbox" class="custom-control-input" name="formats[]['email']" id="email" value="email" <?php if ($isemailenabled == 1) { ?> disabled <?php } else { ?>checked<?php } ?>>
                    <label class="custom-control-label" for="email"><?php echo $locale['email_msg']; ?> <?php if ($isemailenabled == 1) { ?> <small><?php echo $locale['enable_email_sender']; ?></small> <?php } ?></label>

                    <!--<h6>To send email to members, Go to Settings to setup the email sender configuration.</h6>-->

                  </div>

                </div>
              </div>
            </div>

            <div id="smsgatewaydiv" class="form-group" <?php if ($istwilioenabled == 1 && $istermiienabled == 1) { ?> style="display:none;" <?php } ?>>
              <div class="form-line">
                <label><?php echo $locale['sms_gateway']; ?></label>
                <select class="form-control" id="smsgatewayselect" name="smsgateway" <?php if ($istwilioenabled == 0 || $istermiienabled == 0) { ?> required <?php } ?> ?>>
                  <?php if ($istwilioenabled == 0 && $istermiienabled == 0) { ?> <option value=""><?php echo $locale['select_sms_gateway']; ?></option> <?php } ?>
                  <?php if ($istwilioenabled == 0) { ?> <option value="twilio">TWILIO</option> <?php } ?>
                  <?php if ($istermiienabled == 0) { ?> <option value="termii">TERMII</option> <?php } ?>
                </select>
              </div>
            </div>


            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['msg_sub']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['message_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['msg_content']; ?></label>
              <div class="form-line">
                <textarea type="text" class="form-control" name="message" placeholder="<?php echo $locale['msg_content']; ?>" required="" autofocus="" required></textarea>
              </div>
            </div>

            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['send_new']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>