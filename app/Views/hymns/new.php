<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['hymns']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['add_new']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/saveNewHymn" enctype="multipart/form-data" style="margin-top:30px;">

            <div class="form-group">
              <label><?php echo $locale['hymn_lyric_cover']; ?></label>
              <div class="form-line">
                <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
              </div>
            </div>


            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['hymn_lyric_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['hymn_lyric_title']; ?>" required="" autofocus="">
              </div>
            </div>



            <div class="form-group" style="margin-top:30px;">
              <label><?php echo $locale['hymn_content']; ?></label>
              <div class="form-line">
                <textarea class="editor" name="content"><?php echo $locale['add_hymn_content']; ?></textarea>
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