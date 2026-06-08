<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['livestream_channels']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_livestream']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/editLivestreamData" enctype="multipart/form-data" style="margin-top:30px;">
            <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $livestream->id; ?>">



            <div id="upload_div" style="margin-top:20px;">
              <div class="form-group">
                <label><?php echo $locale['livestream_cover_ex']; ?></label>
                <div class="form-line">
                  <input name="thumbnail" data-default-file="<?php echo $livestream->cover_photo; ?>" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" data-height="100">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['livestream_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['livestream_title']; ?>" required="" autofocus="" value="<?php echo $livestream->title; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['livestream_desc']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" value="<?php echo $livestream->description; ?>" name="description" placeholder="<?php echo $locale['livestream_desc']; ?>" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['livestream_src']; ?></label>
                <select class="form-control" name="source" required="" autofocus="">
                  <option value="youtube" <?php echo $livestream->source == "youtube" ? "selected" : ""; ?>>Youtube Live Video ID</option>
                  <option value="facebook" <?php echo $livestream->source == "facebook" ? "selected" : ""; ?>>Facebook Live Embed Link</option>
                  <option value="m3u8" <?php echo $livestream->source == "m3u8" ? "selected" : ""; ?>>M3U8</option>
                  <option value="rtmp" <?php echo $livestream->source == "rtmp" ? "selected" : ""; ?>>RTMP</option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['livestream_link']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="link" value="<?php echo $livestream->link; ?>" placeholder="<?php echo $locale['livestream_link']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['livestream_status']; ?></label>
                <select class="form-control" name="status" required="" autofocus="">
                  <option value="0" <?php echo $livestream->status == 0 ? "selected" : ""; ?>><?php echo $locale['live']; ?></option>
                  <option value="1" <?php echo $livestream->status == 1 ? "selected" : ""; ?>><?php echo $locale['not_live']; ?></option>
                </select>
              </div>
            </div>


            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update_live']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>