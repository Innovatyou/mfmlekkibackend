<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['settings'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['edit_settings'] ?></span></nav>
      </div>
    </div>

    <?php if(session()->getFlashdata('success')):?>
    <div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div>
    <?php endif;?>
    <?php if(session()->getFlashdata('error')):?>
    <div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div>
    <?php endif;?>

    <form method="POST" action="<?= base_url('updatesettings') ?>" enctype="multipart/form-data" id="settings-form">
      <?= csrf_field() ?>

      <div class="st-layout">

        <!-- ── Sidebar ── -->
        <aside class="st-sidebar">
          <nav class="st-nav">
            <a class="st-nav-item" data-tab="features" href="#features">
              <span class="st-nav-icon"><i class="dw dw-grid-2"></i></span>
              <span class="st-nav-label">App Features</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="general" href="#general">
              <span class="st-nav-icon"><i class="dw dw-settings2"></i></span>
              <span class="st-nav-label">General</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="social" href="#social">
              <span class="st-nav-icon"><i class="dw dw-network"></i></span>
              <span class="st-nav-label">Social Media</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="notifications" href="#notifications">
              <span class="st-nav-icon"><i class="dw dw-mail-1"></i></span>
              <span class="st-nav-label">Notifications</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="payments" href="#payments">
              <span class="st-nav-icon"><i class="dw dw-credit-card"></i></span>
              <span class="st-nav-label">Payments</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="content" href="#content">
              <span class="st-nav-icon"><i class="dw dw-edit-3"></i></span>
              <span class="st-nav-label">Content</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
          </nav>
          <div class="st-sidebar-save">
            <button type="submit" class="btn btn-primary" style="width:100%;font-weight:600;border-radius:9px;padding:10px;">
              <i class="dw dw-check" style="margin-right:6px;"></i><?= $locale['update_settings'] ?>
            </button>
          </div>
        </aside>

        <!-- ── Panels ── -->
        <div class="st-panels">

          <!-- ══ App Features ══ -->
          <div class="st-panel" id="tab-features">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">App Features</h2>
                <p class="st-panel-sub">Toggle which features are visible in the mobile app</p>
              </div>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div class="nf-feat-grid">
                  <?php
                  $featureList = [
                    'marketplace'   => ['Marketplace',      'dw-shopping-cart'],
                    'counseling'    => ['Counseling',       'dw-chat-3'],
                    'wellness'      => ['Wellness',         'dw-heart-1'],
                    'partnership'   => ['Partnership',      'dw-handshake'],
                    'audiomessages' => ['Audio Messages',   'dw-music'],
                    'videomessages' => ['Video Messages',   'dw-video-1'],
                    'donations'     => ['Donations',        'dw-heart'],
                    'livestreams'   => ['Livestreams',      'dw-video'],
                    'events'        => ['Events',           'dw-calendar'],
                    'articles'      => ['Articles',         'dw-news'],
                    'bible'         => ['Bible',            'dw-book'],
                    'notes'         => ['Notes',            'dw-edit-2'],
                    'hymns'         => ['Hymns',            'dw-music-1'],
                    'radio'         => ['Radio',            'dw-radio'],
                    'photos'        => ['Photos',           'dw-image'],
                    'groups'        => ['Groups',           'dw-user-2'],
                    'prayer'        => ['Prayer Requests',  'dw-pray'],
                    'testimony'     => ['Testimonies',      'dw-chat-2'],
                    'books'         => ['Christian Books',  'dw-library'],
                    'devotionals'   => ['Devotionals',      'dw-bookmark'],
                    'gosocial'      => ['Go Social',        'dw-share'],
                  ];
                  foreach ($featureList as $key => [$label, $icon]):
                    $checked = strpos($settings->features, $key) !== false ? 'checked' : '';
                  ?>
                  <label class="nf-feat-item">
                    <input type="checkbox" name="features[]['<?= $key ?>']" value="<?= $key ?>" <?= $checked ?>>
                    <span class="nf-feat-box">
                      <span class="nf-feat-icon"><i class="dw <?= $icon ?>"></i></span>
                      <span class="nf-feat-check"><i class="dw dw-check"></i></span>
                      <span class="nf-feat-name"><?= $label ?></span>
                    </span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ General ══ -->
          <div class="st-panel" id="tab-general">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">General Settings</h2>
                <p class="st-panel-sub">App behaviour, content permissions and member controls</p>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Mobile App Availability</h3><p class="nf-card-sub">Hide or show the entire member app</p></div>
              <div class="nf-card-body">
                <div class="st-toggle-row">
                  <div><p class="st-toggle-label">Mobile app enabled</p><p class="st-toggle-hint">Hidden prevents members from opening app modules.</p></div>
                  <select name="mobile_app_enabled" class="nf-input st-inline-select">
                    <option value="1" <?= ($settings->mobile_app_enabled ?? 1)==1?'selected':'' ?>>Visible</option>
                    <option value="0" <?= ($settings->mobile_app_enabled ?? 1)==0?'selected':'' ?>>Hidden</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Church Website</h3></div>
              <div class="nf-card-body">
                <label class="nf-label"><?= $locale['website_link'] ?></label>
                <input type="text" name="website" class="nf-input" placeholder="https://yourchurch.org" value="<?= esc($settings->website) ?>">
              </div>
            </div>

            <?php if ($session->get('role') == 0): ?>
            <div class="nf-card" style="margin-bottom:16px;display:none;">
              <div class="nf-card-body">
                <label class="nf-label">Firebase Server Key</label>
                <input type="text" name="fcm_server_key" class="nf-input" placeholder="Firebase Server Key" value="<?= esc($settings->fcm_server_key) ?>">
              </div>
            </div>
            <?php endif; ?>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Member Permissions</h3><p class="nf-card-sub">Control what members can do in the app</p></div>
              <div class="nf-card-body">
                <div class="st-toggle-list">
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['enforce_login'] ?></p>
                      <p class="st-toggle-hint">Require login to access app content</p>
                    </div>
                    <select name="app_login" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->app_login==0?'selected':'' ?>>Yes</option>
                      <option value="1" <?= $settings->app_login==1?'selected':'' ?>>No</option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['allow_downloads'] ?></p>
                      <p class="st-toggle-hint"><?= $locale['allow_downloads_desc'] ?></p>
                    </div>
                    <select name="allow_downloads" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->allow_downloads==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->allow_downloads==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['allow_group_join'] ?></p>
                      <p class="st-toggle-hint"><?= $locale['allow_group_join_desc'] ?></p>
                    </div>
                    <select name="join_groups" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->join_groups==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->join_groups==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['auto_approve_group_membership'] ?></p>
                      <p class="st-toggle-hint"><?= $locale['auto_approve_group_membership_desc'] ?></p>
                    </div>
                    <select name="auto_approve_group_membership" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->auto_approve_group_membership==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->auto_approve_group_membership==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['allow_members_prayer_request'] ?></p>
                      <p class="st-toggle-hint"><?= $locale['allow_members_prayer_request_desc'] ?></p>
                    </div>
                    <select name="post_prayer" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->post_prayer==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->post_prayer==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['auto_approve_prayer_request'] ?></p>
                      <p class="st-toggle-hint">If No, admins must approve before publishing</p>
                    </div>
                    <select name="auto_approve_prayer" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->auto_approve_prayer==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->auto_approve_prayer==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['allow_member_testimonies'] ?></p>
                      <p class="st-toggle-hint"><?= $locale['allow_member_testimonies_desc'] ?></p>
                    </div>
                    <select name="post_testimony" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->post_testimony==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->post_testimony==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                  <div class="st-toggle-row">
                    <div>
                      <p class="st-toggle-label"><?= $locale['auto_approve_testimonies'] ?></p>
                      <p class="st-toggle-hint">If No, admins must approve before publishing</p>
                    </div>
                    <select name="auto_approve_testimony" class="nf-input st-inline-select">
                      <option value="0" <?= $settings->auto_approve_prayer==0?'selected':'' ?>><?= $locale['yes'] ?></option>
                      <option value="1" <?= $settings->auto_approve_prayer==1?'selected':'' ?>><?= $locale['no'] ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Social Media ══ -->
          <div class="st-panel" id="tab-social">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">Social Media</h2>
                <p class="st-panel-sub">Links shown in the app's connect screen</p>
              </div>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div class="st-social-grid">
                  <div class="st-social-field">
                    <div class="st-social-icon st-facebook"><i class="dw dw-facebook-1"></i></div>
                    <div style="flex:1;">
                      <label class="nf-label">Facebook</label>
                      <input type="text" name="facebook" class="nf-input" placeholder="https://facebook.com/yourpage" value="<?= esc($settings->facebook) ?>">
                    </div>
                  </div>
                  <div class="st-social-field">
                    <div class="st-social-icon st-youtube"><i class="dw dw-youtube"></i></div>
                    <div style="flex:1;">
                      <label class="nf-label">YouTube</label>
                      <input type="text" name="youtube" class="nf-input" placeholder="https://youtube.com/yourchannel" value="<?= esc($settings->youtube) ?>">
                    </div>
                  </div>
                  <div class="st-social-field">
                    <div class="st-social-icon st-twitter"><i class="dw dw-twitter-1"></i></div>
                    <div style="flex:1;">
                      <label class="nf-label">Twitter / X</label>
                      <input type="text" name="twitter" class="nf-input" placeholder="https://twitter.com/yourhandle" value="<?= esc($settings->twitter) ?>">
                    </div>
                  </div>
                  <div class="st-social-field">
                    <div class="st-social-icon st-instagram"><i class="dw dw-instagram"></i></div>
                    <div style="flex:1;">
                      <label class="nf-label">Instagram</label>
                      <input type="text" name="instagram" class="nf-input" placeholder="https://instagram.com/yourprofile" value="<?= esc($settings->instagram) ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Notifications ══ -->
          <div class="st-panel" id="tab-notifications">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">Notifications</h2>
                <p class="st-panel-sub">SMS and email credentials for member notifications</p>
              </div>
            </div>

            <!-- SMS -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head">
                <h3 class="nf-card-title"><?= $locale['sms_gateway_settings'] ?></h3>
                <p class="nf-card-sub"><?= $locale['sms_gateway_settings_desc'] ?></p>
              </div>
              <div class="nf-card-body">
                <div class="st-provider-block">
                  <div class="st-provider-label st-provider-twilio">
                    <i class="dw dw-phone"></i> Twilio
                  </div>
                  <div class="nf-row" style="margin-bottom:14px;">
                    <div class="nf-col-half">
                      <label class="nf-label">Account SID</label>
                      <input type="text" name="twilio_account_sid" class="nf-input" placeholder="ACxxxxxxxx" value="<?= esc($settings->twilio_account_sid) ?>">
                    </div>
                    <div class="nf-col-half">
                      <label class="nf-label">Auth Token</label>
                      <input type="password" name="twilio_auth_token" class="nf-input" placeholder="Auth Token" value="<?= esc($settings->twilio_auth_token) ?>">
                    </div>
                  </div>
                  <div>
                    <label class="nf-label">Sender Phone Number</label>
                    <input type="text" name="twilio_phonenumber" class="nf-input" placeholder="+1234567890" value="<?= esc($settings->twilio_phonenumber) ?>" style="max-width:260px;">
                  </div>
                </div>
                <div class="st-provider-block" style="margin-top:20px;">
                  <div class="st-provider-label st-provider-termii">
                    <i class="dw dw-phone-2"></i> Termii
                  </div>
                  <div class="nf-row">
                    <div class="nf-col-half">
                      <label class="nf-label">Sender ID</label>
                      <input type="text" name="termi_sender_id" class="nf-input" placeholder="Sender ID" value="<?= esc($settings->termi_sender_id) ?>">
                    </div>
                    <div class="nf-col-half">
                      <label class="nf-label">API Key</label>
                      <input type="password" name="termi_apikey" class="nf-input" placeholder="API Key" value="<?= esc($settings->termi_apikey) ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Email -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head">
                <h3 class="nf-card-title">Email Configuration</h3>
                <p class="nf-card-sub">SMTP settings used to send emails from the system</p>
              </div>
              <div class="nf-card-body">
                <div class="nf-row" style="margin-bottom:14px;">
                  <div class="nf-col-half">
                    <label class="nf-label">SMTP Username</label>
                    <input type="text" name="mail_username" class="nf-input" placeholder="you@gmail.com" value="<?= esc($settings->mail_username) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">SMTP Password</label>
                    <input type="password" name="mail_password" class="nf-input" placeholder="App password" value="<?= esc($settings->mail_password) ?>">
                  </div>
                </div>
                <div class="nf-row" style="margin-bottom:14px;">
                  <div class="nf-col-half">
                    <label class="nf-label">SMTP Host</label>
                    <input type="text" name="mail_smtp_host" class="nf-input" placeholder="smtp.gmail.com" value="<?= esc($settings->mail_smtp_host) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">SMTP Protocol</label>
                    <input type="text" name="mail_protocol" class="nf-input" placeholder="tls" value="<?= esc($settings->mail_protocol) ?>">
                  </div>
                </div>
                <div style="max-width:200px;">
                  <label class="nf-label">TCP Port</label>
                  <input type="number" name="mail_port" class="nf-input" placeholder="587" value="<?= esc($settings->mail_port) ?>">
                </div>
              </div>
            </div>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Payments ══ -->
          <div class="st-panel" id="tab-payments">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">Payments &amp; Donations</h2>
                <p class="st-panel-sub">Configure payment gateways, currencies and donation content</p>
              </div>
            </div>

            <!-- Gateway API Keys -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Gateway API Keys</h3><p class="nf-card-sub">Shared by donation and book-store payments</p></div>
              <div class="nf-card-body">
                <div class="st-key-grid">
                  <div class="st-key-group st-key-paystack">
                    <p class="st-key-brand"><i class="dw dw-credit-card"></i> Paystack</p>
                    <label class="nf-label">Secret Key</label>
                    <input type="password" name="paystack_api_key" class="nf-input" placeholder="sk_live_xxxxxxxx" value="<?= esc($settings->paystack_api_key) ?>">
                  </div>
                  <div class="st-key-group st-key-flutter">
                    <p class="st-key-brand"><i class="dw dw-credit-card-1"></i> Flutterwave</p>
                    <label class="nf-label">Secret Key</label>
                    <input type="password" name="flutterwaves_api_key" class="nf-input" placeholder="FLWSECK-xxxxxxxx" value="<?= esc($settings->flutterwaves_api_key) ?>">
                  </div>
                  <div class="st-key-group st-key-stripe">
                    <p class="st-key-brand"><i class="dw dw-credit-card-2"></i> Stripe</p>
                    <div class="nf-row" style="gap:10px;">
                      <div class="nf-col-half">
                        <label class="nf-label">Public Key</label>
                        <input type="text" name="stripe_public" class="nf-input" placeholder="pk_live_xxxxxxxx" value="<?= esc($settings->stripe_public) ?>">
                      </div>
                      <div class="nf-col-half">
                        <label class="nf-label">Secret Key</label>
                        <input type="password" name="stripe_secret" class="nf-input" placeholder="sk_live_xxxxxxxx" value="<?= esc($settings->stripe_secret) ?>">
                      </div>
                    </div>
                  </div>
                  <div class="st-key-group st-key-paypal">
                    <p class="st-key-brand"><i class="dw dw-paypal"></i> PayPal</p>
                    <div class="nf-row" style="gap:10px;">
                      <div class="nf-col-half">
                        <label class="nf-label">Client ID</label>
                        <input type="text" name="paypal_client" class="nf-input" placeholder="Client ID" value="<?= esc($settings->paypal_client) ?>">
                      </div>
                      <div class="nf-col-half">
                        <label class="nf-label">Secret Key</label>
                        <input type="password" name="paypal_secret" class="nf-input" placeholder="Secret Key" value="<?= esc($settings->paypal_secret) ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Donation Gateways -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['donation_settings'] ?></h3><p class="nf-card-sub"><?= $locale['donation_settings_desc'] ?></p></div>
              <div class="nf-card-body">
                <label class="nf-label" style="margin-bottom:10px;display:block;">Active Donation Gateways</label>
                <div class="nf-feat-grid" style="margin-bottom:20px;">
                  <?php
                  $gateways = ['paypal'=>'PayPal','stripe'=>'Stripe','flutterwaves'=>'FlutterWaves','paystack'=>'Paystack'];
                  foreach($gateways as $gk => $gl):
                    $gc = strpos($settings->prefered_gateway, $gk) !== false ? 'checked' : '';
                  ?>
                  <label class="nf-feat-item">
                    <input type="checkbox" name="prefered_gateway[]['<?= $gk ?>']" value="<?= $gk ?>" <?= $gc ?>>
                    <span class="nf-feat-box">
                      <span class="nf-feat-check"><i class="dw dw-check"></i></span>
                      <span class="nf-feat-name"><?= $gl ?></span>
                    </span>
                  </label>
                  <?php endforeach; ?>
                </div>
                <div class="nf-row" style="margin-bottom:14px;">
                  <div class="nf-col-half">
                    <label class="nf-label"><?= $locale['currency_code'] ?></label>
                    <input type="text" name="currency_code" class="nf-input" placeholder="e.g. USD" value="<?= esc($settings->currency_code) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label"><?= $locale['optional_donation_link'] ?></label>
                    <input type="text" name="donations_link" class="nf-input" placeholder="<?= $locale['optional_donation_link'] ?>" value="<?= esc($settings->donations_link) ?>">
                  </div>
                </div>
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Donation Page Image (Optional)</label>
                  <input name="thumbnail" data-default-file="<?= esc($settings->donationslogo) ?>" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" data-height="200">
                </div>
                <div>
                  <label class="nf-label">Thank You Message After Donation</label>
                  <div class="nf-card" style="margin-top:6px;">
                    <div class="nf-card-body" style="padding:12px;">
                      <textarea class="editor" name="thankyou"><?= $settings->thankyou ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Book Store Gateway -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Book Store Payment Gateway</h3><p class="nf-card-sub">Gateway used when members purchase books. Uses the API keys above.</p></div>
              <div class="nf-card-body">
                <?php
                  $bpg = $settings->book_payment_gateway ?? 'paystack';
                  $bpgOptions = [
                    'paystack'     => ['Paystack',    'dw dw-credit-card',   '#0ba4db', 'Best for NGN / African cards'],
                    'flutterwaves' => ['Flutterwave', 'dw dw-credit-card-1', '#f5a623', 'Multi-currency African gateway'],
                    'stripe'       => ['Stripe',      'dw dw-credit-card-2', '#635bff', 'Best for USD / international'],
                  ];
                ?>
                <div style="display:flex;gap:14px;flex-wrap:wrap;">
                  <?php foreach($bpgOptions as $gk => [$gl, $gi, $gc_col, $gd]): ?>
                  <label class="bpg-label">
                    <input type="radio" name="book_payment_gateway" value="<?= $gk ?>" class="bpg-radio" <?= $bpg === $gk ? 'checked' : '' ?>>
                    <span class="bpg-option <?= $bpg === $gk ? 'bpg-active' : '' ?>" style="--bpg-color:<?= $gc_col ?>;">
                      <i class="<?= $gi ?>" style="font-size:1.4rem;color:<?= $gc_col ?>;"></i>
                      <span style="font-weight:700;font-size:.88rem;color:var(--t1);"><?= $gl ?></span>
                      <span style="font-size:.73rem;color:var(--t3);"><?= $gd ?></span>
                    </span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <!-- Marketplace Currency -->
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Marketplace Currency</h3><p class="nf-card-sub">Used for all item prices in the church marketplace</p></div>
              <div class="nf-card-body">
                <?php
                  $mpCurrency = $settings->marketplace_currency ?? 'USD';
                  $currencies = [
                    'USD' => ['US Dollar',      '$',  '🇺🇸'],
                    'GBP' => ['British Pounds', '£',  '🇬🇧'],
                    'NGN' => ['Nigerian Naira', '₦',  '🇳🇬'],
                    'GHS' => ['Ghanaian Cedi',  '₵',  '🇬🇭'],
                    'KES' => ['Kenyan Shilling','KSh','🇰🇪'],
                    'ZAR' => ['South African Rand','R','🇿🇦'],
                  ];
                ?>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                  <?php foreach ($currencies as $code => [$clabel, $csymbol, $cflag]): ?>
                    <label style="cursor:pointer;display:flex;align-items:center;gap:0;">
                      <input type="radio" name="marketplace_currency" value="<?= $code ?>"
                        <?= $mpCurrency === $code ? 'checked' : '' ?>
                        style="display:none;" class="mp-currency-radio">
                      <span class="mp-currency-option <?= $mpCurrency === $code ? 'mp-currency-active' : '' ?>" data-code="<?= $code ?>">
                        <span style="font-size:1.1rem;"><?= $cflag ?></span>
                        <span style="font-weight:700;font-size:.9rem;"><?= $csymbol ?></span>
                        <span style="font-size:.78rem;color:var(--t2);"><?= $clabel ?></span>
                        <span style="font-size:.73rem;font-weight:600;color:var(--t3);">(<?= $code ?>)</span>
                      </span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Content ══ -->
          <div class="st-panel" id="tab-content">
            <div class="st-panel-head">
              <div>
                <h2 class="st-panel-title">Content</h2>
                <p class="st-panel-sub">Church name and legal / informational pages</p>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['church_name'] ?></h3></div>
              <div class="nf-card-body">
                <label class="nf-label"><?= $locale['church_name'] ?></label>
                <p style="font-size:.75rem;color:var(--t3);margin:0 0 6px;"><?= $locale['church_name_desc'] ?></p>
                <input type="text" name="churchname" class="nf-input" value="<?= esc($settings->churchname) ?>" style="max-width:400px;">
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Brand Color</h3></div>
              <div class="nf-card-body">
                <label class="nf-label">Accent Color</label>
                <p style="font-size:.75rem;color:var(--t3);margin:0 0 6px;">Used for buttons, links, and highlights across the admin dashboard and login page.</p>
                <div style="display:flex;align-items:center;gap:10px;">
                  <input type="color" name="brand_color" id="brand_color_picker" value="<?= esc($settings->brand_color ?: '#6366f1') ?>" style="width:48px;height:38px;padding:2px;border:1px solid var(--b1);border-radius:8px;cursor:pointer;" oninput="document.getElementById('brand_color_hex').value=this.value;">
                  <input type="text" class="nf-input" id="brand_color_hex" value="<?= esc($settings->brand_color ?: '#6366f1') ?>" style="max-width:140px;" pattern="^#[0-9A-Fa-f]{6}$" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)){document.getElementById('brand_color_picker').value=this.value;}">
                </div>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['terms'] ?></h3></div>
              <div class="nf-card-body" style="padding:12px;">
                <textarea class="editor" name="terms"><?= $settings->terms ?></textarea>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['privacy'] ?></h3></div>
              <div class="nf-card-body" style="padding:12px;">
                <textarea class="editor" name="privacy"><?= $settings->privacy ?></textarea>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['about'] ?></h3></div>
              <div class="nf-card-body" style="padding:12px;">
                <textarea class="editor" name="aboutus"><?= $settings->aboutus ?></textarea>
              </div>
            </div>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

        </div><!-- /st-panels -->
      </div><!-- /st-layout -->
    </form>
  </div>
