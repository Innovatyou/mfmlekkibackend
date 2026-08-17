<?php
$brandColor = \Config\Database::connect('default')->table('settings')->select('brand_color')->get()->getRow(0)->brand_color ?? '#6366f1';
if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $brandColor)) {
	$brandColor = '#6366f1';
}
$brandColorDark = '#' . implode('', array_map(
	fn($c) => str_pad(dechex((int) max(0, round(hexdec($c) * 0.8))), 2, '0', STR_PAD_LEFT),
	str_split(ltrim($brandColor, '#'), 2)
));
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>MyChurchApp — Sign In</title>
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>/public/favicon.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>/public/apple-touch-icon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/icon-font.min.css">
	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', sans-serif;
			min-height: 100vh;
			display: flex;
			background: #0f172a;
			overflow: hidden;
		}

		/* ── Animated background ── */
		.login-bg {
			position: fixed;
			inset: 0;
			background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1a1035 100%);
			z-index: 0;
		}
		.orb {
			position: absolute;
			border-radius: 50%;
			filter: blur(80px);
			opacity: 0.35;
			animation: drift 12s ease-in-out infinite alternate;
		}
		.orb-1 { width: 520px; height: 520px; background: #3b82f6; top: -180px; left: -120px; animation-delay: 0s; }
		.orb-2 { width: 400px; height: 400px; background: #8b5cf6; bottom: -150px; right: -80px; animation-delay: -4s; }
		.orb-3 { width: 300px; height: 300px; background: #06b6d4; top: 40%; left: 30%; animation-delay: -7s; }
		@keyframes drift {
			from { transform: translate(0, 0) scale(1); }
			to   { transform: translate(40px, 30px) scale(1.08); }
		}

		/* ── Layout ── */
		.login-wrapper {
			position: relative;
			z-index: 1;
			display: flex;
			width: 100%;
			min-height: 100vh;
			align-items: center;
			justify-content: center;
			padding: 24px;
		}

		.login-card {
			display: flex;
			width: 100%;
			max-width: 960px;
			min-height: 560px;
			border-radius: 24px;
			overflow: hidden;
			box-shadow: 0 32px 80px rgba(0,0,0,0.5);
			animation: cardIn 0.6s cubic-bezier(0.22,1,0.36,1) both;
		}
		@keyframes cardIn {
			from { opacity: 0; transform: translateY(32px) scale(0.97); }
			to   { opacity: 1; transform: translateY(0) scale(1); }
		}

		/* ── Left branding panel ── */
		.brand-panel {
			flex: 1;
			background: linear-gradient(160deg, #1d4ed8 0%, #7c3aed 60%, #0e7490 100%);
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 48px 40px;
			position: relative;
			overflow: hidden;
		}
		.brand-panel::before {
			content: '';
			position: absolute;
			inset: 0;
			background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		}
		.brand-circles {
			position: absolute;
			inset: 0;
			pointer-events: none;
		}
		.brand-circle {
			position: absolute;
			border-radius: 50%;
			border: 1px solid rgba(255,255,255,0.12);
		}
		.bc-1 { width: 260px; height: 260px; top: -80px; right: -80px; }
		.bc-2 { width: 180px; height: 180px; bottom: -50px; left: -50px; }
		.bc-3 { width: 120px; height: 120px; bottom: 80px; right: 40px; }

		.brand-logo {
			width: 72px;
			height: 72px;
			background: rgba(255,255,255,0.15);
			backdrop-filter: blur(8px);
			border-radius: 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 28px;
			border: 1px solid rgba(255,255,255,0.2);
		}
		.brand-logo svg { width: 38px; height: 38px; }
		.brand-panel h1 {
			color: #fff;
			font-size: 1.75rem;
			font-weight: 800;
			letter-spacing: -0.02em;
			text-align: center;
			margin-bottom: 12px;
		}
		.brand-panel p {
			color: rgba(255,255,255,0.7);
			font-size: 0.92rem;
			text-align: center;
			line-height: 1.6;
			max-width: 240px;
		}
		.brand-badges {
			display: flex;
			gap: 10px;
			margin-top: 36px;
			flex-wrap: wrap;
			justify-content: center;
		}
		.brand-badge {
			background: rgba(255,255,255,0.12);
			border: 1px solid rgba(255,255,255,0.18);
			color: rgba(255,255,255,0.85);
			font-size: 0.75rem;
			font-weight: 500;
			padding: 5px 12px;
			border-radius: 20px;
			backdrop-filter: blur(4px);
		}

		/* ── Right form panel ── */
		.form-panel {
			flex: 1;
			background: #ffffff;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 52px 48px;
		}

		.form-header { margin-bottom: 36px; }
		.form-header h2 {
			font-size: 1.6rem;
			font-weight: 800;
			color: #0f172a;
			letter-spacing: -0.02em;
			margin-bottom: 6px;
		}
		.form-header p { font-size: 0.875rem; color: #64748b; }

		/* ── Floating label inputs ── */
		.field-group {
			position: relative;
			margin-bottom: 20px;
		}
		.field-group label {
			position: absolute;
			top: 50%;
			left: 16px;
			transform: translateY(-50%);
			font-size: 0.875rem;
			color: #94a3b8;
			pointer-events: none;
			transition: all 0.2s ease;
			background: transparent;
			padding: 0 4px;
		}
		.field-group input {
			width: 100%;
			height: 54px;
			padding: 18px 48px 6px 16px;
			border: 1.5px solid #e2e8f0;
			border-radius: 12px;
			font-size: 0.9rem;
			font-family: 'Inter', sans-serif;
			color: #0f172a;
			background: #f8fafc;
			outline: none;
			transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
		}
		.field-group input:focus,
		.field-group input:not(:placeholder-shown) {
			background: #fff;
		}
		.field-group input:focus { border-color: <?= $brandColor ?>; box-shadow: 0 0 0 4px <?= $brandColor ?>1a; }
		.field-group input:focus + label,
		.field-group input:not(:placeholder-shown) + label {
			top: 10px;
			font-size: 0.72rem;
			color: <?= $brandColor ?>;
			font-weight: 500;
		}
		.field-icon {
			position: absolute;
			right: 16px;
			top: 50%;
			transform: translateY(-50%);
			color: #94a3b8;
			font-size: 1rem;
			cursor: pointer;
			transition: color 0.2s;
			background: none;
			border: none;
			padding: 0;
			display: flex;
			align-items: center;
		}
		.field-icon:hover { color: <?= $brandColor ?>; }
		/* keep placeholder invisible so floating label trick works */
		.field-group input::placeholder { color: transparent; }

		/* ── Validation errors ── */
		.field-error {
			display: flex;
			align-items: center;
			gap: 6px;
			margin-top: -12px;
			margin-bottom: 12px;
			font-size: 0.78rem;
			color: #ef4444;
			font-weight: 500;
		}
		.field-error::before { content: '⚠'; font-size: 0.8rem; }

		/* ── Flash messages ── */
		.flash-area .alert {
			border-radius: 10px;
			font-size: 0.85rem;
			padding: 10px 14px;
			margin-bottom: 16px;
		}

		/* ── Submit button ── */
		.btn-signin {
			width: 100%;
			height: 52px;
			border: none;
			border-radius: 12px;
			background: linear-gradient(135deg, <?= $brandColor ?>, <?= $brandColorDark ?>);
			color: #fff;
			font-family: 'Inter', sans-serif;
			font-size: 0.95rem;
			font-weight: 600;
			letter-spacing: 0.01em;
			cursor: pointer;
			position: relative;
			overflow: hidden;
			transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
			box-shadow: 0 4px 20px rgba(37,99,235,0.35);
			margin-top: 4px;
		}
		.btn-signin:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 8px 28px rgba(37,99,235,0.45); }
		.btn-signin:active { transform: translateY(0); }
		.btn-signin .btn-text { transition: opacity 0.2s; }
		.btn-signin .btn-spinner {
			display: none;
			position: absolute;
			inset: 0;
			align-items: center;
			justify-content: center;
		}
		.btn-signin.loading .btn-text { opacity: 0; }
		.btn-signin.loading .btn-spinner { display: flex; }
		.spinner-ring {
			width: 22px; height: 22px;
			border: 2.5px solid rgba(255,255,255,0.35);
			border-top-color: #fff;
			border-radius: 50%;
			animation: spin 0.7s linear infinite;
		}
		@keyframes spin { to { transform: rotate(360deg); } }

		/* ── Responsive ── */
		@media (max-width: 768px) {
			.brand-panel { display: none; }
			.form-panel { padding: 40px 28px; }
			.login-card { max-width: 420px; border-radius: 20px; }
		}
	</style>
</head>

<body>
	<?php $validation = \Config\Services::validation(); ?>

	<div class="login-bg">
		<div class="orb orb-1"></div>
		<div class="orb orb-2"></div>
		<div class="orb orb-3"></div>
	</div>

	<div class="login-wrapper">
		<div class="login-card">

			<!-- Left branding panel -->
			<div class="brand-panel">
				<div class="brand-circles">
					<div class="brand-circle bc-1"></div>
					<div class="brand-circle bc-2"></div>
					<div class="brand-circle bc-3"></div>
				</div>
				<div class="brand-logo">
					<svg viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M19 4L19 8M19 4L16 7M19 4L22 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M8 16H30V32C30 33.1046 29.1046 34 28 34H10C8.89543 34 8 33.1046 8 32V16Z" stroke="white" stroke-width="2"/>
						<path d="M5 16H33" stroke="white" stroke-width="2" stroke-linecap="round"/>
						<path d="M14 16V10C14 8.89543 14.8954 8 16 8H22C23.1046 8 24 8.89543 24 10V16" stroke="white" stroke-width="2"/>
						<path d="M16 26H22M19 23V29" stroke="white" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</div>
				<h1>MyChurchApp</h1>
				<p>Welcome back! Manage your congregation, events, and community from one place.</p>
				<div class="brand-badges">
					<span class="brand-badge">Members</span>
					<span class="brand-badge">Events</span>
					<span class="brand-badge">Finance</span>
					<span class="brand-badge">Reports</span>
				</div>
			</div>

			<!-- Right form panel -->
			<div class="form-panel">
				<div class="form-header">
					<h2>Welcome back</h2>
					<p>Sign in to your admin account</p>
				</div>

				<form action="<?php echo base_url(); ?>/authenticate" method="POST" id="loginForm" novalidate>

					<!-- Email -->
					<div class="field-group">
						<input type="email" id="email" name="email" placeholder="Email address"
							value="<?= old('email') ?>" autocomplete="email" required>
						<label for="email">Email address</label>
						<button type="button" class="field-icon" tabindex="-1" aria-hidden="true">
							<i class="dw dw-user1"></i>
						</button>
					</div>
					<?php if ($validation->getError('email')) : ?>
						<div class="field-error"><?= esc($validation->getError('email')) ?></div>
					<?php endif; ?>

					<!-- Password -->
					<div class="field-group">
						<input type="password" id="password" name="password" placeholder="Password"
							autocomplete="current-password" required>
						<label for="password">Password</label>
						<button type="button" class="field-icon" id="togglePassword" tabindex="-1" title="Show / hide password">
							<!-- eye-open -->
							<svg id="eyeIconShow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
								<circle cx="12" cy="12" r="3"></circle>
							</svg>
							<!-- eye-off -->
							<svg id="eyeIconHide" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
								<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
								<line x1="1" y1="1" x2="23" y2="23"></line>
							</svg>
						</button>
					</div>
					<?php if ($validation->getError('password')) : ?>
						<div class="field-error"><?= esc($validation->getError('password')) ?></div>
					<?php endif; ?>

					<div style="text-align:right; margin-top:-8px; margin-bottom:16px;">
						<a href="<?= base_url('forgot-password') ?>" style="font-size:0.8rem; color:<?= $brandColor ?>; text-decoration:none;">Forgot password?</a>
					</div>

					<!-- Flash messages -->
					<div class="flash-area">
						<?= view('_flash') ?>
					</div>

					<!-- Submit -->
					<button type="submit" class="btn-signin" id="signinBtn">
						<span class="btn-text">Sign In</span>
						<span class="btn-spinner"><div class="spinner-ring"></div></span>
					</button>

				</form>
			</div>

		</div>
	</div>

	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/core.js"></script>
	<script>
		// Password visibility toggle
		document.getElementById('togglePassword').addEventListener('click', function () {
			var pwd     = document.getElementById('password');
			var eyeShow = document.getElementById('eyeIconShow');
			var eyeHide = document.getElementById('eyeIconHide');
			if (pwd.type === 'password') {
				pwd.type = 'text';
				eyeShow.style.display = 'none';
				eyeHide.style.display = '';
			} else {
				pwd.type = 'password';
				eyeShow.style.display = '';
				eyeHide.style.display = 'none';
			}
		});

		// Loading state on submit
		document.getElementById('loginForm').addEventListener('submit', function () {
			var btn = document.getElementById('signinBtn');
			btn.classList.add('loading');
			btn.disabled = true;
		});
	</script>
</body>

</html>
