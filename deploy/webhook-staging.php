<?php
/**
 * GitHub webhook receiver — deploys to staging on push to $branch.
 * See AUTO_DEPLOY_SETUP.md for setup.
 *
 * Deliberately self-contained (no require of another repo file): this
 * script gets copied out to the document root as deploy-hook.php, and
 * runs BEFORE the repo clone it manages is necessarily on the right
 * branch — a fresh clone can default to main, which doesn't even have
 * a deploy/ folder. A require pointed at the clone would then fail
 * before ever reaching the git fetch/reset that fixes that. Keeping
 * everything in one file sidesteps that bootstrap ordering problem
 * entirely. The tradeoff: a future change to this deploy logic needs
 * re-pasting into the server copy, same as editing the config values.
 */

$configPath = getenv('MFMADMIN_STAGING_DEPLOY_CONFIG') ?: '/home/mfmlbbcm/church-deploy-config.php';

if (!is_file($configPath)) {
    http_response_code(500);
    exit('Deploy config not found. See AUTO_DEPLOY_SETUP.md.');
}
require $configPath;
// Expects $secret, $repoDir, $deployDir, $branch, and optionally
// $runMigrations (bool, default true) to be defined by the config file.
$runMigrations = $runMigrations ?? true;

function respond(int $code, string $msg): never
{
    http_response_code($code);
    echo $msg;
    exit;
}

$payload = file_get_contents('php://input');
$signatureHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (!$signatureHeader || !hash_equals(
    'sha256=' . hash_hmac('sha256', $payload, $secret),
    $signatureHeader
)) {
    respond(401, 'Invalid signature');
}

$data = json_decode($payload, true);
if (!isset($data['ref']) || $data['ref'] !== "refs/heads/$branch") {
    respond(200, "Ignored (not a push to $branch)");
}

$logFile = __DIR__ . '/deploy.log';
$log = fopen($logFile, 'a');
fwrite($log, "\n==== Deploy triggered " . date('c') . " (target: $deployDir) ====\n");

// Pull into a separate clone first, then rsync into the live document
// root — never git-reset the live directory directly. Once a target has
// its own real Database.php / App.php / firebase.json / .env in place,
// those stay excluded here so a deploy never clobbers them.
$commands = [
    'cd ' . escapeshellarg($repoDir) . ' && git fetch origin ' . escapeshellarg($branch)
        . ' && git reset --hard origin/' . escapeshellarg($branch),
    // vendor/ is deliberately excluded and never touched by this sync:
    // composer isn't reachable via exec() on this host under any of the
    // usual paths (confirmed directly — plain `composer`, composer.phar
    // at /usr/local/bin, /opt/cpanel/composer/bin, and $HOME all 127/not
    // found), so there's no way to rebuild it automatically here. It's
    // uploaded once by hand (built locally, zipped, extracted via File
    // Manager) and only needs re-uploading when composer.lock actually
    // changes — most deploys are code-only and don't touch it.
    'rsync -a --delete '
        . "--exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' --exclude='vendor' "
        . "--exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' "
        . "--exclude='deploy-hook.php' --exclude='deploy.log' --exclude='test.php' --exclude='error_log' "
        . escapeshellarg(rtrim($repoDir, '/') . '/') . ' ' . escapeshellarg(rtrim($deployDir, '/') . '/'),
    // rsync -a preserves the source file's mode bits as they exist in the
    // git checkout, which have occasionally come through non-world-readable
    // and made LiteSpeed unable to open .htaccess at all (seen as a site-wide
    // 500 on every request, including plain static files). Force it back to
    // a known-good, world-readable mode on every deploy so this can't regress.
    'chmod 644 ' . escapeshellarg(rtrim($deployDir, '/') . '/.htaccess'),
];
if ($runMigrations) {
    $commands[] = 'cd ' . escapeshellarg($deployDir) . ' && php spark migrate --no-interaction';
}
$commands[] = 'rm -rf ' . escapeshellarg(rtrim($deployDir, '/') . '/writable/cache') . '/*';

foreach ($commands as $cmd) {
    fwrite($log, "\$ $cmd\n");
    exec($cmd . ' 2>&1', $output, $exitCode);
    fwrite($log, implode("\n", $output) . "\n");
    if ($exitCode !== 0) {
        fwrite($log, "FAILED (exit $exitCode), stopping.\n");
        fclose($log);
        respond(500, "Deploy failed at: $cmd");
    }
    $output = [];
}

fwrite($log, "Deploy succeeded.\n");
fclose($log);
respond(200, 'Deployed successfully');