</div>

<?= view('_nf_styles') ?>
<style>
/* ── Layout ── */
.st-layout{display:flex;gap:0;align-items:flex-start;min-height:60vh;}
.st-sidebar{width:220px;flex-shrink:0;position:sticky;top:20px;}
.st-panels{flex:1;min-width:0;padding-left:20px;}

/* ── Sidebar Nav ── */
.st-nav{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:12px;}
.st-nav-item{display:flex;align-items:center;gap:10px;padding:13px 16px;text-decoration:none;color:var(--t2);font-size:.855rem;font-weight:500;border-left:3px solid transparent;transition:all .15s;cursor:pointer;}
.st-nav-item:hover{background:#f8fafc;color:var(--t1);}
.st-nav-item.active{background:#eef2ff;color:var(--accent);border-left-color:var(--accent);font-weight:700;}
.st-nav-icon{width:20px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}
.st-nav-label{flex:1;}
.st-nav-arrow{font-size:.6rem;opacity:.4;}
.st-nav-item.active .st-nav-arrow{opacity:.7;}
.st-nav-item+.st-nav-item{border-top:1px solid var(--border);}
.st-sidebar-save{padding:0 2px;}

/* ── Panels ── */
.st-panel{display:none;}
.st-panel.active{display:block;}
.st-panel-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;}
.st-panel-title{font-size:1.15rem;font-weight:800;color:var(--t1);margin:0 0 3px;}
.st-panel-sub{font-size:.8rem;color:var(--t3);margin:0;}
.st-panel-footer{margin-top:24px;padding-top:16px;border-top:1px solid var(--border);}

/* ── Feature grid ── */
.nf-feat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:8px;}
.nf-feat-item{cursor:pointer;margin:0;}
.nf-feat-item input{display:none;}
.nf-feat-box{display:flex;align-items:center;gap:8px;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:.82rem;color:var(--t2);transition:all .15s;position:relative;}
.nf-feat-icon{font-size:.85rem;color:var(--t3);flex-shrink:0;}
.nf-feat-item input:checked+.nf-feat-box{border-color:var(--accent);background:#eef2ff;color:var(--accent);}
.nf-feat-item input:checked+.nf-feat-box .nf-feat-icon{color:var(--accent);}
.nf-feat-check{width:16px;height:16px;border:1.5px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:transparent;flex-shrink:0;transition:all .15s;margin-left:auto;}
.nf-feat-item input:checked+.nf-feat-box .nf-feat-check{background:var(--accent);border-color:var(--accent);color:#fff;}

/* ── Toggle list ── */
.st-toggle-list{display:flex;flex-direction:column;gap:0;}
.st-toggle-row{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:14px 0;border-bottom:1px solid var(--border);}
.st-toggle-row:last-child{border-bottom:none;padding-bottom:0;}
.st-toggle-label{font-size:.875rem;font-weight:600;color:var(--t1);margin:0 0 2px;}
.st-toggle-hint{font-size:.75rem;color:var(--t3);margin:0;}
.st-inline-select{width:90px;flex-shrink:0;padding:6px 8px;font-size:.82rem;}

/* ── Social fields ── */
.st-social-grid{display:flex;flex-direction:column;gap:16px;}
.st-social-field{display:flex;align-items:flex-end;gap:14px;}
.st-social-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;flex-shrink:0;margin-bottom:2px;}
.st-facebook{background:#1877f2;}.st-youtube{background:#ff0000;}.st-twitter{background:#1da1f2;}.st-instagram{background:radial-gradient(circle at 30% 107%,#fdf497 0%,#fd5949 45%,#d6249f 60%,#285aeb 90%);}

/* ── Provider blocks ── */
.st-provider-block{background:#f8fafc;border:1.5px solid var(--border);border-radius:10px;padding:16px;}
.st-provider-label{display:inline-flex;align-items:center;gap:6px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:4px 10px;border-radius:20px;margin-bottom:14px;}
.st-provider-twilio{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;}
.st-provider-termii{background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;}

/* ── API Key groups ── */
.st-key-grid{display:flex;flex-direction:column;gap:16px;}
.st-key-group{background:#f8fafc;border:1.5px solid var(--border);border-radius:10px;padding:16px;}
.st-key-brand{display:flex;align-items:center;gap:7px;font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;margin:0 0 12px;}
.st-key-paystack .st-key-brand{color:#0ba4db;}.st-key-flutter .st-key-brand{color:#f5a623;}.st-key-stripe .st-key-brand{color:#635bff;}.st-key-paypal .st-key-brand{color:#003087;}

/* ── Currency & BPG ── */
.mp-currency-option{display:flex;align-items:center;gap:7px;padding:9px 14px;border:2px solid var(--border);border-radius:10px;transition:all .15s;font-size:.875rem;}
.mp-currency-option:hover{border-color:#a5b4fc;background:#eef2ff;}
.mp-currency-active{border-color:var(--accent)!important;background:#eef2ff!important;}
.bpg-label{display:block;}.bpg-radio{position:absolute;opacity:0;width:0;height:0;}
.bpg-option{display:flex;flex-direction:column;align-items:center;gap:5px;padding:14px 20px;border:2px solid var(--border);border-radius:12px;transition:all .15s;min-width:120px;text-align:center;cursor:pointer;}
.bpg-option:hover{border-color:var(--bpg-color,var(--accent));background:#f8fafc;}
.bpg-active{border-color:var(--bpg-color,var(--accent))!important;background:#f0f9ff!important;box-shadow:0 0 0 3px rgba(99,102,241,.1);}

/* ── Misc ── */
.nf-setting-hint{font-size:.72rem;color:var(--t3);margin:2px 0 6px;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}.lt-bc span{color:var(--t3);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}

@media(max-width:768px){
  .st-layout{flex-direction:column;}
  .st-sidebar{width:100%;position:static;}
  .st-panels{padding-left:0;padding-top:16px;}
  .st-nav{display:flex;overflow-x:auto;border-radius:10px;}
  .st-nav-item{flex-direction:column;gap:4px;padding:10px 14px;white-space:nowrap;border-left:none;border-bottom:3px solid transparent;font-size:.75rem;}
  .st-nav-item.active{border-left:none;border-bottom-color:var(--accent);}
  .st-nav-arrow{display:none;}
}
</style>
<script>
(function(){
  var tabs = document.querySelectorAll('.st-nav-item');
  var panels = document.querySelectorAll('.st-panel');

  function activate(tabId) {
    tabs.forEach(function(t){ t.classList.toggle('active', t.dataset.tab === tabId); });
    panels.forEach(function(p){ p.classList.toggle('active', p.id === 'tab-' + tabId); });
    try { localStorage.setItem('st_active_tab', tabId); } catch(e){}
  }

  tabs.forEach(function(t){
    t.addEventListener('click', function(e){
      e.preventDefault();
      activate(t.dataset.tab);
    });
  });

  // restore from hash or localStorage
  var hash = (location.hash||'').replace('#','');
  var stored = '';
  try { stored = localStorage.getItem('st_active_tab')||''; } catch(e){}
  activate(hash || stored || 'features');

  // currency radio
  document.querySelectorAll('.mp-currency-radio').forEach(function(r){
    r.addEventListener('change', function(){
      document.querySelectorAll('.mp-currency-option').forEach(function(el){ el.classList.remove('mp-currency-active'); });
      this.nextElementSibling.classList.add('mp-currency-active');
    });
  });

  // book gateway radio
  document.querySelectorAll('.bpg-radio').forEach(function(r){
    r.addEventListener('change', function(){
      document.querySelectorAll('.bpg-option').forEach(function(el){ el.classList.remove('bpg-active'); });
      this.nextElementSibling.classList.add('bpg-active');
    });
  });
})();
</script>
