<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['photo_gallery']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_photos']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/savenewphoto" enctype="multipart/form-data" style="margin-top:30px;">


            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['photo_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" id="title" name="title" placeholder="<?php echo $locale['photo_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['photo_desc']; ?></label>
              <div class="form-line">
                <textarea type="text" class="form-control" id="description" name="description" placeholder="<?php echo $locale['photo_desc']; ?>" rows="5"></textarea>
              </div>
            </div>

            <div id="myDrop" class="dropzone" style="margin-top:20px;">
              <div class="dz-message">

                <h3><?php echo $locale['drag_and_drop']; ?></h3>
              </div>
              <div class="fallback">
                <input name="file[]" type="file" multiple />
              </div>
            </div>
            <div class="box-footer text-center" style="margin-top:20px;">
              <button id="submit" onclick="uploadphotos(event)" class="btn btn-primary waves-effect" type="submit"><?php echo $locale['upload_photos']; ?></button>
            </div>
          </form>


        </div>
      </div>

    </div>
  </div>