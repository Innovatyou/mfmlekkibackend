<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['books_literatures']; ?></h4>
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

          <form method="POST" action="<?php echo base_url(); ?>/saveNewBook" enctype="multipart/form-data" style="margin-top:30px;">



            <div class="form-group">
              <label><?php echo $locale['book_cover']; ?></label>
              <div class="form-line">
                <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['book_pdf']; ?></label>
              <div class="form-line">
                <input type="file" name="book" data-allowed-file-extensions="pdf" class="pdf_dropify">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['book_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $locale['book_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['book_writer']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="author" placeholder="<?php echo $locale['book_writer']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
              <label><?php echo $locale['book_desc']; ?></label>
              <div class="form-line">
                <textarea name="description" rows="3" class="form-control"></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['num_pages']; ?></label>
              <div class="form-line">
                <input type="number" class="form-control" name="pages" required="" autofocus="">
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