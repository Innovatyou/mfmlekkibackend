<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate License</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a3c6e 0%, #0d2244 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 480px;
            width: 100%;
        }
        .card-header {
            background: #1a3c6e;
            color: #fff;
            border-radius: 16px 16px 0 0 !important;
            padding: 2rem;
            text-align: center;
        }
        .lock-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
        .code-input {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            text-align: center;
            text-transform: uppercase;
        }
        .btn-activate {
            background: #1a3c6e;
            border-color: #1a3c6e;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
        }
        .btn-activate:hover {
            background: #0d2244;
            border-color: #0d2244;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="lock-icon">&#128274;</div>
            <h4 class="mb-1 fw-bold">License Activation</h4>
            <p class="mb-0 opacity-75 small">Enter the purchase code from your confirmation email to unlock the application.</p>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-start gap-2">
                    <span>&#9888;&#65039;</span>
                    <span><?= esc($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success d-flex align-items-start gap-2">
                    <span>&#9989;</span>
                    <div>
                        <?= esc($success) ?>
                        <br>
                        <a href="<?= base_url('login') ?>" class="fw-semibold">Go to Login &rarr;</a>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('activate/process') ?>" id="activateForm">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Purchase Code</label>
                    <input
                        type="text"
                        name="purchase_code"
                        id="purchase_code"
                        class="form-control form-control-lg code-input"
                        placeholder="XXXX-XXXX-XXXX-XXXX"
                        maxlength="19"
                        autocomplete="off"
                        spellcheck="false"
                        required
                    >
                    <div class="form-text mt-2">
                        Your 16-character code is found in your purchase confirmation email.
                    </div>
                </div>
                <button type="submit" class="btn btn-activate btn-primary w-100" id="submitBtn">
                    Activate License
                </button>
            </form>
        </div>
        <div class="card-footer bg-transparent text-center text-muted small py-3 border-top">
            Having trouble? Contact support with your order details.
        </div>
    </div>

    <script>
        // Auto-format input as XXXX-XXXX-XXXX-XXXX
        const input = document.getElementById('purchase_code');
        input.addEventListener('input', function () {
            let val = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            let formatted = val.match(/.{1,4}/g)?.join('-') ?? val;
            this.value = formatted.substring(0, 19);
        });

        // Show loading state on submit
        document.getElementById('activateForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Verifying...';
        });
    </script>
</body>
</html>
