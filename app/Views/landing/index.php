<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($church->fullname ?? ($settings->churchname ?? 'Welcome')) ?></title>
  <meta name="description" content="<?= esc($content->hero_subtitle ?? '') ?>">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <?php
    $primary = !empty($content->primary_color) ? $content->primary_color : '#4f46e5';
    $churchName = $church->fullname ?? ($settings->churchname ?? 'Our Church');
    $logo = !empty($church->logo) ? $church->logo : '';
  ?>
  <style>
    :root{
      --primary: <?= esc($primary, 'css') ?>;
      --ink: #1e1b2e;
      --muted: #6b7280;
      --paper: #ffffff;
      --wash: #f7f7fb;
      --line: #eceaf3;
    }
    *{box-sizing:border-box;}
    body{font-family:'Inter',system-ui,sans-serif;color:var(--ink);background:var(--paper);margin:0;}
    h1,h2,h3,h4,.brand-name{font-family:'Poppins',sans-serif;}
    a{text-decoration:none;}
    .btn-brand{background:var(--primary);border-color:var(--primary);color:#fff;font-weight:600;padding:.65rem 1.6rem;border-radius:8px;transition:transform .15s,box-shadow .15s;}
    .btn-brand:hover{opacity:.92;color:#fff;transform:translateY(-1px);box-shadow:0 8px 20px -8px var(--primary);}
    .btn-brand-outline{border:1.5px solid #fff;color:#fff;font-weight:600;padding:.6rem 1.5rem;border-radius:8px;}
    .btn-brand-outline:hover{background:#fff;color:var(--ink);}
    .text-brand{color:var(--primary);}
    .section{padding:88px 0;}
    .section-alt{background:var(--wash);}
    .eyebrow{display:inline-block;font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--primary);margin-bottom:10px;}
    .section-title{font-size:2rem;font-weight:800;margin-bottom:10px;}
    .section-sub{color:var(--muted);font-size:1.02rem;max-width:640px;}
    .section-head{margin-bottom:44px;}

    /* Navbar */
    .navbar-site{padding:16px 0;background:rgba(255,255,255,.96);backdrop-filter:blur(8px);border-bottom:1px solid var(--line);position:sticky;top:0;z-index:1030;}
    .brand-name{font-weight:800;font-size:1.2rem;color:var(--ink);display:flex;align-items:center;gap:10px;}
    .brand-logo-img{height:36px;width:36px;object-fit:cover;border-radius:8px;}
    .brand-logo-fallback{height:36px;width:36px;border-radius:8px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;}
    .nav-link-site{color:var(--ink);font-weight:500;font-size:.92rem;padding:.5rem 1rem!important;}
    .nav-link-site:hover{color:var(--primary);}

    /* Hero */
    .hero{position:relative;background:linear-gradient(160deg,var(--ink),#332e4d);color:#fff;overflow:hidden;}
    .hero::after{content:"";position:absolute;inset:0;background:radial-gradient(circle at 80% 10%, color-mix(in srgb, var(--primary) 35%, transparent), transparent 55%);}
    .hero-bg-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.28;}
    .hero-inner{position:relative;z-index:2;padding:120px 0 100px;}
    .hero h1{font-size:3rem;font-weight:800;line-height:1.12;margin-bottom:18px;}
    .hero p.lead{font-size:1.15rem;color:rgba(255,255,255,.82);max-width:560px;margin-bottom:32px;}

    /* Cards */
    .service-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px 26px;height:100%;transition:transform .15s,box-shadow .15s;}
    .service-card:hover{transform:translateY(-4px);box-shadow:0 20px 40px -24px rgba(30,27,46,.25);}
    .service-icon{width:46px;height:46px;border-radius:12px;background:color-mix(in srgb, var(--primary) 12%, white);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:16px;}

    .event-card{border-radius:16px;overflow:hidden;border:1px solid var(--line);background:#fff;height:100%;}
    .event-thumb{height:170px;width:100%;object-fit:cover;background:var(--wash);}
    .event-date-badge{background:var(--primary);color:#fff;font-weight:700;font-size:.78rem;padding:4px 12px;border-radius:20px;display:inline-block;margin-bottom:10px;}

    .sermon-card{border-radius:16px;overflow:hidden;border:1px solid var(--line);background:#fff;height:100%;}
    .sermon-thumb-wrap{position:relative;height:170px;background:var(--ink);}
    .sermon-thumb{width:100%;height:100%;object-fit:cover;opacity:.85;}
    .sermon-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;}
    .sermon-play i{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.92);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.3rem;}

    .gallery-item{border-radius:14px;overflow:hidden;aspect-ratio:1/1;}
    .gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform .3s;}
    .gallery-item:hover img{transform:scale(1.06);}

    .leader-card{text-align:center;}
    .leader-photo{width:150px;height:150px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:4px solid var(--wash);}
    .leader-photo-fallback{width:150px;height:150px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,var(--primary),#8b5cf6);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:800;}

    .join-panel{background:linear-gradient(160deg,var(--ink),#332e4d);border-radius:24px;color:#fff;padding:56px 48px;}
    .form-control-brand{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);color:#fff;padding:.7rem .9rem;border-radius:8px;}
    .form-control-brand::placeholder{color:rgba(255,255,255,.5);}
    .form-control-brand:focus{background:rgba(255,255,255,.12);border-color:var(--primary);color:#fff;box-shadow:0 0 0 3px color-mix(in srgb, var(--primary) 25%, transparent);}
    .form-label-brand{font-size:.82rem;font-weight:600;color:rgba(255,255,255,.75);margin-bottom:6px;}

    .contact-item{display:flex;gap:14px;align-items:flex-start;margin-bottom:20px;}
    .contact-icon{width:42px;height:42px;border-radius:10px;background:color-mix(in srgb, var(--primary) 12%, white);color:var(--primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;}

    .site-footer{background:var(--ink);color:rgba(255,255,255,.65);padding:48px 0 24px;}
    .footer-social a{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.08);color:#fff;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;transition:.15s;}
    .footer-social a:hover{background:var(--primary);}

    @media(max-width:767px){
      .hero h1{font-size:2.1rem;}
      .section{padding:56px 0;}
      .join-panel{padding:32px 22px;}
    }
  </style>
</head>
<body>

  <!-- ── Navbar ── -->
  <nav class="navbar navbar-expand-lg navbar-site">
    <div class="container">
      <a class="navbar-brand brand-name" href="#top">
        <?php if($logo):?>
          <img src="<?= esc($logo) ?>" class="brand-logo-img" alt="<?= esc($churchName) ?>">
        <?php else:?>
          <span class="brand-logo-fallback"><?= esc(strtoupper(substr($churchName,0,1))) ?></span>
        <?php endif;?>
        <span><?= esc($churchName) ?></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="siteNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <?php if(!empty($content->show_about)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#about">About</a></li><?php endif;?>
          <?php if(!empty($content->show_service_times)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#service-times">Services</a></li><?php endif;?>
          <?php if(!empty($content->show_events)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#events">Events</a></li><?php endif;?>
          <?php if(!empty($content->show_sermons)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#sermons">Sermons</a></li><?php endif;?>
          <?php if(!empty($content->show_leadership)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#leadership">Leadership</a></li><?php endif;?>
          <?php if(!empty($content->show_contact)):?><li class="nav-item"><a class="nav-link nav-link-site" href="#contact">Contact</a></li><?php endif;?>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <?php if(!empty($content->show_signup)):?>
              <a class="btn btn-brand btn-sm me-2" href="#join-us">Join Us</a>
            <?php endif;?>
            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right me-1"></i>Admin Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <a id="top"></a>

  <!-- ── Hero ── -->
  <?php if(!empty($content->show_hero)):?>
  <section class="hero">
    <?php if(!empty($content->hero_image)):?><img src="<?= esc($content->hero_image) ?>" class="hero-bg-img" alt=""><?php endif;?>
    <div class="container hero-inner">
      <div class="row">
        <div class="col-lg-8">
          <h1><?= esc($content->hero_title) ?></h1>
          <p class="lead"><?= esc($content->hero_subtitle) ?></p>
          <div class="d-flex flex-wrap gap-3">
            <a href="<?= esc($content->hero_cta_link ?: '#service-times') ?>" class="btn btn-brand">
              <?= esc($content->hero_cta_text ?: 'Join Us This Sunday') ?>
            </a>
            <?php if(!empty($content->show_signup)):?>
              <a href="#join-us" class="btn btn-brand-outline">Become a Member</a>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif;?>

  <!-- ── About ── -->
  <?php if(!empty($content->show_about)):?>
  <section class="section" id="about">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 order-lg-2">
          <span class="eyebrow">Who We Are</span>
          <h2 class="section-title"><?= esc($content->about_title) ?></h2>
          <div style="color:var(--muted);font-size:1.02rem;white-space:pre-line;line-height:1.75;">
            <?= esc($content->about_content ?: '') ?>
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <?php if(!empty($content->about_image)):?>
            <img src="<?= esc($content->about_image) ?>" alt="<?= esc($churchName) ?>" style="width:100%;border-radius:20px;object-fit:cover;max-height:420px;">
          <?php else:?>
            <div style="width:100%;height:340px;border-radius:20px;background:linear-gradient(135deg,var(--primary),#8b5cf6);"></div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Service Times ── -->
  <?php if(!empty($content->show_service_times)):?>
  <section class="section section-alt" id="service-times">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">Worship With Us</span>
        <h2 class="section-title"><?= esc($content->service_times_title) ?></h2>
        <p class="section-sub mx-auto"><?= esc($content->service_times_subtitle) ?></p>
      </div>
      <?php if(!empty($serviceTimes)):?>
      <div class="row g-4">
        <?php foreach($serviceTimes as $s):?>
        <div class="col-md-6 col-lg-4">
          <div class="service-card">
            <div class="service-icon"><i class="bi bi-clock"></i></div>
            <h4 style="font-size:1.05rem;font-weight:700;"><?= esc($s->name) ?></h4>
            <p style="color:var(--primary);font-weight:600;margin-bottom:4px;"><?= esc($s->day_of_week) ?> &middot; <?= esc($s->time_label) ?></p>
            <?php if(!empty($s->location)):?><p style="color:var(--muted);font-size:.88rem;margin-bottom:4px;"><i class="bi bi-geo-alt me-1"></i><?= esc($s->location) ?></p><?php endif;?>
            <?php if(!empty($s->description)):?><p style="color:var(--muted);font-size:.88rem;margin:0;"><?= esc($s->description) ?></p><?php endif;?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php else:?>
        <p class="text-center" style="color:var(--muted);">Service times will be posted here soon.</p>
      <?php endif;?>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Events ── -->
  <?php if(!empty($content->show_events)):?>
  <section class="section" id="events">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">What's Happening</span>
        <h2 class="section-title"><?= esc($content->events_title) ?></h2>
        <p class="section-sub mx-auto"><?= esc($content->events_subtitle) ?></p>
      </div>
      <?php if(!empty($events)):?>
      <div class="row g-4">
        <?php foreach($events as $e):?>
        <div class="col-md-6 col-lg-4">
          <div class="event-card">
            <img src="<?= esc($e->thumbnail) ?>" class="event-thumb" alt="<?= esc($e->title ?? '') ?>" onerror="this.style.display='none'">
            <div class="p-4">
              <span class="event-date-badge"><?= esc(date('M j, Y', strtotime($e->date))) ?></span>
              <h4 style="font-size:1.05rem;font-weight:700;"><?= esc($e->title ?? '') ?></h4>
              <p style="color:var(--muted);font-size:.88rem;margin:0;"><?= esc(mb_strimwidth(strip_tags($e->description ?? ''), 0, 110, '…')) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php else:?>
        <p class="text-center" style="color:var(--muted);">No upcoming events at the moment — check back soon!</p>
      <?php endif;?>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Sermons ── -->
  <?php if(!empty($content->show_sermons)):?>
  <section class="section section-alt" id="sermons">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">Recent Messages</span>
        <h2 class="section-title"><?= esc($content->sermons_title) ?></h2>
        <p class="section-sub mx-auto"><?= esc($content->sermons_subtitle) ?></p>
      </div>
      <?php if(!empty($sermons)):?>
      <div class="row g-4">
        <?php foreach($sermons as $m):?>
        <div class="col-md-6 col-lg-4">
          <div class="sermon-card">
            <div class="sermon-thumb-wrap">
              <?php if(!empty($m->cover_photo)):?><img src="<?= esc($m->cover_photo) ?>" class="sermon-thumb" alt="" onerror="this.style.display='none'"><?php endif;?>
              <div class="sermon-play"><i class="bi <?= $m->type=='video' ? 'bi-play-fill' : 'bi-volume-up-fill' ?>"></i></div>
            </div>
            <div class="p-3">
              <h4 style="font-size:1rem;font-weight:700;margin-bottom:4px;"><?= esc($m->title) ?></h4>
              <p style="color:var(--muted);font-size:.8rem;margin:0;"><?= esc($m->category ?? '') ?></p>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php else:?>
        <p class="text-center" style="color:var(--muted);">Sermons will appear here once uploaded from the admin dashboard.</p>
      <?php endif;?>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Gallery ── -->
  <?php if(!empty($content->show_gallery)):?>
  <section class="section" id="gallery">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">Church Life</span>
        <h2 class="section-title"><?= esc($content->gallery_title) ?></h2>
        <p class="section-sub mx-auto"><?= esc($content->gallery_subtitle) ?></p>
      </div>
      <?php if(!empty($gallery)):?>
      <div class="row g-3">
        <?php foreach($gallery as $g):?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="gallery-item"><img src="<?= esc($g['image']) ?>" alt="<?= esc($g['title']) ?>" loading="lazy"></div>
        </div>
        <?php endforeach;?>
      </div>
      <?php else:?>
        <p class="text-center" style="color:var(--muted);">Photos from church life will appear here.</p>
      <?php endif;?>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Leadership ── -->
  <?php if(!empty($content->show_leadership)):?>
  <section class="section section-alt" id="leadership">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">Meet The Team</span>
        <h2 class="section-title"><?= esc($content->leadership_title) ?></h2>
        <p class="section-sub mx-auto"><?= esc($content->leadership_subtitle) ?></p>
      </div>
      <?php if(!empty($leadership)):?>
      <div class="row g-4 justify-content-center">
        <?php foreach($leadership as $l):?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="leader-card">
            <?php if(!empty($l->photo)):?>
              <img src="<?= esc($l->photo) ?>" class="leader-photo" alt="<?= esc($l->name) ?>">
            <?php else:?>
              <div class="leader-photo-fallback"><?= esc(strtoupper(substr($l->name,0,1))) ?></div>
            <?php endif;?>
            <h4 style="font-size:1rem;font-weight:700;margin-bottom:2px;"><?= esc($l->name) ?></h4>
            <p class="text-brand" style="font-size:.85rem;font-weight:600;margin-bottom:6px;"><?= esc($l->role_title) ?></p>
            <?php if(!empty($l->bio)):?><p style="color:var(--muted);font-size:.82rem;"><?= esc(mb_strimwidth($l->bio,0,100,'…')) ?></p><?php endif;?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php else:?>
        <p class="text-center" style="color:var(--muted);">Leadership profiles coming soon.</p>
      <?php endif;?>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Join Us ── -->
  <?php if(!empty($content->show_signup)):?>
  <section class="section" id="join-us">
    <div class="container">
      <div class="join-panel">
        <div class="row align-items-center g-5">
          <div class="col-lg-5">
            <span class="eyebrow" style="color:#fff;opacity:.8;">Become Part Of Our Family</span>
            <h2 style="font-size:1.9rem;font-weight:800;margin-bottom:14px;"><?= esc($content->signup_title) ?></h2>
            <p style="color:rgba(255,255,255,.75);"><?= esc($content->signup_subtitle) ?></p>
          </div>
          <div class="col-lg-7">
            <?php if(!empty($success)):?>
              <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= esc($success) ?></div>
            <?php endif;?>
            <?php if(!empty($error)):?>
              <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= esc($error) ?></div>
            <?php endif;?>
            <form method="POST" action="<?= base_url('joinUs') ?>">
              <div class="row g-3">
                <div class="col-sm-6">
                  <label class="form-label-brand">First Name</label>
                  <input type="text" name="firstname" class="form-control form-control-brand" value="<?= esc($old['firstname'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label-brand">Last Name</label>
                  <input type="text" name="lastname" class="form-control form-control-brand" value="<?= esc($old['lastname'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label-brand">Email</label>
                  <input type="email" name="email" class="form-control form-control-brand" value="<?= esc($old['email'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label-brand">Phone Number</label>
                  <input type="tel" name="phonenumber" class="form-control form-control-brand" value="<?= esc($old['phonenumber'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label-brand">Gender</label>
                  <select name="gender" class="form-control form-control-brand" required>
                    <option value="" disabled selected>Select…</option>
                    <option value="Male" <?= (($old['gender'] ?? '')=='Male')?'selected':'' ?>>Male</option>
                    <option value="Female" <?= (($old['gender'] ?? '')=='Female')?'selected':'' ?>>Female</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="form-label-brand">Date of Birth</label>
                  <input type="date" name="dob" class="form-control form-control-brand" value="<?= esc($old['dob'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                  <label class="form-label-brand">Address (optional)</label>
                  <input type="text" name="address" class="form-control form-control-brand" value="<?= esc($old['address'] ?? '') ?>">
                </div>
                <div class="col-12 pt-2">
                  <button type="submit" class="btn btn-brand w-100">Submit Membership Request</button>
                  <p style="color:rgba(255,255,255,.55);font-size:.78rem;margin:10px 0 0;">We ask for your birthday so we can celebrate it with you 🎉. A church admin will review your request shortly.</p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Contact ── -->
  <?php if(!empty($content->show_contact)):?>
  <section class="section section-alt" id="contact">
    <div class="container">
      <div class="section-head text-center mx-auto">
        <span class="eyebrow">We'd Love To Hear From You</span>
        <h2 class="section-title"><?= esc($content->contact_title) ?></h2>
      </div>
      <div class="row g-5">
        <div class="col-lg-5">
          <?php if(!empty($content->contact_address)):?>
          <div class="contact-item">
            <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
            <div><h5 style="font-size:.95rem;font-weight:700;margin-bottom:2px;">Address</h5><p style="color:var(--muted);margin:0;"><?= esc($content->contact_address) ?></p></div>
          </div>
          <?php endif;?>
          <?php if(!empty($content->contact_phone)):?>
          <div class="contact-item">
            <div class="contact-icon"><i class="bi bi-telephone"></i></div>
            <div><h5 style="font-size:.95rem;font-weight:700;margin-bottom:2px;">Phone</h5><p style="color:var(--muted);margin:0;"><?= esc($content->contact_phone) ?></p></div>
          </div>
          <?php endif;?>
          <?php if(!empty($content->contact_email)):?>
          <div class="contact-item">
            <div class="contact-icon"><i class="bi bi-envelope"></i></div>
            <div><h5 style="font-size:.95rem;font-weight:700;margin-bottom:2px;">Email</h5><p style="color:var(--muted);margin:0;"><?= esc($content->contact_email) ?></p></div>
          </div>
          <?php endif;?>
          <?php if(!empty($branches)):?>
            <?php foreach($branches as $b): if($b->id==1) continue; ?>
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-building"></i></div>
              <div><h5 style="font-size:.95rem;font-weight:700;margin-bottom:2px;"><?= esc($b->name) ?></h5><p style="color:var(--muted);margin:0;"><?= esc($b->address) ?></p></div>
            </div>
            <?php endforeach;?>
          <?php endif;?>
        </div>
        <div class="col-lg-7">
          <?php if(!empty($content->contact_map_embed)):?>
            <div style="border-radius:16px;overflow:hidden;line-height:0;"><?= $content->contact_map_embed ?></div>
          <?php else:?>
            <div style="width:100%;height:280px;border-radius:16px;background:var(--wash);display:flex;align-items:center;justify-content:center;color:var(--muted);">
              <i class="bi bi-map me-2"></i>Map coming soon
            </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </section>
  <?php endif;?>

  <!-- ── Footer ── -->
  <footer class="site-footer">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="brand-name" style="color:#fff;">
          <?php if($logo):?><img src="<?= esc($logo) ?>" class="brand-logo-img" alt=""><?php else:?><span class="brand-logo-fallback"><?= esc(strtoupper(substr($churchName,0,1))) ?></span><?php endif;?>
          <span><?= esc($churchName) ?></span>
        </div>
        <div class="footer-social">
          <?php if(!empty($settings->facebook)):?><a href="<?= esc($settings->facebook) ?>" target="_blank"><i class="bi bi-facebook"></i></a><?php endif;?>
          <?php if(!empty($settings->twitter)):?><a href="<?= esc($settings->twitter) ?>" target="_blank"><i class="bi bi-twitter-x"></i></a><?php endif;?>
          <?php if(!empty($settings->instagram)):?><a href="<?= esc($settings->instagram) ?>" target="_blank"><i class="bi bi-instagram"></i></a><?php endif;?>
          <?php if(!empty($settings->youtube)):?><a href="<?= esc($settings->youtube) ?>" target="_blank"><i class="bi bi-youtube"></i></a><?php endif;?>
        </div>
      </div>
      <hr style="border-color:rgba(255,255,255,.1);margin:28px 0 20px;">
      <div class="d-flex flex-wrap justify-content-between gap-2" style="font-size:.82rem;">
        <span><?= $content->footer_text ? esc($content->footer_text) : '&copy; ' . date('Y') . ' ' . esc($churchName) . '. All rights reserved.' ?></span>
        <a href="<?= base_url('login') ?>" style="color:rgba(255,255,255,.65);">Admin Login</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
