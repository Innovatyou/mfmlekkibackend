<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['app_inbox_notifications']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_notification']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/sendnewinbox" enctype="multipart/form-data" style="margin-top:30px;">



            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['inbox_title']; ?>" required="" autofocus="" value="<?php echo $inbox->title; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['inbox_content']; ?></label>
              <div class="form-line">
                <textarea type="text" class="form-control" name="message" placeholder="<?php echo $locale['inbox_content']; ?>" required="" autofocus="" required><?php echo $inbox->message; ?></textarea>
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