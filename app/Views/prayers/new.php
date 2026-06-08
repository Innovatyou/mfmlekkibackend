<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['prayer_requests']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_request']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>



            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['requester']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="requester" placeholder="<?php echo $locale['requester']; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['request_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['request_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['request_content']; ?></label>
              <div class="form-line">
                <textarea class="editor1" name="content"><?php echo $locale['add_request_content']; ?></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['prayer_visibility']; ?>
                  <small><?php echo $locale['prayer_visibility_desc']; ?></small>
                </label>
                <select class="form-control" name="public" required="" autofocus="">
                  <option value="0"><?php echo $locale['public']; ?></option>
                  <option value="1" selected><?php echo $locale['private']; ?></option>
                </select>
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