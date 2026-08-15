<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Partnership Payment — <?= esc($settings->churchname) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="<?= base_url() ?>/public/assets/sweetalert/sweetalert.css" rel="stylesheet">
  <script type="text/javascript">var baseURL = "<?= base_url() ?>";</script>
  <style>
    body{background:#f1f5f9;font-family:'Inter',system-ui,sans-serif;}
    .pay-card{background:#fff;border-radius:16px;box-shadow:0 4px 32px rgba(0,0,0,.09);overflow:hidden;}
    .pay-header{padding:28px 32px 20px;border-bottom:1px solid #e2e8f0;}
    .pay-body{padding:28px 32px;}
    .pay-label{display:block;font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;}
    .pay-input{width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:10px 14px;font-size:.9rem;color:#1e293b;outline:none;transition:border-color .15s;background:#fff;}
    .pay-input:focus{border-color:#6366f1;}
    .tier-dot{display:inline-block;width:12px;height:12px;border-radius:50%;}
    .gw-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:10px;font-size:.875rem;font-weight:600;cursor:pointer;border:2px solid transparent;transition:all .15s;width:100%;justify-content:center;margin-bottom:10px;}
    .gw-stripe{background:#6772e5;color:#fff;border-color:#6772e5;}
    .gw-stripe:hover{background:#5469d4;border-color:#5469d4;}
    .gw-paystack{background:#00c3f7;color:#fff;border-color:#00c3f7;}
    .gw-paystack:hover{background:#00a8d4;border-color:#00a8d4;}
    .gw-flutterwave{background:#f5a623;color:#fff;border-color:#f5a623;}
    .gw-flutterwave:hover{background:#e0951a;border-color:#e0951a;}
    .gw-paypal{background:#0070ba;color:#fff;border-color:#0070ba;}
    .gw-paypal:hover{background:#005ea6;border-color:#005ea6;}
    .info-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;}
    .info-row:last-child{border-bottom:none;}
    .badge-tier{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;}
    #stripe-card-wrap{background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:9px;padding:12px 14px;margin-bottom:14px;}
    #pp-container{margin-bottom:10px;}
    .pay-success{display:none;text-align:center;padding:40px 20px;}
    .pay-success .check-icon{font-size:3.5rem;color:#22c55e;margin-bottom:12px;}
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      <!-- Church header -->
      <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-0"><?= esc($settings->churchname) ?></h5>
        <p class="text-muted small mt-1">Partnership Pledge Payment</p>
      </div>

      <div class="pay-card">
        <div class="pay-header">
          <h5 class="fw-bold mb-1" style="color:#1e293b;">Complete Your Pledge</h5>
          <p class="text-muted small mb-0">Make a payment towards your partnership pledge</p>
        </div>

        <div class="pay-body">

          <!-- Success state (shown after payment) -->
          <div class="pay-success" id="paySuccessDiv">
            <div class="check-icon">✓</div>
            <h5 class="fw-bold">Payment Received!</h5>
            <p class="text-muted">Thank you, <strong><?= esc($partnership->partner_name) ?></strong>. Your payment has been recorded.</p>
          </div>

          <!-- Payment form (hidden after payment) -->
          <div id="payFormDiv">

            <!-- Pledge summary -->
            <div class="mb-4 p-3" style="background:#f8fafc;border-radius:12px;">
              <div class="info-row">
                <span class="text-muted">Partner</span>
                <strong style="color:#1e293b;"><?= esc($partnership->partner_name) ?></strong>
              </div>
              <?php if ($partnership->tier_name): ?>
              <div class="info-row">
                <span class="text-muted">Tier</span>
                <span class="badge-tier" style="background:<?= esc($partnership->tier_color) ?>22;color:<?= esc($partnership->tier_color) ?>;border:1px solid <?= esc($partnership->tier_color) ?>44;">
                  <span class="tier-dot" style="background:<?= esc($partnership->tier_color) ?>;"></span>
                  <?= esc($partnership->tier_name) ?>
                </span>
              </div>
              <?php endif; ?>
              <div class="info-row">
                <span class="text-muted">Total Pledge</span>
                <strong><?= esc($partnership->currency) ?> <?= number_format((float)$partnership->pledge_amount, 2) ?></strong>
              </div>
              <div class="info-row">
                <span class="text-muted">Paid So Far</span>
                <span style="color:#059669;font-weight:600;"><?= esc($partnership->currency) ?> <?= number_format((float)$partnership->paid_amount, 2) ?></span>
              </div>
              <div class="info-row">
                <span class="text-muted">Remaining</span>
                <span style="color:<?= $remaining > 0 ? '#ef4444' : '#059669' ?>;font-weight:700;font-size:1rem;">
                  <?= esc($partnership->currency) ?> <?= number_format($remaining, 2) ?>
                </span>
              </div>
            </div>

            <?php if ($remaining <= 0): ?>
            <div class="alert alert-success text-center">
              <strong>Pledge Fulfilled!</strong> Your full pledge has been paid. Thank you!
            </div>
            <?php else: ?>

            <!-- Gateway warnings -->
            <?php if (strpos($settings->prefered_gateway, 'stripe') !== false && empty($settings->stripe_public)): ?>
            <div class="alert alert-warning small py-2">Stripe public key is not configured.</div>
            <?php endif; ?>
            <?php if (strpos($settings->prefered_gateway, 'paystack') !== false && empty($settings->paystack_api_key)): ?>
            <div class="alert alert-warning small py-2">Paystack API key is not configured.</div>
            <?php endif; ?>
            <?php if (strpos($settings->prefered_gateway, 'flutterwaves') !== false && empty($settings->flutterwaves_api_key)): ?>
            <div class="alert alert-warning small py-2">Flutterwave API key is not configured.</div>
            <?php endif; ?>
            <?php if (strpos($settings->prefered_gateway, 'paypal') !== false && empty($settings->paypal_client)): ?>
            <div class="alert alert-warning small py-2">PayPal client ID is not configured.</div>
            <?php endif; ?>

            <!-- Amount field -->
            <div class="mb-3">
              <label class="pay-label">Payment Amount (<?= esc($partnership->currency) ?>)</label>
              <input type="number" id="pay_amount" class="pay-input" placeholder="0.00" min="0.01" step="0.01"
                value="<?= number_format($remaining, 2, '.', '') ?>">
            </div>

            <p id="pay_error" style="display:none;color:#ef4444;font-size:.82rem;margin-bottom:10px;"></p>

            <!-- Stripe -->
            <?php if (strpos($settings->prefered_gateway, 'stripe') !== false && !empty($settings->stripe_public)): ?>
            <div class="mb-2">
              <div id="stripe-card-wrap"><div id="stripe-card-element"></div></div>
              <button class="gw-btn gw-stripe" id="stripe-pay-btn" onclick="payWithStripe()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M2 7h20v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7zm0-2a2 2 0 012-2h16a2 2 0 012 2H2z" fill="currentColor" opacity=".4"/><path d="M2 9h20v2H2z" fill="currentColor"/></svg>
                Pay with Stripe
              </button>
            </div>
            <?php endif; ?>

            <!-- Paystack -->
            <?php if (strpos($settings->prefered_gateway, 'paystack') !== false && !empty($settings->paystack_api_key)): ?>
            <button class="gw-btn gw-paystack" onclick="payWithPaystack()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M9 12h6M9 8h6M9 16h4" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>
              Pay with Paystack
            </button>
            <?php endif; ?>

            <!-- Flutterwave -->
            <?php if (strpos($settings->prefered_gateway, 'flutterwaves') !== false && !empty($settings->flutterwaves_api_key)): ?>
            <button class="gw-btn gw-flutterwave" onclick="payWithFlutterwave()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" opacity=".4"/><path d="M15 8l-4 8-3-4"/></svg>
              Pay with Flutterwave
            </button>
            <?php endif; ?>

            <!-- PayPal -->
            <?php if (strpos($settings->prefered_gateway, 'paypal') !== false && !empty($settings->paypal_client)): ?>
            <div id="pp-container" class="mt-2"></div>
            <?php endif; ?>

            <?php endif; // remaining > 0 ?>
          </div><!-- /payFormDiv -->

        </div>
      </div>

      <p class="text-center text-muted small mt-3">
        <a href="<?= base_url() ?>" style="color:#94a3b8;text-decoration:none;">← Back to <?= esc($settings->churchname) ?></a>
      </p>

    </div>
  </div>
</div>

<?php if (strpos($settings->prefered_gateway, 'paypal') !== false && !empty($settings->paypal_client)): ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?= esc($settings->paypal_client) ?>" crossorigin="anonymous"></script>
<?php endif; ?>
<?php if (strpos($settings->prefered_gateway, 'stripe') !== false && !empty($settings->stripe_public)): ?>
<script src="https://js.stripe.com/v3/"></script>
<?php endif; ?>
<?php if (strpos($settings->prefered_gateway, 'paystack') !== false && !empty($settings->paystack_api_key)): ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<?php endif; ?>
<?php if (strpos($settings->prefered_gateway, 'flutterwaves') !== false && !empty($settings->flutterwaves_api_key)): ?>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="<?= base_url() ?>/public/assets/sweetalert/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="<?= base_url() ?>/public/assets/js/ajax.js"></script>

<script>
var partnershipId = <?= (int)$partnership->id ?>;
var partnerEmail  = '<?= esc($partnership->partner_email) ?>';
var partnerName   = '<?= esc(addslashes($partnership->partner_name)) ?>';
var currency      = '<?= esc($partnership->currency) ?>';
var remaining     = <?= (float)$remaining ?>;

function getAmount() {
  var v = parseFloat(document.getElementById('pay_amount').value);
  return isNaN(v) || v <= 0 ? 0 : v;
}

function showError(msg) {
  var el = document.getElementById('pay_error');
  el.textContent = msg;
  el.style.display = 'block';
}

function clearError() {
  document.getElementById('pay_error').style.display = 'none';
}

function validateAmount() {
  var a = getAmount();
  if (a <= 0) { showError('Please enter a valid payment amount.'); return false; }
  clearError();
  return true;
}

function showSuccess() {
  document.getElementById('payFormDiv').style.display = 'none';
  document.getElementById('paySuccessDiv').style.display = 'block';
}

function sendPaymentToServer(reference, type, amount) {
  $.LoadingOverlay('show');
  var fd = new FormData();
  fd.append('data', JSON.stringify({
    partnership_id: partnershipId,
    amount: amount,
    reference: reference,
    type: type,
  }));
  makeAjaxCall(baseURL + '/savePartnershipPayment', 'POST', fd).then(function(res) {
    $.LoadingOverlay('hide');
    var r = typeof res === 'string' ? JSON.parse(res) : res;
    if (r.status === 'ok') {
      showSuccess();
    } else {
      swal({ title: 'Error', text: r.message || 'Payment could not be recorded.', type: 'error' });
    }
  }, function(err) {
    $.LoadingOverlay('hide');
    swal({ title: 'Error', text: 'Could not connect to server. Please try again.', type: 'error' });
  });
}

// ── Stripe ────────────────────────────────────────────────────────────────
<?php if (strpos($settings->prefered_gateway, 'stripe') !== false && !empty($settings->stripe_public)): ?>
var stripe  = Stripe('<?= esc($settings->stripe_public) ?>');
var elements = stripe.elements();
var cardEl   = elements.create('card', { style: { base: { fontSize: '15px', color: '#1e293b' } } });
cardEl.mount('#stripe-card-element');

function payWithStripe() {
  if (!validateAmount()) return;
  var amount = getAmount();
  var btn = document.getElementById('stripe-pay-btn');
  btn.disabled = true;
  stripe.createToken(cardEl).then(function(result) {
    if (result.error) {
      btn.disabled = false;
      showError(result.error.message);
      return;
    }
    $.LoadingOverlay('show');
    var fd = new FormData();
    fd.append('data', JSON.stringify({
      partnership_id: partnershipId,
      amount: amount,
      token: result.token.id,
    }));
    makeAjaxCall(baseURL + '/stripe/partnership-charge', 'POST', fd).then(function(res) {
      $.LoadingOverlay('hide');
      btn.disabled = false;
      var r = typeof res === 'string' ? JSON.parse(res) : res;
      if (r.status === 'ok') {
        showSuccess();
      } else {
        swal({ title: 'Payment Failed', text: r.message, type: 'error' });
      }
    }, function() {
      $.LoadingOverlay('hide');
      btn.disabled = false;
      swal({ title: 'Error', text: 'Network error. Please try again.', type: 'error' });
    });
  });
}
<?php endif; ?>

// ── Paystack ──────────────────────────────────────────────────────────────
<?php if (strpos($settings->prefered_gateway, 'paystack') !== false && !empty($settings->paystack_api_key)): ?>
function payWithPaystack() {
  if (!validateAmount()) return;
  var amount = getAmount();
  var handler = PaystackPop.setup({
    key: '<?= esc($settings->paystack_api_key) ?>',
    email: partnerEmail || 'partner@church.org',
    currency: currency,
    amount: Math.round(amount * 100),
    firstname: partnerName,
    onClose: function() {},
    callback: function(response) {
      if (response.message === 'Approved') {
        sendPaymentToServer(response.trxref, 'paystack', amount);
      }
    }
  });
  handler.openIframe();
}
<?php endif; ?>

// ── Flutterwave ───────────────────────────────────────────────────────────
<?php if (strpos($settings->prefered_gateway, 'flutterwaves') !== false && !empty($settings->flutterwaves_api_key)): ?>
function payWithFlutterwave() {
  if (!validateAmount()) return;
  var amount = getAmount();
  var ref = 'PSHIP_' + partnershipId + '_' + Math.floor(Math.random() * 1e9);
  FlutterwaveCheckout({
    public_key: '<?= esc($settings->flutterwaves_api_key) ?>',
    tx_ref: ref,
    amount: amount,
    currency: currency,
    customer: { email: partnerEmail || 'partner@church.org', name: partnerName },
    callback: function(data) {
      if (data.status === 'successful' || data.status === 'completed') {
        sendPaymentToServer(data.flw_ref, 'flutterwaves', amount);
      }
    },
    onclose: function() {},
  });
}
<?php endif; ?>

// ── PayPal ────────────────────────────────────────────────────────────────
<?php if (strpos($settings->prefered_gateway, 'paypal') !== false && !empty($settings->paypal_client)): ?>
paypal.Buttons({
  createOrder: function(data, actions) {
    var amount = getAmount();
    if (amount <= 0) { showError('Enter a valid amount first.'); return Promise.reject(); }
    return actions.order.create({ purchase_units: [{ amount: { value: amount.toFixed(2) } }] });
  },
  onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
      sendPaymentToServer(details.id, 'paypal', getAmount());
    });
  },
  onError: function(err) {
    swal({ title: 'PayPal Error', text: 'Payment could not be completed.', type: 'error' });
  }
}).render('#pp-container');
<?php endif; ?>
</script>
</body>
</html>
