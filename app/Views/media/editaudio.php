<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['audios']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_audio_details']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form id="upload-form">

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['audio_title_ex']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" id="title" placeholder="<?php echo $locale['audio_title']; ?>" required="" autofocus="" value="<?php echo $audio->title; ?>">
              </div>
            </div>

            <div class="form-group">
              <label><?php echo $locale['audio_desc_ex']; ?></label>
              <div class="form-line">
                <textarea type="text" class="form-control" id="description" placeholder="<?php echo $locale['audio_desc']; ?>" autofocus=""><?php echo $audio->description; ?></textarea>
              </div>
            </div>

            <div id="link_div" style="margin-top:20px; display:none;">
              <div class="form-group" style="margin-top:20px;">
                <label><?php echo $locale['cover_link']; ?></label>
                <div class="form-line">
                  <input type="url" class="form-control" id="thumbnail_link" placeholder="<?php echo $locale['cover_link']; ?> " autofocus="" value="<?php echo $audio->cover_photo; ?>">
                </div>
              </div>

              <div class="form-group" style="margin-top:20px;">
                <label id="video-label"><?php echo $locale['audio_link']; ?></label>

                <div class="form-line">
                  <input type="url" class="form-control" id="media_link" placeholder="<?php echo $locale['audio_link']; ?>" autofocus="" value="<?php echo $audio->source; ?>">
                </div>

              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['audio_duration_ex']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" id="duration" name="duration" placeholder="<?php echo $locale['audio_duration']; ?>" required="" autofocus="" value="<?php echo $audio->duration; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['allow_free_stream']; ?><br>
                  <small><?php echo $locale['allow_free_stream_desc']; ?></small></label>
                <select class="form-control" id="is_free" required="" autofocus="">
                  <option value="0" <?php echo $audio->is_free == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $audio->is_free == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">

              <div class="form-line">
                <label><?php echo $locale['allow_download']; ?><br>
                  <small><?php echo $locale['download_availability']; ?></label>
                <select class="form-control" id="can_download" required="" autofocus="">
                  <option value="0" <?php echo $audio->can_download == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $audio->can_download == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">

              <div class="form-line">
                <label><?php echo $locale['audio_preview']; ?></label>
                <select class="form-control" id="can_preview" required="" autofocus="">
                  <option value="0" <?php echo $audio->can_preview == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $audio->can_preview == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">
              <label><?php echo $locale['audio_preview_dur']; ?></label>
              <div class="form-line">
                <input type="number" class="form-control" id="preview_duration" placeholder="<?php echo $locale['audio_preview_sec']; ?>" required="" autofocus="" value="<?php echo $audio->preview_duration; ?>">
              </div>
            </div>
            <input type="hidden" required="" id="id" autofocus="" value="<?php echo $audio->id; ?>">

            <div class="box-footer text-center">
              <button id="submit" onclick="updateAudio(event)" class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update_audio']; ?></button>
              <ol class="breadcrumb align-center" id="loader" style="display:none;">
                <li><span style="font-size:18px; color:grey; font-style:italic;" id="publish_hint"><?php echo $locale['processing']; ?></span>
                  <br>
                  <div class="preloader pl-size-xs">
                    <div class="spinner-layer pl-teal">
                      <div class="circle-clipper left">
                        <div class="circle"></div>
                      </div>
                      <div class="circle-clipper right">
                        <div class="circle"></div>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>