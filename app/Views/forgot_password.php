<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>MyChurchApp — Forgot Password</title>
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>/public/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/core.css">
	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', sans-serif;
			min-height: 100vh;
			display: flex;
			background: #0f172a;
			overflow: hidden;
		}

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
		.brand-circles { position: absolute; inset: 0; pointer-events: none; }
		.brand-circle { position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,0.12); }
		.bc-1 { width: 260px; height: 260px; top: -80px; right: -80px; }
		.bc-2 { width: 180px; height: 180px; bottom: -50px; left: -50px; }
		.bc-3 { width: 120px; height: 120px; bottom: 80px; right: 40px; }

		.brand-logo {
			width: 72px; height: 72px;
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
		.brand-panel h1 { color: #fff; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; text-align: center; margin-bottom: 12px; }
		.brand-panel p { color: rgba(255,255,255,0.7); font-size: 0.92rem; text-align: center; line-height: 1.6; max-width: 240px; }

		.form-panel {
			flex: 1;
			background: #ffffff;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 52px 48px;
		}

		.form-header { margin-bottom: 32px; }
		.form-header h2 { font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin-bottom: 6px; }
		.form-header p { font-size: 0.875rem; color: #64748b; line-height: 1.5; }

		.field-group { position: relative; margin-bottom: 20px; }
		.field-group label {
			position: absolute; top: 50%; left: 16px;
			transform: translateY(-50%);
			font-size: 0.875rem; color: #94a3b8;
			pointer-events: none;
			transition: all 0.2s ease;
			padding: 0 4px;
		}
		.field-group input {
			width: 100%; height: 54px;
			padding: 18px 16px 6px 16px;
			border: 1.5px solid #e2e8f0; border-radius: 12px;
			font-size: 0.9rem; font-family: 'Inter', sans-serif;
			color: #0f172a; background: #f8fafc;
			outline: none;
			transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
		}
		.field-group input:focus,
		.field-group input:not(:placeholder-shown) { background: #fff; }
		.field-group input:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
		.field-group input:focus + label,
		.field-group input:not(:placeholder-shown) + label { top: 10px; font-size: 0.72rem; color: #3b82f6; font-weight: 500; }
		.field-group input::placeholder { color: transparent; }

		.flash-area .alert { border-radius: 10px; font-size: 0.85rem; padding: 10px 14px; margin-bottom: 16px; }

		.btn-submit {
			width: 100%; height: 52px;
			border: none; border-radius: 12px;
			background: linear-gradient(135deg, #2563eb, #7c3aed);
			color: #fff;
			font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600;
			cursor: pointer;
			transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
			box-shadow: 0 4px 20px rgba(37,99,235,0.35);
			margin-top: 4px;
		}
		.btn-submit:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 8px 28px rgba(37,99,235,0.45); }
		.btn-submit:active { transform: translateY(0); }

		.back-link { display: block; text-align: center; margin-top: 20px; font-size: 0.85rem; color: #64748b; text-decoration: none; }
		.back-link span { color: #3b82f6; }
		.back-link:hover span { text-decoration: underline; }

		@media (max-width: 768px) {
			.brand-panel { display: none; }
			.form-panel { padding: 40px 28px; }
			.login-card { max-width: 420px; border-radius: 20px; }
		}
	</style>
</head>

<body>
	<div class="login-bg">
		<div class="orb orb-1"></div>
		<div class="orb orb-2"></div>
		<div class="orb orb-3"></div>
	</div>

	<div class="login-wrapper">
		<div class="login-card">

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
				<p>Enter your email address and we'll send you a link to reset your password.</p>
			</div>

			<div class="form-panel">
				<div class="form-header">
					<h2>Forgot password?</h2>
					<p>Enter your admin email and we'll send you a reset link.</p>
				</div>

				<div class="flash-area">
					<?= view('_flash') ?>
				</div>

				<form action="<?= base_url('forgot-password') ?>" method="POST">

					<div class="field-group">
						<input type="email" id="email" name="email" placeholder="Email address"
							value="<?= old('email') ?>" autocomplete="email" required>
						<label for="email">Email address</label>
					</div>

					<button type="submit" class="btn-submit">Send Reset Link</button>

				</form>

				<a href="<?= base_url('login') ?>" class="back-link">
					&larr; Back to <span>Sign In</span>
				</a>
			</div>

		</div>
	</div>

	<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/core.js"></script>
</body>

</html>
