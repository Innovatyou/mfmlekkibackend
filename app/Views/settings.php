<?php $session = session(); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><?php echo $locale['settings']; ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $locale['edit_settings']; ?></li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?= view('_flash') ?>

          <form method="POST" action="<?php echo base_url(); ?>/updatesettings" enctype="multipart/form-data" style="margin-top:30px;">
            <h5 style="margin-top:0px;"><?php echo $locale['app_features']; ?></h5>
            <hr>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['audiomessages']" id="audiomessages" value="audiomessages" <?php if (strpos($settings->features, "audiomessages") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="audiomessages">Audio Messages</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['videomessages']" id="videomessages" value="videomessages" <?php if (strpos($settings->features, "videomessages") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="videomessages">Video Messages</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['donations']" id="donations" value="donations" <?php if (strpos($settings->features, "donations") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="donations">Donations</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['livestreams']" id="livestreams" value="livestreams" <?php if (strpos($settings->features, "livestreams") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="livestreams">Livestreams</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['events']" id="events" value="events" <?php if (strpos($settings->features, "events") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="events">Events</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['articles']" id="articles" value="articles" <?php if (strpos($settings->features, "articles") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="articles">Articles</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['bible']" id="bible" value="bible" <?php if (strpos($settings->features, "bible") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="bible">Bible</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['notes']" id="notes" value="notes" <?php if (strpos($settings->features, "notes") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="notes">Notes</label>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['hymns']" id="hymns" value="hymns" <?php if (strpos($settings->features, "hymns") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="hymns">Hymns</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['radio']" id="radio" value="radio" <?php if (strpos($settings->features, "radio") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="radio">Radio</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" id="photos" name="features[]['photos']" value="photos" id="photos" <?php if (strpos($settings->features, "photos") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="photos">Photos</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['groups']" id="groups" value="groups" <?php if (strpos($settings->features, "groups") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="groups">Groups</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['prayer']" id="prayer" value="prayer" <?php if (strpos($settings->features, "prayer") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="prayer">Prayer Requests</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['testimony']" id="testimony" value="testimony" <?php if (strpos($settings->features, "testimony") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="testimony">Testimonies</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['books']" id="books" value="books" <?php if (strpos($settings->features, "books") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="books">Christian Books</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['devotionals']" id="devotionals" value="devotionals" <?php if (strpos($settings->features, "devotionals") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="devotionals">Devotionals</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" class="custom-control-input" name="features[]['gosocial']" id="gosocial" value="gosocial" <?php if (strpos($settings->features, "gosocial") !== false) echo "checked"; ?>>
                    <label class="custom-control-label" for="gosocial">Go Social</label>
                  </div>
                </div>
              </div>
            </div>
            <h5 style="margin-top:40px;"><?php echo $locale['miscellaneous']; ?></h5>
            <hr>
            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['website_link']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="website" placeholder="<?php echo $locale['website_link']; ?>" value="<?php echo $settings->website; ?>">
              </div>
            </div>
            <?php if ($session->get('role') == 0) { ?>
              <div class="form-group" style="margin-top:20px; display:none;">
                <label>Firebase Server Key</label>
                <div class="form-line">
                  <input type="text" class="form-control" name="fcm_server_key" placeholder="Firebase Server Key" value="<?php echo $settings->fcm_server_key; ?>">
                </div>
              </div>
            <?php } ?>
            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['enforce_login']; ?></label>
                <select class="form-control" name="app_login" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->app_login == 0 ? "selected" : ""; ?>>YES</option>
                  <option value="1" <?php echo $settings->app_login == 1 ? "selected" : ""; ?>>NO</option>
                </select>
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['allow_downloads']; ?></label><br>
                <small><?php echo $locale['allow_downloads_desc']; ?></small>
                <select class="form-control" name="allow_downloads" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->allow_downloads == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->allow_downloads == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['allow_group_join']; ?></label>
                <small><?php echo $locale['allow_group_join_desc']; ?></small>
                <select class="form-control" name="join_groups" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->join_groups == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->join_groups == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['auto_approve_group_membership']; ?></label>
                <small><?php echo $locale['auto_approve_group_membership_desc']; ?></small>
                <select class="form-control" name="auto_approve_group_membership" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->auto_approve_group_membership == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->auto_approve_group_membership == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['allow_members_prayer_request']; ?></label>
                <small><?php echo $locale['allow_members_prayer_request_desc']; ?></small>
                <select class="form-control" name="post_prayer" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->post_prayer == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->post_prayer == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label></label>
                <small><?php echo $locale['auto_approve_prayer_request']; ?></small>
                <select class="form-control" name="auto_approve_prayer" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->auto_approve_prayer == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->auto_approve_prayer == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['allow_member_testimonies']; ?></label>
                <small><?php echo $locale['allow_member_testimonies_desc']; ?></small>
                <select class="form-control" name="post_testimony" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->post_testimony == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->post_testimony == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label><?php echo $locale['auto_approve_testimonies']; ?></label>
                <small> (If set to No, admins will have to approve members testimonies before it is published.)</small>
                <select class="form-control" name="auto_approve_testimony" required="" autofocus="" style="margin-top:10px;">
                  <option value="0" <?php echo $settings->auto_approve_prayer == 0 ? "selected" : ""; ?>><?php echo $locale['yes']; ?></option>
                  <option value="1" <?php echo $settings->auto_approve_prayer == 1 ? "selected" : ""; ?>><?php echo $locale['no']; ?></option>
                </select>
              </div>
            </div>

            <h5 style="margin-top:40px;"><?php echo $locale['social_media_links']; ?></h5>
            <hr>


            <div class="form-group" style="margin-top:20px;">
              <label>Facebook Page</label>
              <div class="form-line">
                <input type="text" class="form-control" name="facebook" placeholder="Facebook Page" value="<?php echo $settings->facebook; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Youtube Page</label>
              <div class="form-line">
                <input type="text" class="form-control" name="youtube" placeholder="Youtube Page" value="<?php echo $settings->youtube; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Twitter Page</label>
              <div class="form-line">
                <input type="text" class="form-control" name="twitter" placeholder="Twitter Page" value="<?php echo $settings->twitter; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Instagram Page</label>
              <div class="form-line">
                <input type="text" class="form-control" name="instagram" placeholder="Instagram Page" value="<?php echo $settings->instagram; ?>">
              </div>
            </div>


            <h5 style="margin-top:40px;"><?php echo $locale['sms_gateway_settings']; ?><small><?php echo $locale['sms_gateway_settings_desc']; ?></small></h5>
            <hr>
            <h6 style="color:red;"><a href="https://www.twilio.com/">TWILIO SMS GATEWAY</a></h6>
            <div class="form-group" style="margin-top:20px;">
              <label>Twilio Account SID</label>
              <div class="form-line">
                <input type="text" class="form-control" name="twilio_account_sid" placeholder="Account SID" value="<?php echo $settings->twilio_account_sid; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Twilio Auth Token</label>
              <div class="form-line">
                <input type="text" class="form-control" name="twilio_auth_token" placeholder="Auth Token" value="<?php echo $settings->twilio_auth_token; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Twilio Phone Number</label>
              <div class="form-line">
                <input type="text" class="form-control" name="twilio_phonenumber" placeholder="Twilio Sender Phone Number" value="<?php echo $settings->twilio_phonenumber; ?>">
              </div>
            </div>
            <h6 style="margin-top:30px; color:red;"><a href="https://termii.com/">TERMII SMS GATEWAY</a></h6>
            <div class="form-group" style="margin-top:20px;">
              <label>Termii Sender ID</label>
              <div class="form-line">
                <input type="text" class="form-control" name="termi_sender_id" placeholder="Sender ID" value="<?php echo $settings->termi_sender_id; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Termii API Key</label>
              <div class="form-line">
                <input type="text" class="form-control" name="termi_apikey" placeholder="API Key" value="<?php echo $settings->termi_apikey; ?>">
              </div>
            </div>

<h5 style="margin-top:40px;">Email configuration<small>(The fields below will be used to send mails)</small></h5>
<hr>
<div class="form-group" style="margin-top:20px;">
      <label>SMTP username</label>
    <div class="form-line">
        <input type="text" class="form-control" name="mail_username" placeholder="SMTP username" value="<?php echo $settings->mail_username; ?>">
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
      <label>SMTP Password</label>
    <div class="form-line">
        <input type="text" class="form-control" name="mail_password" placeholder="SMTP Password" value="<?php echo $settings->mail_password; ?>">
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
      <label>SMTP HOST</label>
    <div class="form-line">
        <input type="text" class="form-control" name="mail_smtp_host" placeholder="SMTP HOST" value="<?php echo $settings->mail_smtp_host; ?>">
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
      <label>SMTP Protocol</label>
    <div class="form-line">
        <input type="text" class="form-control" name="mail_protocol" placeholder="SMTP Protocol" value="<?php echo $settings->mail_protocol; ?>">
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
      <label>TCP port to connect to</label>
    <div class="form-line">
        <input type="number" class="form-control" name="mail_port" placeholder="TCP port to connect to" value="<?php echo $settings->mail_port; ?>">
    </div>
</div>


            <h5 style="margin-top:40px;"><?php echo $locale['donation_settings']; ?><small><?php echo $locale['donation_settings_desc']; ?></small></h5>
            <hr>
            <div class="form-group" style="margin-top:20px;">
              <label>
                Available Donation Gateways
              </label><br>
              <div class="custom-control custom-checkbox mb-5">
                <input type="checkbox" name="prefered_gateway[]['paypal']" class="custom-control-input" id="Paypal" value="paypal" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "paypal") !== false) echo "checked"; ?>>
                <label class="custom-control-label" for="Paypal">Paypal</label>
              </div>
              <div class="custom-control custom-checkbox mb-5">
                <input type="checkbox" name="prefered_gateway[]['stripe']" class="custom-control-input" value="stripe" id="stripemode" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "stripe") !== false) echo "checked"; ?>>
                <label class="custom-control-label" for="stripemode">Stripe</label>
              </div>
              <div class="custom-control custom-checkbox mb-5">
                <input type="checkbox" name="prefered_gateway[]['flutterwaves']" class="custom-control-input" value="flutterwaves" id="flutterwaves" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "flutterwaves") !== false) echo "checked"; ?>>
                <label class="custom-control-label" for="flutterwaves" id="flutterwaveslabel">FlutterWaves</label>
              </div>
              <div class="custom-control custom-checkbox mb-5">
                <input type="checkbox" name="prefered_gateway[]['paystack']" class="custom-control-input" value="paystack" id="Paystack" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "paystack") !== false) echo "checked"; ?>>
                <label class="custom-control-label" for="Paystack" id="paystacklabel">Paystack</label>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>FlutterWaves Api Key</label>
              <div class="form-line">
                <input type="text" class="form-control" name="flutterwaves_api_key" placeholder="FlutterWaves Api Key" value="<?php echo $settings->flutterwaves_api_key; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>PayStack Api Key</label>
              <div class="form-line">
                <input type="text" class="form-control" name="paystack_api_key" placeholder="PayStack Api Key" value="<?php echo $settings->paystack_api_key; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Paypal Client ID</label>
              <div class="form-line">
                <input type="text" class="form-control" name="paypal_client" placeholder="Paypal Client ID" value="<?php echo $settings->paypal_client; ?>">
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <label>Paypal Secret Key</label>
              <div class="form-line">
                <input type="text" class="form-control" name="paypal_secret" placeholder="Paypal Secret Key" value="<?php echo $settings->paypal_secret; ?>">
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <label>Stripe Key</label>
              <div class="form-line">
                <input type="text" class="form-control" name="stripe_public" placeholder="Stripe Key" value="<?php echo $settings->stripe_public; ?>">
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <label>Stripe Secret</label>
              <div class="form-line">
                <input type="text" class="form-control" name="stripe_secret" placeholder="Stripe Secret" value="<?php echo $settings->stripe_secret; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['currency_code']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="currency_code" placeholder="<?php echo $locale['currency_code']; ?>" value="<?php echo $settings->currency_code; ?>">
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['optional_donation_link']; ?></label>
              <div class="form-line">
                <input type="text" class="form-control" name="donations_link" placeholder="<?php echo $locale['optional_donation_link']; ?>" value="<?php echo $settings->donations_link; ?>">
              </div>
            </div>

            <div id="upload_div" style="margin-top:20px;">
              <div class="form-group">
                <label>Optional Image to show on Donations Page</label>
                <div class="form-line">
                  <input name="thumbnail" data-default-file="<?php echo $settings->donationslogo; ?>" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" data-height="200">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
              <label>Thank you message after donations</label>
              <div class="form-line">
                <textarea class="editor" name="thankyou"><?php echo $settings->thankyou; ?></textarea>
              </div>
            </div>

            <h5 style="margin-top:40px;"><?php echo $locale['other_settings']; ?><small></h5>
            <hr>

            <div class="form-group" style="margin-top:20px;">
              <label><?php echo $locale['church_name']; ?>
                <br><small><?php echo $locale['church_name_desc']; ?></small></label>
              <div class="form-line">
                <input type="text" class="form-control" name="churchname" value="<?php echo $settings->churchname; ?>">
              </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
              <label><?php echo $locale['terms']; ?></label>
              <div class="form-line">
                <textarea class="editor" name="terms"><?php echo $settings->terms; ?></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
              <label><?php echo $locale['privacy']; ?></label>
              <div class="form-line">
                <textarea class="editor" name="privacy"><?php echo $settings->privacy; ?></textarea>
              </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
              <label><?php echo $locale['about']; ?></label>
              <div class="form-line">
                <textarea class="editor" name="aboutus"><?php echo $settings->aboutus; ?></textarea>
              </div>
            </div>


            <div class="box-footer text-center" style="margin-top:20px;">
              <button class="btn btn-primary waves-effect" type="submit"><?php echo $locale['update_settings']; ?></button>
            </div>

          </form>


        </div>
      </div>

    </div>
  </div>
