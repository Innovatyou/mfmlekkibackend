<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Website</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><span>Website</span></nav>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="<?= base_url('signupRequests') ?>" class="btn btn-outline-secondary">
          <i class="dw dw-user2" style="margin-right:6px;"></i>Signup Requests
          <?php if($pendingCount > 0): ?><span class="lc-nav-badge"><?= $pendingCount ?></span><?php endif; ?>
        </a>
        <a href="<?= base_url('contactMessages') ?>" class="btn btn-outline-secondary">
          <i class="dw dw-mail-1" style="margin-right:6px;"></i>Contact Messages
          <?php if($unreadMessages > 0): ?><span class="lc-nav-badge"><?= $unreadMessages ?></span><?php endif; ?>
        </a>
        <a href="http://localhost:3001" target="_blank" class="btn btn-outline-secondary">
          <i class="dw dw-browser" style="margin-right:6px;"></i>View Live Site
        </a>
      </div>
    </div>

    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>

    <form method="POST" action="<?= base_url('updateLandingContent') ?>" enctype="multipart/form-data" id="lc-form">
      <?= csrf_field() ?>

      <div class="st-layout">
        <aside class="st-sidebar">
          <nav class="st-nav">
            <a class="st-nav-item" data-tab="hero" href="#hero">
              <span class="st-nav-icon"><i class="dw dw-image"></i></span>
              <span class="st-nav-label">Hero</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="about" href="#about">
              <span class="st-nav-icon"><i class="dw dw-edit-3"></i></span>
              <span class="st-nav-label">About Us</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="sections" href="#sections">
              <span class="st-nav-icon"><i class="dw dw-grid-2"></i></span>
              <span class="st-nav-label">Sections</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="signup" href="#signup">
              <span class="st-nav-icon"><i class="dw dw-user1"></i></span>
              <span class="st-nav-label">Join Us Form</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="contact" href="#contact">
              <span class="st-nav-icon"><i class="dw dw-location"></i></span>
              <span class="st-nav-label">Contact</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="apps" href="#apps">
              <span class="st-nav-icon"><i class="dw dw-smartphone"></i></span>
              <span class="st-nav-label">Apps &amp; Login</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="seo" href="#seo">
              <span class="st-nav-icon"><i class="dw dw-search"></i></span>
              <span class="st-nav-label">SEO</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
            <a class="st-nav-item" data-tab="branding" href="#branding">
              <span class="st-nav-icon"><i class="dw dw-brush"></i></span>
              <span class="st-nav-label">Branding</span>
              <span class="st-nav-arrow"><i class="dw dw-next"></i></span>
            </a>
          </nav>
          <div class="st-sidebar-save">
            <button type="submit" class="btn btn-primary" style="width:100%;font-weight:600;border-radius:9px;padding:10px;">
              <i class="dw dw-check" style="margin-right:6px;"></i>Save Changes
            </button>
          </div>
        </aside>

        <div class="st-panels">

          <!-- ══ Hero ══ -->
          <div class="st-panel" id="tab-hero">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">Hero Section</h2><p class="st-panel-sub">The first thing visitors see at the top of your website</p></div>
              <label class="lc-toggle"><input type="checkbox" name="show_hero" value="1" <?= $content->show_hero ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Headline</label>
                  <input type="text" name="hero_title" class="nf-input" value="<?= esc($content->hero_title) ?>">
                </div>
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Subheadline</label>
                  <textarea name="hero_subtitle" class="nf-input" rows="2"><?= esc($content->hero_subtitle) ?></textarea>
                </div>
                <div class="nf-row" style="margin-bottom:16px;">
                  <div class="nf-col-half">
                    <label class="nf-label">Button Text</label>
                    <input type="text" name="hero_cta_text" class="nf-input" value="<?= esc($content->hero_cta_text) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">Button Link</label>
                    <input type="text" name="hero_cta_link" class="nf-input" value="<?= esc($content->hero_cta_link) ?>" placeholder="#service-times">
                  </div>
                </div>
                <div>
                  <label class="nf-label">Background Image</label>
                  <?php if(!empty($content->hero_image)):?>
                    <div style="margin-bottom:10px;"><img src="<?=esc($content->hero_image)?>" style="max-width:220px;border-radius:10px;"></div>
                  <?php endif;?>
                  <label class="nf-upload-zone" style="display:block;" onclick="document.getElementById('hero-image-input').click()">
                    <div class="nf-upload-icon"><i class="dw dw-upload"></i></div>
                    <p class="nf-upload-text">Click to upload / replace</p>
                    <p class="nf-upload-hint">JPG, PNG or WEBP — wide image recommended</p>
                  </label>
                  <input type="file" name="hero_image" id="hero-image-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;">
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ About ══ -->
          <div class="st-panel" id="tab-about">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">About Us</h2><p class="st-panel-sub">Tell visitors who you are and what you believe</p></div>
              <label class="lc-toggle"><input type="checkbox" name="show_about" value="1" <?= $content->show_about ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Section Title</label>
                  <input type="text" name="about_title" class="nf-input" value="<?= esc($content->about_title) ?>">
                </div>
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Content</label>
                  <textarea name="about_content" class="nf-input" rows="7"><?= esc($content->about_content) ?></textarea>
                </div>
                <div>
                  <label class="nf-label">Image</label>
                  <?php if(!empty($content->about_image)):?>
                    <div style="margin-bottom:10px;"><img src="<?=esc($content->about_image)?>" style="max-width:220px;border-radius:10px;"></div>
                  <?php endif;?>
                  <label class="nf-upload-zone" style="display:block;" onclick="document.getElementById('about-image-input').click()">
                    <div class="nf-upload-icon"><i class="dw dw-upload"></i></div>
                    <p class="nf-upload-text">Click to upload / replace</p>
                    <p class="nf-upload-hint">JPG, PNG or WEBP</p>
                  </label>
                  <input type="file" name="about_image" id="about-image-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;">
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Sections ══ -->
          <div class="st-panel" id="tab-sections">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">Page Sections</h2><p class="st-panel-sub">Toggle visibility and edit the heading for each section. Content itself is managed on its own page.</p></div>
            </div>

            <?php
            $sections = [
              ['key' => 'service_times', 'label' => 'Service Times', 'manage' => 'serviceTimesListing', 'manageLabel' => 'Manage Service Times'],
              ['key' => 'events',        'label' => 'Upcoming Events', 'manage' => 'eventsListing', 'manageLabel' => 'Manage Events'],
              ['key' => 'sermons',       'label' => 'Latest Sermons', 'manage' => 'videos', 'manageLabel' => 'Manage Videos & Audio'],
              ['key' => 'live',          'label' => 'Join Us Live', 'manage' => 'livestreams', 'manageLabel' => 'Manage Livestream'],
              ['key' => 'gallery',       'label' => 'Gallery', 'manage' => 'photos', 'manageLabel' => 'Manage Photos'],
              ['key' => 'leadership',    'label' => 'Leadership', 'manage' => 'leadershipListing', 'manageLabel' => 'Manage Leadership'],
              ['key' => 'contact',       'label' => 'Contact', 'manage' => null, 'manageLabel' => null],
            ];
            foreach ($sections as $s):
              $showField = 'show_' . $s['key'];
              $titleField = $s['key'] . '_title';
              $subField = $s['key'] . '_subtitle';
            ?>
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                  <h4 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0;"><?= esc($s['label']) ?></h4>
                  <div style="display:flex;align-items:center;gap:12px;">
                    <?php if($s['manage']):?><a href="<?= base_url($s['manage']) ?>" class="lc-manage-link"><?= esc($s['manageLabel']) ?></a><?php endif;?>
                    <label class="lc-toggle"><input type="checkbox" name="<?=$showField?>" value="1" <?= $content->$showField ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
                  </div>
                </div>
                <?php if(isset($content->$titleField)):?>
                <div class="nf-row">
                  <div class="nf-col-half">
                    <label class="nf-label">Title</label>
                    <input type="text" name="<?=$titleField?>" class="nf-input" value="<?= esc($content->$titleField) ?>">
                  </div>
                  <?php if(isset($content->$subField)):?>
                  <div class="nf-col-half">
                    <label class="nf-label">Subtitle</label>
                    <input type="text" name="<?=$subField?>" class="nf-input" value="<?= esc($content->$subField) ?>">
                  </div>
                  <?php endif;?>
                </div>
                <?php endif;?>
                <?php if($s['key'] === 'live'):?>
                <div style="margin-top:16px;">
                  <label class="nf-label">Message When Not Live</label>
                  <input type="text" name="live_offline_message" class="nf-input" value="<?= esc($content->live_offline_message) ?>">
                  <p class="nf-setting-hint">Shown in place of the video whenever no stream is marked "Live" on the Livestream page.</p>
                </div>
                <?php endif;?>
              </div>
            </div>
            <?php endforeach; ?>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Join Us Form ══ -->
          <div class="st-panel" id="tab-signup">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">"Join Us" Signup Form</h2><p class="st-panel-sub">The public membership form. Manage its questions on the <a href="<?= base_url('membershipFormListing') ?>">Membership Form</a> page. New submissions appear under <a href="<?= base_url('signupRequests') ?>">Signup Requests</a> for your review.</p></div>
              <label class="lc-toggle"><input type="checkbox" name="show_signup" value="1" <?= $content->show_signup ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div class="nf-row">
                  <div class="nf-col-half">
                    <label class="nf-label">Title</label>
                    <input type="text" name="signup_title" class="nf-input" value="<?= esc($content->signup_title) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">Subtitle</label>
                    <input type="text" name="signup_subtitle" class="nf-input" value="<?= esc($content->signup_subtitle) ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Contact ══ -->
          <div class="st-panel" id="tab-contact">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">Contact Section</h2><p class="st-panel-sub">Address, phone, email and map shown at the bottom of the page</p></div>
              <label class="lc-toggle"><input type="checkbox" name="show_contact" value="1" <?= $content->show_contact ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Section Title</label>
                  <input type="text" name="contact_title" class="nf-input" value="<?= esc($content->contact_title) ?>">
                </div>
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Address</label>
                  <input type="text" name="contact_address" class="nf-input" value="<?= esc($content->contact_address) ?>">
                </div>
                <div class="nf-row" style="margin-bottom:16px;">
                  <div class="nf-col-half">
                    <label class="nf-label">Phone</label>
                    <input type="text" name="contact_phone" class="nf-input" value="<?= esc($content->contact_phone) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">Email</label>
                    <input type="email" name="contact_email" class="nf-input" value="<?= esc($content->contact_email) ?>">
                  </div>
                </div>
                <div>
                  <label class="nf-label">Map Embed (optional)</label>
                  <textarea name="contact_map_embed" class="nf-input" rows="3" placeholder="Paste a Google Maps <iframe> embed code"><?= esc($content->contact_map_embed) ?></textarea>
                  <p class="nf-setting-hint">From Google Maps: Share → Embed a map → copy the &lt;iframe&gt; code</p>
                </div>
              </div>
            </div>

            <div class="nf-card" style="margin-top:16px;">
              <div class="nf-card-head">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <div>
                    <h3 class="nf-card-title">Contact Form</h3>
                    <p class="nf-card-sub">Submissions land in <a href="<?= base_url('contactMessages') ?>">Contact Messages</a>, and you'll get an email notification</p>
                  </div>
                  <label class="lc-toggle"><input type="checkbox" name="show_contact_form" value="1" <?= $content->show_contact_form ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
                </div>
              </div>
              <div class="nf-card-body">
                <div class="nf-row" style="margin-bottom:16px;">
                  <div class="nf-col-half">
                    <label class="nf-label">Form Title</label>
                    <input type="text" name="contact_form_title" class="nf-input" value="<?= esc($content->contact_form_title) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">Form Subtitle</label>
                    <input type="text" name="contact_form_subtitle" class="nf-input" value="<?= esc($content->contact_form_subtitle) ?>">
                  </div>
                </div>
                <div>
                  <label class="nf-label">Notify This Email On New Messages</label>
                  <input type="email" name="contact_notification_email" class="nf-input" value="<?= esc($content->contact_notification_email) ?>" placeholder="Leave blank to use your church's registered email">
                </div>
              </div>
            </div>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Apps & Login ══ -->
          <div class="st-panel" id="tab-apps">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">Apps &amp; Web Login</h2><p class="st-panel-sub">Where visitors go to log in to your member web app, and links to download the mobile apps</p></div>
            </div>
            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Member Web App Login</h3></div>
              <div class="nf-card-body">
                <div class="nf-row" style="margin-bottom:16px;">
                  <div class="nf-col-two-thirds">
                    <label class="nf-label">Web App URL</label>
                    <input type="url" name="web_app_url" class="nf-input" value="<?= esc($content->web_app_url) ?>" placeholder="https://app.yourchurch.org">
                    <p class="nf-setting-hint">Shown as a "<?= esc($content->web_app_login_text ?: 'Member Login') ?>" button on the website. Leave blank to hide it.</p>
                  </div>
                  <div class="nf-col-third">
                    <label class="nf-label">Button Text</label>
                    <input type="text" name="web_app_login_text" class="nf-input" value="<?= esc($content->web_app_login_text) ?>" placeholder="Member Login">
                  </div>
                </div>
              </div>
            </div>

            <div class="nf-card">
              <div class="nf-card-head">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <h3 class="nf-card-title">Mobile App Download Section</h3>
                  <label class="lc-toggle"><input type="checkbox" name="show_app_download" value="1" <?= $content->show_app_download ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
                </div>
              </div>
              <div class="nf-card-body">
                <div class="nf-row" style="margin-bottom:16px;">
                  <div class="nf-col-half">
                    <label class="nf-label">Section Title</label>
                    <input type="text" name="app_download_title" class="nf-input" value="<?= esc($content->app_download_title) ?>">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">Section Subtitle</label>
                    <input type="text" name="app_download_subtitle" class="nf-input" value="<?= esc($content->app_download_subtitle) ?>">
                  </div>
                </div>
                <div class="nf-row">
                  <div class="nf-col-half">
                    <label class="nf-label">Android (Google Play) URL</label>
                    <input type="url" name="android_app_url" class="nf-input" value="<?= esc($content->android_app_url) ?>" placeholder="https://play.google.com/store/apps/details?id=...">
                  </div>
                  <div class="nf-col-half">
                    <label class="nf-label">iOS (App Store) URL</label>
                    <input type="url" name="ios_app_url" class="nf-input" value="<?= esc($content->ios_app_url) ?>" placeholder="https://apps.apple.com/app/...">
                  </div>
                </div>
                <p class="nf-setting-hint">Leave either blank to hide just that badge — the section itself only shows once at least one is filled in.</p>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ SEO ══ -->
          <div class="st-panel" id="tab-seo">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">SEO &amp; Sharing</h2><p class="st-panel-sub">How your website appears in Google search results and when shared on social media</p></div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Search Engine Listing</h3></div>
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Meta Title</label>
                  <input type="text" name="seo_meta_title" class="nf-input" maxlength="70" value="<?= esc($content->seo_meta_title) ?>" placeholder="e.g. Grace Community Church | Home">
                  <p class="nf-setting-hint">Shown as the blue link text in Google results and the browser tab. Leave blank to use your church name.</p>
                </div>
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Meta Description</label>
                  <textarea name="seo_meta_description" class="nf-input" rows="3" maxlength="300" placeholder="A short, compelling summary of your church — shown under the title in search results."><?= esc($content->seo_meta_description) ?></textarea>
                  <p class="nf-setting-hint">Aim for under 160 characters. Leave blank to use the hero subtitle.</p>
                </div>
                <div>
                  <label class="nf-label">Meta Keywords (optional)</label>
                  <input type="text" name="seo_meta_keywords" class="nf-input" value="<?= esc($content->seo_meta_keywords) ?>" placeholder="church, worship, community, your city">
                  <p class="nf-setting-hint">Comma-separated. Most search engines ignore this today, but it doesn't hurt to set it.</p>
                </div>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Social Sharing Preview</h3></div>
              <div class="nf-card-body">
                <label class="nf-label">Preview Image</label>
                <?php if(!empty($content->seo_og_image)):?>
                  <div style="margin-bottom:10px;"><img src="<?=esc($content->seo_og_image)?>" style="max-width:280px;border-radius:10px;"></div>
                <?php endif;?>
                <label class="nf-upload-zone" style="display:block;margin-bottom:16px;" onclick="document.getElementById('og-image-input').click()">
                  <div class="nf-upload-icon"><i class="dw dw-upload"></i></div>
                  <p class="nf-upload-text">Click to upload / replace</p>
                  <p class="nf-upload-hint">Shown when your site is shared on WhatsApp, Facebook, X, etc. — 1200×630px recommended</p>
                </label>
                <input type="file" name="seo_og_image" id="og-image-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;">
                <div>
                  <label class="nf-label">X (Twitter) Handle (optional)</label>
                  <input type="text" name="seo_twitter_handle" class="nf-input" value="<?= esc($content->seo_twitter_handle) ?>" placeholder="@yourchurch">
                </div>
              </div>
            </div>

            <div class="nf-card" style="margin-bottom:16px;">
              <div class="nf-card-head"><h3 class="nf-card-title">Verification &amp; Analytics</h3></div>
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Google Site Verification Code (optional)</label>
                  <input type="text" name="seo_google_site_verification" class="nf-input" value="<?= esc($content->seo_google_site_verification) ?>" placeholder="Paste just the content value from Google Search Console">
                  <p class="nf-setting-hint">Search Console → Settings → Ownership verification → HTML tag → copy only the "content" value.</p>
                </div>
                <div>
                  <label class="nf-label">Google Analytics Measurement ID (optional)</label>
                  <input type="text" name="seo_google_analytics_id" class="nf-input" value="<?= esc($content->seo_google_analytics_id) ?>" placeholder="G-XXXXXXXXXX">
                </div>
              </div>
            </div>

            <div class="nf-card">
              <div class="nf-card-head">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <div>
                    <h3 class="nf-card-title">Allow Search Engines To Index This Site</h3>
                    <p class="nf-card-sub">Turn off while building or testing so the site doesn't appear in search results yet</p>
                  </div>
                  <label class="lc-toggle"><input type="checkbox" name="seo_robots_index" value="1" <?= $content->seo_robots_index ? 'checked' : '' ?>><span class="lc-toggle-track"><span class="lc-toggle-thumb"></span></span></label>
                </div>
              </div>
            </div>

            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

          <!-- ══ Branding ══ -->
          <div class="st-panel" id="tab-branding">
            <div class="st-panel-head">
              <div><h2 class="st-panel-title">Branding &amp; Footer</h2><p class="st-panel-sub">Colours and footer text for the public website</p></div>
            </div>
            <div class="nf-card">
              <div class="nf-card-body">
                <div style="margin-bottom:16px;">
                  <label class="nf-label">Primary Colour</label>
                  <div style="display:flex;align-items:center;gap:10px;">
                    <input type="color" name="primary_color" value="<?= esc($content->primary_color ?: '#4f46e5') ?>" style="width:44px;height:38px;border:1.5px solid var(--border);border-radius:8px;padding:2px;">
                    <span class="nf-setting-hint" style="margin:0;">Used for buttons, links and highlights on the public site</span>
                  </div>
                </div>
                <div>
                  <label class="nf-label">Footer Text</label>
                  <input type="text" name="footer_text" class="nf-input" value="<?= esc($content->footer_text) ?>" placeholder="&copy; 2026 Your Church. All rights reserved.">
                </div>
              </div>
            </div>
            <div class="st-panel-footer">
              <button type="submit" class="btn btn-primary nf-submit"><i class="dw dw-check" style="margin-right:6px;"></i>Save Changes</button>
            </div>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

