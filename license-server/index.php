<?php
/**
 * License Server Admin Panel
 * Password-protected panel to generate and manage license codes.
 */
session_start();
require __DIR__ . '/config.php';

// ── Auth ──────────────────────────────────────────────────────────────────────

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
    if (password_verify($_POST['admin_password'], ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $loginError = 'Incorrect password.';
}

$loggedIn = !empty($_SESSION['admin']);

// ── Actions (logged-in only) ──────────────────────────────────────────────────

$message     = '';
$messageType = 'success';
$licenses    = [];
$newCode     = '';

if ($loggedIn) {
    try {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        $message     = 'Database error: ' . htmlspecialchars($e->getMessage());
        $messageType = 'danger';
    }

    if (isset($pdo) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $id = (int) ($_POST['license_id'] ?? 0);
        switch ($_POST['action']) {
            case 'generate':
                $newCode = generateCode($pdo);
                $email   = trim($_POST['buyer_email'] ?? '');
                $ref     = trim($_POST['order_ref'] ?? '');
                $pdo->prepare('INSERT INTO licenses (code, buyer_email, order_ref) VALUES (?, ?, ?)')
                    ->execute([$newCode, $email ?: null, $ref ?: null]);
                $message = 'Code generated: <strong><code>' . htmlspecialchars($newCode) . '</code></strong>';
                break;

            case 'revoke':
                $pdo->prepare('UPDATE licenses SET revoked = 1 WHERE id = ?')->execute([$id]);
                $message = 'License revoked.';
                break;

            case 'restore':
                $pdo->prepare('UPDATE licenses SET revoked = 0 WHERE id = ?')->execute([$id]);
                $message = 'License restored.';
                break;

            case 'reset_domain':
                $pdo->prepare('UPDATE licenses SET domain = NULL, activated_at = NULL WHERE id = ?')->execute([$id]);
                $message = 'Domain reset. The buyer can now activate on a new domain.';
                break;

            case 'delete':
                $pdo->prepare('DELETE FROM licenses WHERE id = ?')->execute([$id]);
                $message = 'License deleted permanently.';
                break;
        }
    }

    if (isset($pdo)) {
        $licenses = $pdo->query('SELECT * FROM licenses ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}

function generateCode(PDO $pdo): string
{
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    do {
        $parts = [];
        for ($i = 0; $i < 4; $i++) {
            $part = '';
            for ($j = 0; $j < 4; $j++) {
                $part .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $parts[] = $part;
        }
        $code = implode('-', $parts);
        $exists = $pdo->prepare('SELECT 1 FROM licenses WHERE code = ?');
        $exists->execute([$code]);
    } while ($exists->fetchColumn());

    return $code;
}

function statusBadge(array $lic): string
{
    if ($lic['revoked'])      return '<span class="badge bg-danger">Revoked</span>';
    if (!empty($lic['domain'])) return '<span class="badge bg-success">Active</span>';
    return '<span class="badge bg-secondary">Unused</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: #1a3c6e !important; }
        code { color: #198754; font-size: 1rem; }
    </style>
</head>
<body>

<?php if (!$loggedIn): ?>
<!-- ── Login ── -->
<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow" style="max-width:360px;width:100%">
        <div class="card-body p-4">
            <h5 class="mb-3 fw-bold text-center"><?= htmlspecialchars(APP_NAME) ?></h5>
            <?php if ($loginError): ?>
                <div class="alert alert-danger py-2"><?= $loginError ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Admin Password</label>
                    <input type="password" name="admin_password" class="form-control" required autofocus>
                </div>
                <button class="btn btn-primary w-100" style="background:#1a3c6e;border-color:#1a3c6e">Login</button>
            </form>
        </div>
    </div>
</div>

<?php else: ?>
<!-- ── Admin Panel ── -->
<nav class="navbar navbar-dark px-4">
    <span class="navbar-brand fw-bold"><?= htmlspecialchars(APP_NAME) ?></span>
    <a href="?logout" class="btn btn-outline-light btn-sm">Logout</a>
</nav>

<div class="container-fluid my-4">

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Stats row -->
    <?php
    $total    = count($licenses);
    $active   = count(array_filter($licenses, fn($l) => !$l['revoked'] && !empty($l['domain'])));
    $unused   = count(array_filter($licenses, fn($l) => !$l['revoked'] && empty($l['domain'])));
    $revoked  = count(array_filter($licenses, fn($l) => $l['revoked']));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold"><?= $total ?></div>
                    <div class="text-muted small">Total</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm border-success">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-success"><?= $active ?></div>
                    <div class="text-muted small">Active</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-secondary"><?= $unused ?></div>
                    <div class="text-muted small">Unused</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm border-danger">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-danger"><?= $revoked ?></div>
                    <div class="text-muted small">Revoked</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold">Generate New License Code</div>
        <div class="card-body">
            <form method="POST" class="row g-2 align-items-end">
                <input type="hidden" name="action" value="generate">
                <div class="col-md-4">
                    <label class="form-label small">Buyer Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="buyer_email" class="form-control" placeholder="buyer@example.com">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Order Reference <span class="text-muted">(optional)</span></label>
                    <input type="text" name="order_ref" class="form-control" placeholder="e.g. INV-0042">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success w-100" style="margin-top:1.5rem">+ Generate Code</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Licenses table -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">All Licenses</div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Buyer Email</th>
                        <th>Order Ref</th>
                        <th>Domain</th>
                        <th>Activated</th>
                        <th>Last Verified</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($licenses as $lic): ?>
                    <tr class="<?= $lic['revoked'] ? 'table-danger' : '' ?>">
                        <td>
                            <code id="code-<?= $lic['id'] ?>"><?= htmlspecialchars($lic['code']) ?></code>
                            <button class="btn btn-link btn-sm p-0 ms-1 text-muted" title="Copy"
                                onclick="navigator.clipboard.writeText('<?= htmlspecialchars($lic['code']) ?>').then(()=>this.textContent='Copied!')">
                                &#128203;
                            </button>
                        </td>
                        <td><?= htmlspecialchars($lic['buyer_email'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($lic['order_ref'] ?? '—') ?></td>
                        <td><?= !empty($lic['domain']) ? htmlspecialchars($lic['domain']) : '<span class="text-muted">—</span>' ?></td>
                        <td class="small"><?= $lic['activated_at'] ? date('Y-m-d', strtotime($lic['activated_at'])) : '—' ?></td>
                        <td class="small"><?= $lic['last_verified'] ? date('Y-m-d', strtotime($lic['last_verified'])) : '—' ?></td>
                        <td><?= statusBadge($lic) ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-1 flex-wrap">
                                <input type="hidden" name="license_id" value="<?= $lic['id'] ?>">
                                <?php if (!$lic['revoked']): ?>
                                    <button name="action" value="revoke" class="btn btn-warning btn-sm"
                                        onclick="return confirm('Revoke this license? The buyer will lose access.')">Revoke</button>
                                <?php else: ?>
                                    <button name="action" value="restore" class="btn btn-secondary btn-sm">Restore</button>
                                <?php endif; ?>
                                <?php if (!empty($lic['domain'])): ?>
                                    <button name="action" value="reset_domain" class="btn btn-info btn-sm"
                                        onclick="return confirm('Reset domain? The buyer can activate on a new domain.')">
                                        Reset Domain
                                    </button>
                                <?php endif; ?>
                                <button name="action" value="delete" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Permanently delete this license?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($licenses)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No licenses yet. Generate one above.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php endif; ?>
</body>
</html>
