<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['videos']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['new_video']; ?></li>
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
              <label><?php echo $locale['video_title_ex']; ?></label>
              <input type="text" class="form-control" id="title" placeholder="Video Title" required="" autofocus="">
            </div>

            <div class="form-group">
              <label><?php echo $locale['vid_desc_ex']; ?></label>
              <div class="form-line">
                <textarea type="text" class="form-control" id="description" placeholder="<?php echo $locale['vid_desc']; ?>" autofocus=""></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['media_type']; ?></label>
                <select class="form-control" id="media_type" required="" autofocus="">
                  <option value="mp4_video" selected>Upload MP4 Video</option>
                  <option value="video_link">mp4 video link</option>
                  <option value="youtube_video">Youtube video id</option>
                </select>
              </div>
            </div>

            <div id="upload_div" style="margin-top:20px;">
              <div class="form-group">
                <label><?php echo $locale['video_cover']; ?></label>
                <div class="form-line">
                  <input id="thumbnail" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" data-height="100">
                </div>
              </div>

              <div class="form-group">

                <div class="form-line">
                  <input id="video-file" type="file" name="video" data-allowed-file-extensions="mp4" class="dropify3" required data-height="100">
                </div>

              </div>
            </div>

            <div id="link_div" style="margin-top:20px; display:none;">
              <div class="form-group" style="margin-top:20px;">
                <label><?php echo $locale['cover_link']; ?></label>
                <div class="form-line">
                  <input type="url" class="form-control" id="thumbnail_link" placeholder="<?php echo $locale['cover_link']; ?>" autofocus="">
                </div>
              </div>

              <div class="form-group" style="margin-top:20px;">
                <label id="video-label"><?php echo $locale['video_lnk']; ?></label>

                <div class="form-line">
                  <input type="url" class="form-control" id="media_link" placeholder="<?php echo $locale['video_lnk']; ?>" autofocus="">
                </div>

              </div>
            </div>


            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['vid_dur_ex']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" id="duration" name="duration" placeholder="<?php echo $locale['vid_dur']; ?>" required="" autofocus="">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">

              <div class="form-line">
                <label><?php echo $locale['allow_free_stream']; ?><br>
                  <small><?php echo $locale['video_subsc']; ?></small></label>
                <select class="form-control" id="is_free" required="" autofocus="">
                  <option value="0" selected><?php echo $locale['yes']; ?></option>
                  <option value="1"><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">

              <div class="form-line">
                <label><?php echo $locale['allow_vid_down']; ?> <br>
                  <small><?php echo $locale['allow_vid_down_avail']; ?></small></label>
                <select class="form-control" id="can_download" required="" autofocus="">
                  <option value="0"><?php echo $locale['yes']; ?></option>
                  <option value="1" selected><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">

              <div class="form-line">
                <label><?php echo $locale['audio_preview']; ?></label>
                <select class="form-control" id="can_preview" required="" autofocus="">
                  <option value="0"><?php echo $locale['yes']; ?></option>
                  <option value="1"><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>


            <div class="box-footer text-center" style="margin-top:20px;">
              <button id="submit" onclick="uploadNewVideo(event)" class="btn btn-primary waves-effect" type="submit"><?php echo $locale['upload_new_vid']; ?></button>
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