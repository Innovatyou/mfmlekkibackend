<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['events']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_event']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/savenewevent" enctype="multipart/form-data" style="margin-top:30px;">


            <div class="form-group">
              <label><?php echo $locale['event_date']; ?></label>
              <div class="form-line">
                <input type="date" class="form-control" name="date" placeholder="<?php echo $locale['event_date']; ?>" required="">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['event_time']; ?></label>
              <div class="form-line">
                <input type="time" class="form-control" name="time" placeholder="<?php echo $locale['event_time']; ?>" required="">
              </div>
            </div>

            <div class="form-group">

              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['event_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group">

              <div class="form-line">
                <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify" required>
              </div>
            </div>



            <div class="form-group" style="margin-top:30px;">
              <label>Event Details</label>
              <div class="form-line">
                <textarea class="editor" name="details"><?php echo $locale['add_event_detail']; ?></textarea>
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