<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['languages']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_lang']; ?></li>
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
              <label><?php echo $locale['language_id']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="id" placeholder="<?php echo $locale['language_id']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>English</label>
              <div class="form-line">
                <input type="text" class="form-control" name="english" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>French</label>
              <div class="form-line">
                <input type="text" class="form-control" name="french" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Spanish</label>
              <div class="form-line">
                <input type="text" class="form-control" name="spanish" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>German</label>
              <div class="form-line">
                <input type="text" class="form-control" name="german" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Arabic</label>
              <div class="form-line">
                <input type="text" class="form-control" name="arabic" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Portugese</label>
              <div class="form-line">
                <input type="text" class="form-control" name="portugese" required="" autofocus="" required>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Portugese-Br</label>
              <div class="form-line">
                <input type="text" class="form-control" name="portugesebr" required="" autofocus="" required>
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