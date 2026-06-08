<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>MyChurchApp-Login</title>

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
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/style.css">
</head>

<body class="login-page" style="background-image: url('<?php echo base_url(); ?>/public/assets/vendors/images/bgimg.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: cover; background-position: center;">
	<?php $validation =  \Config\Services::validation(); ?>

	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">

				<div class="col-md-12 col-lg-12">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Admin Login</h2>
						</div>
						<form action="<?php echo base_url(); ?>/authenticate" method="POST">

							<div class="input-group custom">
								<input type="email" name="email" class="form-control form-control-lg" placeholder="Email">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>

							</div>
							<?php if ($validation->getError('email')) { ?>
								<div class='alert alert-danger mt-2'>
									<?= $error = $validation->getError('email'); ?>
								</div>
							<?php } ?>
							<div class="input-group custom">
								<input type="password" name="password" class="form-control form-control-lg" placeholder="**********">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>

							</div>
							<?php if ($validation->getError('password')) { ?>
								<div class='alert alert-danger mt-2'>
									<?= $error = $validation->getError('password'); ?>
								</div>
							<?php } ?>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<?= view('_flash') ?>
										<!--
                      use code for form submit
                      <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                    -->
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
									</div>

								</div>
							</div>


						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/core.js"></script>
	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/script.min.js"></script>
	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/process.js"></script>
	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/layout-settings.js"></script>
</body>

</html>