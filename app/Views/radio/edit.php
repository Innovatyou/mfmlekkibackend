<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['radio_stations']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_radio']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

            <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $radio->id; ?>">


            <div id="upload_div" style="margin-top:20px;">
              <div class="form-group">
                <label><?php echo $locale['radio_cover']; ?></label>
                <div class="form-line">
                  <input name="thumbnail" data-default-file="<?php echo $radio->cover_photo; ?>" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" data-height="100">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['radio_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['radio_title']; ?>" required="" autofocus="" value="<?php echo $radio->title; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['radio_desc']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" value="<?php echo $radio->description; ?>" name="description" placeholder="<?php echo $locale['radio_desc']; ?>" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['radio_link']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="link" value="<?php echo $radio->link; ?>" placeholder="<?php echo $locale['radio_link']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['radio_status']; ?></label>
                <select class="form-control" name="status" required="" autofocus="">
                  <option value="0" <?php echo $radio->status == 0 ? "selected" : ""; ?>><?php echo $locale['live']; ?></option>
                  <option value="1" <?php echo $radio->status == 1 ? "selected" : ""; ?>><?php echo $locale['not_live']; ?></option>
                </select>
              </div>
            </div>


            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update_radio']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>