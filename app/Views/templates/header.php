<?php
helper('AdminAuth');
$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$session = session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $locale['site_title'] ?></title>
  <link rel="icon" type="image/svg+xml" href="<?= base_url() ?>/public/favicon.svg">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url() ?>/public/favicon.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="csrf-token" content="<?= csrf_hash() ?>"><?php // CI4 CSRF token for AJAX requests ?>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/vendors/styles/core.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/vendors/styles/icon-font.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/vendors/styles/style.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/vendors/sweetalert/sweetalert.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/vendors/dropify/dist/css/dropify.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/assets/src/plugins/dropzone/src/dropzone.css">
  <script src="<?= base_url() ?>/public/assets/tinymce/tinymce.js"></script>

  <style>
    /* ══════════════════════════════════════════
       DESIGN TOKENS  (visual only — layout left to framework)
    ══════════════════════════════════════════ */
    :root {
      --sb-bg:        #0d1117;
      --sb-border:    rgba(255,255,255,.08);
      --sb-text:      rgba(148,163,184,.9);
      --sb-text-h:    #e6edf3;
      --sb-hover-bg:  rgba(255,255,255,.05);
      --sb-active-bg: rgba(99,102,241,.15);
      --sb-active-fg: #a5b4fc;
      --sb-icon:      rgba(100,116,139,.8);
      --sb-label:     rgba(71,85,105,.75);
      --accent:       #6366f1;
      --accent-d:     #4f46e5;
      --page-bg:      #f0f2f5;
      --card-bg:      #ffffff;
      --border:       #e2e8f0;
      --t1:           #0f172a;
      --t2:           #475569;
      --t3:           #94a3b8;
      --radius:       10px;
      --shadow-sm:    0 1px 2px rgba(0,0,0,.05),0 1px 3px rgba(0,0,0,.08);
      --shadow-md:    0 4px 6px rgba(0,0,0,.07),0 2px 4px rgba(0,0,0,.05);
    }

    /* ── Base ── */
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
      background: var(--page-bg) !important;
      color: var(--t1) !important;
    }

    /* ══════════════════════════════════════════
       SIDEBAR  — colour/style only, NO width/position overrides
    ══════════════════════════════════════════ */
    .left-side-bar {
      background: var(--sb-bg) !important;
      border-right: 1px solid var(--sb-border) !important;
      box-shadow: 2px 0 20px rgba(0,0,0,.35) !important;
    }

    /* Brand strip */
    .left-side-bar .brand-logo {
      background: var(--sb-bg) !important;
      border-bottom: 1px solid var(--sb-border) !important;
    }
    .left-side-bar .brand-logo a {
      color: #e6edf3 !important;
      font-weight: 700 !important;
      font-size: .92rem !important;
      letter-spacing: -.01em !important;
      gap: 10px !important;
    }
    .left-side-bar .brand-logo .close-sidebar {
      color: var(--sb-label) !important;
      transition: color .15s !important;
    }
    .left-side-bar .brand-logo .close-sidebar:hover { color: #e6edf3 !important; }

    /* Brand icon pill */
    .sb-brand-icon {
      width: 30px; height: 30px; flex-shrink: 0;
      background: linear-gradient(135deg,#6366f1,#8b5cf6);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: #fff;
    }

    /* ── Nav items (framework keeps position:absolute micon — don't change display/padding) ── */
    .sidebar-menu .dropdown-toggle {
      color: var(--sb-text) !important;
      font-size: .875rem !important;
      font-weight: 500 !important;
      transition: background .15s, color .15s !important;
    }
    .sidebar-menu .dropdown-toggle:hover,
    .sidebar-menu .show > .dropdown-toggle {
      background: var(--sb-hover-bg) !important;
      color: var(--sb-text-h) !important;
    }

    /* Active top-level item */
    .sidebar-menu > ul > li > .dropdown-toggle.active {
      background: var(--sb-active-bg) !important;
      color: var(--sb-active-fg) !important;
      border-left: 3px solid var(--accent) !important;
    }
    .sidebar-menu > ul > li > .dropdown-toggle.active .micon {
      color: var(--sb-active-fg) !important;
    }

    /* Icons */
    .sidebar-menu .dropdown-toggle .micon {
      color: var(--sb-icon) !important;
      transition: color .15s !important;
    }
    .sidebar-menu .dropdown-toggle:hover .micon { color: var(--sb-text-h) !important; }

    /* Submenu container */
    .sidebar-menu .submenu {
      background: rgba(0,0,0,.18) !important;
    }
    .sidebar-menu .submenu li a {
      color: rgba(148,163,184,.8) !important;
      font-size: .845rem !important;
      transition: background .15s, color .15s !important;
    }
    .sidebar-menu .submenu li a:hover {
      background: rgba(255,255,255,.04) !important;
      color: var(--sb-text-h) !important;
    }
    .sidebar-menu .submenu li a.active {
      background: rgba(255,255,255,.04) !important;
      color: var(--sb-active-fg) !important;
    }

    /* Section labels — sits inside the ul as a plain li */
    .sidebar-menu ul li.nav-label {
      padding: 18px 15px 5px !important;
      font-size: .67rem !important;
      font-weight: 700 !important;
      letter-spacing: .08em !important;
      text-transform: uppercase !important;
      color: var(--sb-label) !important;
      pointer-events: none !important;
      line-height: 1 !important;
    }

    /* ══════════════════════════════════════════
       TOPBAR  — colour/shadow only, NO width/height/position overrides
    ══════════════════════════════════════════ */
    .header {
      background: #ffffff !important;
      box-shadow: 0 1px 0 var(--border) !important;
    }

    /* Hamburger */
    .header .header-left .menu-icon {
      color: var(--t2) !important;
      border-radius: 8px !important;
      padding: 6px !important;
      cursor: pointer !important;
      transition: background .15s !important;
    }
    .header .header-left .menu-icon:hover {
      background: #f1f5f9 !important;
      color: var(--t1) !important;
    }

    /* User avatar wrapper */
    .user-icon-wrap {
      width: 34px; height: 34px; border-radius: 50%;
      overflow: hidden; flex-shrink: 0; display: inline-flex;
      align-items: center; justify-content: center;
      background: linear-gradient(135deg,#6366f1,#8b5cf6);
      border: 2px solid var(--border);
      vertical-align: middle;
    }
    .user-icon-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .user-icon-initials { font-size: .78rem; font-weight: 700; color: #fff; line-height: 1; }

    .user-info-dropdown .dropdown-toggle {
      border-radius: 10px !important;
      transition: background .15s !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      padding: 5px 10px !important;
    }
    .user-info-dropdown .dropdown-toggle:hover { background: #f1f5f9 !important; }
    .user-info-dropdown .user-name {
      font-weight: 600 !important;
      font-size: .875rem !important;
      color: var(--t1) !important;
    }

    .user-info-dropdown .dropdown-menu {
      border: 1px solid var(--border) !important;
      border-radius: 10px !important;
      box-shadow: 0 10px 30px rgba(0,0,0,.12) !important;
      padding: 6px !important;
      min-width: 160px !important;
    }
    .user-info-dropdown .dropdown-menu .dropdown-item {
      border-radius: 7px !important;
      font-size: .875rem !important;
      padding: 8px 12px !important;
      color: var(--t2) !important;
      transition: background .15s !important;
    }
    .user-info-dropdown .dropdown-menu .dropdown-item:hover {
      background: #f1f5f9 !important;
      color: var(--t1) !important;
    }

    /* ══════════════════════════════════════════
       MAIN CONTENT  — background only
    ══════════════════════════════════════════ */
    .main-container { background: var(--page-bg) !important; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
    .main-container { animation: fadeUp .3s ease both; }

    /* ── Cards ── */
    .card-box {
      background: var(--card-bg) !important;
      border-radius: var(--radius) !important;
      box-shadow: var(--shadow-sm) !important;
      border: 1px solid var(--border) !important;
    }

    /* ── DataTables ── */
    .data-table.table thead th {
      background: #f8fafc !important;
      border-bottom: 1px solid var(--border) !important;
      border-top: none !important;
      color: var(--t2) !important;
      font-size: .74rem !important; font-weight: 700 !important;
      text-transform: uppercase !important; letter-spacing: .05em !important;
    }
    .data-table.table tbody tr:hover td { background: #f8fafc !important; }
    .data-table.table td {
      border-top: none !important;
      border-bottom: 1px solid var(--border) !important;
      font-size: .875rem !important;
      vertical-align: middle !important;
    }
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
      border-radius: 7px !important; border-color: var(--border) !important;
      font-size: .85rem !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important;
      outline: none !important;
    }

    /* ── Buttons ── */
    .btn { border-radius: 8px !important; font-weight: 500 !important; font-size: .875rem !important; }
    .btn-primary { background: var(--accent) !important; border-color: var(--accent) !important; }
    .btn-primary:hover,
    .btn-primary:focus { background: var(--accent-d) !important; border-color: var(--accent-d) !important; }

    /* ── Forms ── */
    .form-control {
      border-radius: 8px !important; border-color: var(--border) !important;
      background: #f8fafc !important; font-size: .875rem !important;
      transition: border-color .15s, box-shadow .15s !important;
    }
    .form-control:focus {
      background: #fff !important; border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important;
    }
    label { font-size: .85rem !important; font-weight: 500 !important; color: var(--t2) !important; }

    /* ── Alerts ── */
    .alert { border-radius: 9px !important; border: none !important; font-size: .875rem !important; }
    .alert-success { background: #d1fae5 !important; color: #065f46 !important; }
    .alert-danger  { background: #fee2e2 !important; color: #7f1d1d !important; }

    /* ── Badges ── */
    .badge-pill { padding: 3px 10px !important; border-radius: 20px !important; font-size: .72rem !important; font-weight: 600 !important; }
    .badge-success  { background: #d1fae5 !important; color: #065f46 !important; }
    .badge-info     { background: #e0f2fe !important; color: #0c4a6e !important; }
    .badge-warning  { background: #fef3c7 !important; color: #78350f !important; }
    .badge-danger   { background: #fee2e2 !important; color: #7f1d1d !important; }
    .badge-secondary{ background: #f1f5f9 !important; color: var(--t2) !important; }

    /* ── Page header ── */
    .page-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-title {
      font-size: 1.3rem !important; font-weight: 800 !important;
      color: var(--t1) !important; letter-spacing: -.02em !important; margin: 0 !important;
    }
    .page-subtitle { font-size: .82rem; color: var(--t3); margin: 2px 0 0; }

    /* ── Dropify ── */
    .dropify-wrapper {
      border-radius: var(--radius) !important;
      border: 2px dashed var(--border) !important;
      background: #f8fafc !important;
    }
    .dropify-wrapper:hover { border-color: var(--accent) !important; }

    /* ── Scrollbar (sidebar only) ── */
    .customscroll::-webkit-scrollbar { width: 3px; }
    .customscroll::-webkit-scrollbar-track { background: transparent; }
    .customscroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 4px; }
    .customscroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.18); }
  </style>
</head>

<script>
var baseURL = "<?= base_url() ?>";
// Unregister any stale service workers — the admin panel does not use a SW.
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(function(regs) {
    regs.forEach(function(r) { r.unregister(); });
  });
}
</script>

<body>

<!-- ── TOPBAR ── -->
<div class="header">
  <div class="header-left">
    <div class="menu-icon dw dw-menu"></div>
  </div>
  <div class="header-right">
    <div class="user-info-dropdown">
      <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
          <span class="user-icon">
            <?php if ($session->get('logo') != ''): ?>
              <span class="user-icon-wrap"><img src="<?= esc($session->get('logo')) ?>"></span>
            <?php else: ?>
              <span class="user-icon-wrap">
                <span class="user-icon-initials"><?= strtoupper(substr($session->get('name') ?: 'A', 0, 1)) ?></span>
              </span>
            <?php endif; ?>
          </span>
          <span class="user-name"><?= esc($session->get('name')) ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
          <a class="dropdown-item" href="<?= base_url() ?>/logout">
            <i class="dw dw-logout"></i> <?= $locale['logout'] ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ── SIDEBAR ── -->
<div class="left-side-bar">
  <div class="brand-logo">
    <a href="<?= base_url() ?>/dashboard">
      <span class="sb-brand-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
          <polyline points="9,22 9,12 15,12 15,22"/>
        </svg>
      </span>
      <span>MyChurchApp</span>
    </a>
    <div class="close-sidebar" data-toggle="left-sidebar-close">
      <i class="ion-close-round"></i>
    </div>
  </div>

  <div class="menu-block customscroll">
    <div class="sidebar-menu">
      <ul id="accordion-menu">

        <li class="nav-label">Main</li>

        <li>
          <a href="<?= base_url() ?>/dashboard" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'dashboard') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-home"></span><span class="mtext"><?= $locale['dashboard'] ?></span>
          </a>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle <?= (strpos(strtolower($url), 'members') !== false || strpos(strtolower($url), '/list') !== false || strpos(strtolower($url), 'membercare') !== false || strpos(strtolower($url), 'counseling') !== false) ? 'active' : '' ?>">
            <span class="micon fi-torsos-all"></span><span class="mtext"><?= $locale['members'] ?></span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/membersListing" <?= (strpos(strtolower($url), 'memberslisting') !== false || strpos(strtolower($url), 'newmember') !== false || strpos(strtolower($url), 'editmember') !== false || strpos(strtolower($url), 'viewmember') !== false) ? 'class="active"' : '' ?>><?= $locale['all_members'] ?></a></li>
            <li><a href="<?= base_url() ?>/memberCare" <?= strpos(strtolower($url), 'membercare') !== false ? 'class="active"' : '' ?>>Member Care</a></li>
            <li><a href="<?= base_url() ?>/counseling" <?= strpos(strtolower($url), 'counseling') !== false ? 'class="active"' : '' ?>>Counseling</a></li>
            <li><a href="<?= base_url() ?>/lists" <?= strpos(strtolower($url), '/list') !== false ? 'class="active"' : '' ?>><?= $locale['email_sms_list'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="<?= base_url() ?>/donations" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'donation') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-wallet1"></span><span class="mtext"><?= $locale['donations'] ?></span>
          </a>
        </li>

        <li>
          <a href="<?= base_url() ?>/marketplaceListing" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'marketplace') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-shop"></span><span class="mtext">Marketplace</span>
          </a>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle <?= (strpos(strtolower($url), 'landingcontent') !== false || strpos(strtolower($url), 'servicetimes') !== false || strpos(strtolower($url), 'leadership') !== false || strpos(strtolower($url), 'signuprequests') !== false || strpos(strtolower($url), 'membershipform') !== false || strpos(strtolower($url), 'contactmessage') !== false) ? 'active' : '' ?>">
            <span class="micon dw dw-browser"></span><span class="mtext">Website</span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/landingContent" <?= strpos(strtolower($url), 'landingcontent') !== false ? 'class="active"' : '' ?>>Content Editor</a></li>
            <li><a href="<?= base_url() ?>/serviceTimesListing" <?= strpos(strtolower($url), 'servicetime') !== false ? 'class="active"' : '' ?>>Service Times</a></li>
            <li><a href="<?= base_url() ?>/leadershipListing" <?= strpos(strtolower($url), 'leader') !== false ? 'class="active"' : '' ?>>Leadership</a></li>
            <li><a href="<?= base_url() ?>/membershipFormListing" <?= strpos(strtolower($url), 'membershipfield') !== false || strpos(strtolower($url), 'membershipform') !== false ? 'class="active"' : '' ?>>Membership Form</a></li>
            <li><a href="<?= base_url() ?>/signupRequests" <?= strpos(strtolower($url), 'signuprequests') !== false ? 'class="active"' : '' ?>>Signup Requests</a></li>
            <li><a href="<?= base_url() ?>/contactMessages" <?= strpos(strtolower($url), 'contactmessage') !== false ? 'class="active"' : '' ?>>Contact Messages</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle <?= (strpos(strtolower($url), 'partnership') !== false) ? 'active' : '' ?>">
            <span class="micon dw dw-handshake"></span><span class="mtext">Partnership</span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/partnership" <?= ($url === base_url() . '/partnership' || strpos(strtolower($url), '/partnership') !== false && strpos(strtolower($url), 'listing') === false && strpos(strtolower($url), 'tier') === false && strpos(strtolower($url), 'new') === false && strpos(strtolower($url), 'edit') === false && strpos(strtolower($url), 'delete') === false) ? 'class="active"' : '' ?>>Overview</a></li>
            <li><a href="<?= base_url() ?>/partnershipListing" <?= strpos(strtolower($url), 'partnershiplisting') !== false || strpos(strtolower($url), 'newpartnership') !== false || strpos(strtolower($url), 'editpartnership') !== false ? 'class="active"' : '' ?>>All Partners</a></li>
            <li><a href="<?= base_url() ?>/partnershipTiers" <?= strpos(strtolower($url), 'partnershiptiers') !== false ? 'class="active"' : '' ?>>Tiers</a></li>
          </ul>
        </li>

        <li class="nav-label">Content</li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle">
            <span class="micon dw dw-video-camera"></span><span class="mtext"><?= $locale['media'] ?></span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/videos"      <?= strpos(strtolower($url), 'video')      !== false ? 'class="active"' : '' ?>><?= $locale['videos'] ?></a></li>
            <li><a href="<?= base_url() ?>/audios"      <?= strpos(strtolower($url), 'audio')      !== false ? 'class="active"' : '' ?>><?= $locale['audios'] ?></a></li>
            <li><a href="<?= base_url() ?>/livestreams" <?= strpos(strtolower($url), 'livestream') !== false ? 'class="active"' : '' ?>><?= $locale['livestream'] ?></a></li>
            <li><a href="<?= base_url() ?>/radio"       <?= strpos(strtolower($url), 'radio')      !== false ? 'class="active"' : '' ?>><?= $locale['radio'] ?></a></li>
            <li><a href="<?= base_url() ?>/photos"      <?= strpos(strtolower($url), 'photo')      !== false ? 'class="active"' : '' ?>><?= $locale['photos'] ?></a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle">
            <span class="micon dw dw-books"></span><span class="mtext"><?= $locale['publications'] ?></span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/devotionalsListing" <?= strpos(strtolower($url), 'devotional') !== false ? 'class="active"' : '' ?>><?= $locale['devotionals'] ?></a></li>
            <li><a href="<?= base_url() ?>/books"              <?= strpos(strtolower($url), 'book')        !== false ? 'class="active"' : '' ?>><?= $locale['books'] ?></a></li>
            <li><a href="<?= base_url() ?>/articlesListing"    <?= strpos(strtolower($url), 'article')     !== false ? 'class="active"' : '' ?>><?= $locale['articles'] ?></a></li>
          </ul>
        </li>

        <li class="nav-label">Community</li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle">
            <span class="micon dw dw-group"></span><span class="mtext"><?= $locale['connect'] ?></span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/groups"           <?= strpos(strtolower($url), 'group')   !== false ? 'class="active"' : '' ?>><?= $locale['groups'] ?></a></li>
            <li><a href="<?= base_url() ?>/prayersListing"   <?= strpos(strtolower($url), 'prayer')  !== false ? 'class="active"' : '' ?>><?= $locale['prayers'] ?></a></li>
            <li><a href="<?= base_url() ?>/testimonyListing" <?= strpos(strtolower($url), 'testimo') !== false ? 'class="active"' : '' ?>><?= $locale['testimonies'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="<?= base_url() ?>/eventsListing" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'event') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-calendar1"></span><span class="mtext"><?= $locale['events'] ?></span>
          </a>
        </li>

        <li>
          <a href="<?= base_url() ?>/hymnsListing" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'hymn') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-open-book"></span><span class="mtext"><?= $locale['hymns'] ?></span>
          </a>
        </li>

        <li class="nav-label">Tools</li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle">
            <span class="micon dw dw-email"></span><span class="mtext"><?= $locale['messaging'] ?></span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/messaging" <?= strpos(strtolower($url), 'messag') !== false ? 'class="active"' : '' ?>><?= $locale['mail_sms'] ?></a></li>
            <li><a href="<?= base_url() ?>/inbox"     <?= strpos(strtolower($url), 'inbox')  !== false ? 'class="active"' : '' ?>><?= $locale['notifications'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="<?= base_url() ?>/branchesListing" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'branch') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-city"></span><span class="mtext"><?= $locale['locations'] ?></span>
          </a>
        </li>

        <?php if (isSuperAdmin()): ?>
        <li class="nav-label">Administration</li>

        <li>
          <a href="<?= base_url() ?>/settings" class="dropdown-toggle no-arrow <?= strpos(strtolower($url), 'settings') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-settings"></span><span class="mtext">Settings</span>
          </a>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle <?= strpos(strtolower($url), 'admin') !== false ? 'active' : '' ?>">
            <span class="micon dw dw-user1"></span><span class="mtext">Administration</span>
          </a>
          <ul class="submenu">
            <li><a href="<?= base_url() ?>/admin/users"  <?= strpos(strtolower($url), 'admin/users')  !== false ? 'class="active"' : '' ?>>Admin Users</a></li>
            <li><a href="<?= base_url() ?>/admin/roles"  <?= strpos(strtolower($url), 'admin/roles')  !== false ? 'class="active"' : '' ?>>User Roles</a></li>
            <li><a href="<?= base_url() ?>/adminListing" <?= strpos(strtolower($url), 'adminlisting') !== false ? 'class="active"' : '' ?>>Legacy Admin List</a></li>
          </ul>
        </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</div>
