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
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['view_request']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

            <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $prayer->id; ?>">

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['requester']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="requester" value="<?php echo $prayer->requester; ?>" placeholder="<?php echo $locale['requester']; ?>" readonly>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['request_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" value="<?php echo $prayer->title; ?>" placeholder="<?php echo $locale['request_title']; ?>" required="" autofocus="" readonly>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['request_content']; ?></label>
              <div class="form-line">
                <textarea class="editor1" name="content" readonly><?php echo $prayer->content; ?></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['prayer_visibility']; ?>
                </label>
                <select class="form-control" name="public" required="" autofocus="" readonly disabled>
                  <option value="0" <?php echo $prayer->public == 0 ? 'selected' : ''; ?>><?php echo $locale['public']; ?></option>
                  <option value="1" <?php echo $prayer->public == 1 ? 'selected' : ''; ?>><?php echo $locale['private']; ?></option>
                </select>
              </div>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>