<?= view('_nf_styles') ?>
<style>
.st-layout{display:flex;gap:0;align-items:flex-start;min-height:60vh;}
.st-sidebar{width:220px;flex-shrink:0;position:sticky;top:20px;}
.st-panels{flex:1;min-width:0;padding-left:20px;}
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
.st-panel{display:none;}
.st-panel.active{display:block;}
.st-panel-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:16px;}
.st-panel-title{font-size:1.15rem;font-weight:800;color:var(--t1);margin:0 0 3px;}
.st-panel-sub{font-size:.8rem;color:var(--t3);margin:0;}
.st-panel-sub a{color:var(--accent);}
.st-panel-footer{margin-top:24px;padding-top:16px;border-top:1px solid var(--border);}
.lc-nav-badge{display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:10px;background:#f59e0b;color:#fff;font-size:.68rem;font-weight:700;margin-left:6px;}
.lc-manage-link{font-size:.78rem;font-weight:600;color:var(--accent);text-decoration:none;}
.lc-manage-link:hover{text-decoration:underline;}
.lc-toggle{position:relative;display:inline-block;width:42px;height:24px;flex-shrink:0;cursor:pointer;}
.lc-toggle input{opacity:0;width:0;height:0;position:absolute;}
.lc-toggle-track{position:absolute;inset:0;background:#e2e8f0;border-radius:24px;transition:.15s;}
.lc-toggle-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;transition:.15s;box-shadow:0 1px 2px rgba(0,0,0,.2);}
.lc-toggle input:checked+.lc-toggle-track{background:var(--accent);}
.lc-toggle input:checked+.lc-toggle-track .lc-toggle-thumb{transform:translateX(18px);}
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
    try { localStorage.setItem('lc_active_tab', tabId); } catch(e){}
  }

  tabs.forEach(function(t){
    t.addEventListener('click', function(e){
      e.preventDefault();
      activate(t.dataset.tab);
    });
  });

  var hash = (location.hash||'').replace('#','');
  var stored = '';
  try { stored = localStorage.getItem('lc_active_tab')||''; } catch(e){}
  activate(hash || stored || 'hero');
})();
</script>
