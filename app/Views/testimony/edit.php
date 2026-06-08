<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['testimonies']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_testimony']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/edittestimonydata" enctype="multipart/form-data" style="margin-top:30px;">
            <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $testimony->id; ?>">

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['name_testifier']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="testifier" value="<?php echo $testimony->testifier; ?>" placeholder="<?php echo $locale['name_testifier']; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['testimony_title']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="title" value="<?php echo $testimony->title; ?>" placeholder="<?php echo $locale['testimony_title']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['testimony_content']; ?></label>
              <div class="form-line">
                <textarea class="editor1" name="content"><?php echo $testimony->content; ?></textarea>
              </div>
            </div>


            <div class="box-footer text-center">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update_testimony']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>