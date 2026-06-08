<?php
helper('AdminAuth');
$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title><?php echo $locale['site_title']; ?></title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>/public/assets/vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>/public/assets/vendors/images/favicon.ico">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/style.css">
	<link type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/sweetalert/sweetalert.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/dropify/dist/css/dropify.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/dropzone/src/dropzone.css">
	<script src='<?= base_url() ?>/public/assets/tinymce/tinymce.js'></script>

</head>
<script type="text/javascript">
	var baseURL = "<?php echo base_url(); ?>";
</script>
<?php $session = session(); ?>

<body>


	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>

		</div>
		<div class="header-right">

			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<?php if ($session->get('logo') != "") { ?>
								<img src="<?php echo $session->get('logo'); ?>" style="height:50px; width:50px;">
							<?php } ?>
						</span>
						<span class="user-name"><?php echo $session->get('name'); ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="logout"><i class="dw dw-logout"></i> <?php echo $locale['logout']; ?></a>
					</div>
				</div>
			</div>

		</div>
	</div>


	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="">
				<h4 style="color:white;"><?php echo $session->get('name'); ?></h4>
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li>
						<a href="<?php echo base_url(); ?>/dashboard" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'dashboard') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-home"></span><span class="mtext"><?php echo $locale['dashboard']; ?></span>
						</a>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle <?php if (strpos(strtolower($url), 'members') !== false) { ?> active <?php } ?>">
							<span class="micon fi-torsos-all"></span><span class="mtext"><?php echo $locale['members']; ?></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/membersListing" <?php if (strpos(strtolower($url), 'members') !== false) { ?> class="active" <?php } ?>><?php echo $locale['all_members']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/lists" <?php if (strpos(strtolower($url), 'list') !== false) { ?> class="active" <?php } ?>><?php echo $locale['email_sms_list']; ?></a></li>
						</ul>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>/donations" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'donation') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-wallet1"></span><span class="mtext"><?php echo $locale['donations']; ?></span>
						</a>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-video-camera"></span><span class="mtext"><?php echo $locale['media']; ?></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/videos" <?php if (strpos(strtolower($url), 'video') !== false) { ?> class="active" <?php } ?>><?php echo $locale['videos']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/audios" <?php if (strpos(strtolower($url), 'audio') !== false) { ?> class="active" <?php } ?>><?php echo $locale['audios']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/livestreams" <?php if (strpos(strtolower($url), 'livestream') !== false) { ?> class="active" <?php } ?>><?php echo $locale['livestream']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/radio" <?php if (strpos(strtolower($url), 'radio') !== false) { ?> class="active" <?php } ?>><?php echo $locale['radio']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/photos" <?php if (strpos(strtolower($url), 'photo') !== false) { ?> class="active" <?php } ?>><?php echo $locale['photos']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/zoomadmin" <?php if (strpos(strtolower($url), 'zoom') !== false) { ?> class="active" <?php } ?>>Zoom Live Service</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-books"></span><span class="mtext"><?php echo $locale['publications']; ?></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/devotionalsListing" <?php if (strpos(strtolower($url), 'devotional') !== false) { ?> class="active" <?php } ?>><?php echo $locale['devotionals']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/books" <?php if (strpos(strtolower($url), 'book') !== false) { ?> class="active" <?php } ?>><?php echo $locale['books']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/articlesListing" <?php if (strpos(strtolower($url), 'article') !== false) { ?> class="active" <?php } ?>><?php echo $locale['articles']; ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-group"></span><span class="mtext"><?php echo $locale['connect']; ?></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/groups" <?php if (strpos(strtolower($url), 'group') !== false) { ?> class="active" <?php } ?>><?php echo $locale['groups']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/prayersListing" <?php if (strpos($url, 'prayer') !== false || strpos(strtolower($url), 'Prayer') !== false) { ?> class="active" <?php } ?>><?php echo $locale['prayers']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/testimonyListing" <?php if (strpos(strtolower($url), 'testimo') !== false) { ?> class="active" <?php } ?>><?php echo $locale['testimonies']; ?></a></li>
						</ul>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>/eventsListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'event') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-calendar1"></span><span class="mtext"><?php echo $locale['events']; ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>/hymnsListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'hymn') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-open-book"></span><span class="mtext"><?php echo $locale['hymns']; ?></span>
						</a>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-email"></span><span class="mtext"><?php echo $locale['messaging']; ?></span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/messaging" <?php if (strpos(strtolower($url), 'messag') !== false) { ?> class="active" <?php } ?>><?php echo $locale['mail_sms']; ?></a></li>
							<li><a href="<?php echo base_url(); ?>/inbox" <?php if (strpos($url, 'inbox') !== false || strpos(strtolower($url), 'inbox') !== false) { ?> class="active" <?php } ?>><?php echo $locale['notifications']; ?></a></li>
						</ul>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>/branchesListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'branch') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-city"></span><span class="mtext"><?php echo $locale['locations']; ?></span>
						</a>
					</li>
					<?php if (isSuperAdmin()): ?>
					<li>
						<a href="<?php echo base_url(); ?>/settings" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url), 'settings') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-settings"></span><span class="mtext">Settings</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if (isSuperAdmin()): ?>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle <?php if (strpos(strtolower($url), 'admin') !== false) { ?> active <?php } ?>">
							<span class="micon dw dw-user1"></span><span class="mtext">Administration</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/admin/users" <?php if (strpos(strtolower($url), 'admin/users') !== false) { ?> class="active" <?php } ?>>Admin Users</a></li>
							<li><a href="<?php echo base_url(); ?>/admin/roles" <?php if (strpos(strtolower($url), 'admin/roles') !== false) { ?> class="active" <?php } ?>>User Roles</a></li>
							<li><a href="<?php echo base_url(); ?>/adminListing" <?php if (strpos(strtolower($url), 'adminlisting') !== false) { ?> class="active" <?php } ?>>Legacy Admin List</a></li>
						</ul>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>