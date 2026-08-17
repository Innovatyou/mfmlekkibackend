<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donations</title>

  <link rel="canonical" href="index.html">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <!-- Favicons -->
  <link rel="apple-touch-icon" href="assets/img/favicons/apple-touch-icon.png" sizes="180x180">
  <link href="<?php echo base_url(); ?>/public/assets/sweetalert/sweetalert.css" rel="stylesheet">
  <meta name="theme-color" content="#7952b3">


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
  </script>
</head>

<body class="bg-light">

  <div class="container">
    <main>
        <div class="py-5 text-center">
        <h2>Donate to </h2>
        <h2 class="lead"><?php echo $settings->churchname; ?></h2>
        <div class="card mb-3 mt-3">
          <div class="card-body text-start">
            <h5 class="card-title">PAY YOUR TITHES &amp; OFFERING</h5>
            <p class="mb-1"><strong>Bank Name:</strong> BANK NAME</p>
            <p class="mb-1"><strong>Account Number:</strong> 10000000000</p>
            <p class="mb-0"><strong>Account Name:</strong> Church Account Name</p>
            <hr />

          </div>
        </div>
        <?php if (strpos($settings->prefered_gateway, "flutterwaves") == true && $settings->flutterwaves_api_key == "") { ?>
          <div class="alert alert-danger" role="alert">
            Flutterwaves Api key is missing! You can add this on the settings page of the admin dashboard.
          </div>
        <?php } ?>
        <?php if (strpos($settings->prefered_gateway, "paystack") == true && $settings->paystack_api_key == "") { ?>
          <div class="alert alert-danger" role="alert">
            Paystack Api key is missing! You can add this on the settings page of the admin dashboard.
          </div>
        <?php } ?>
        <?php if (strpos($settings->prefered_gateway, "paypal") == true && $settings->paypal_client == "") { ?>
          <div class="alert alert-danger" role="alert">
            Paypal Client ID Api key is missing! You can add this on the settings page of the admin dashboard.
          </div>
        <?php } ?>
        <?php if (strpos($settings->prefered_gateway, "stripe") == true && $settings->stripe_public == "") { ?>
          <div class="alert alert-danger" role="alert">
            Stripe Public key is missing! You can add this on the settings page of the admin dashboard.
          </div>
        <?php } ?>
        <?php if (strpos($settings->prefered_gateway, "stripe") == true && $settings->stripe_secret == "") { ?>
          <div class="alert alert-danger" role="alert">
            Stripe Secret key is missing!
          </div>
        <?php } ?>
      </div>

      <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">

          <ul class="list-group mb-3">
            <img src="<?php echo $settings->donationslogo == "" ? base_url() . "/public/assets/src/images/donations.jpg" : $settings->donationslogo; ?>" />
          </ul>
        </div>
        <div class=" col-md-7 col-lg-8">
          <form class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email-address" name="email" placeholder="" value="">
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full-name" name="fullname" placeholder="" value="">
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="fullname" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="" value="">
                <div class="invalid-feedback">
                  Amount is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="country" class="form-label">Reason for Donation</label>
                <select class="form-select" id="reason" name="reason" required>
                  <option value="offering" selected>Offering</option>
                  <option value="tithe">Tithe</option>
                  <option value="project support">Project Support</option>
                  <option value="others">Others</option>
                </select>
                <div class="invalid-feedback">
                  Please select a valid donation reason.
                </div>
              </div>

              <hr class="my-4">

              <h6 class="mb-3">Proceed with</h6>

              <div <?php if (strpos($settings->prefered_gateway, "flutterwaves") == false && strpos($settings->prefered_gateway, "paystack") == false) { ?> style="display:none;" <?php } ?>>
                <div class="my-3">
                  <input type="radio" name="options2" class="btn-check btn-check-outlined" value="flutterwaves" id="flutterwaves" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "flutterwaves") == false) { ?> style="display:none;" <?php } ?>>
                  <label class="btn btn-outline-primary" for="flutterwaves" id="flutterwaveslabel" <?php if (strpos($settings->prefered_gateway, "flutterwaves") == false) { ?> style="display:none;" <?php } ?>>FlutterWaves</label>
                  <input type="radio" name="options2" class="btn-check btn-check-outlined" value="paystack" id="Paystack" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "paystack") == false) { ?> style="display:none;" <?php } ?>>
                  <label class="btn btn-outline-primary" for="Paystack" id="paystacklabel" <?php if (strpos($settings->prefered_gateway, "paystack") == false) { ?> style="display:none;" <?php } ?>> Paystack</label>
                </div>
                <hr class="my-4">

                <h6 class="mb-3">or Donate with</h6>
              </div>

              <div class="my-3">
                <input type="radio" name="options" class="btn-check btn-check-outlined" id="Paypal" value="paypal" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "paypal") !== false) echo "checked"; ?> <?php if (strpos($settings->prefered_gateway, "paypal") == false) { ?> style="display:none;" <?php } ?>>
                <label class="btn btn-outline-primary" for="Paypal" <?php if (strpos($settings->prefered_gateway, "paypal") == false) { ?> style="display:none;" <?php } ?>>Paypal</label>
                <input type="radio" name="options" class="btn-check btn-check-outlined" value="stripe" id="stripemode" autocomplete="off" <?php if (strpos($settings->prefered_gateway, "paypal") == false && strpos($settings->prefered_gateway, "stripe") !== false) echo "checked"; ?> <?php if (strpos($settings->prefered_gateway, "stripe") == false) { ?> style="display:none;" <?php } ?>>
                <label class="btn btn-outline-primary" for="stripemode" <?php if (strpos($settings->prefered_gateway, "stripe") == false) { ?> style="display:none;" <?php } ?>>Stripe</label>
              </div>

              <hr class="my-4">
              <p id="error" style="display: none; color: red;">Fill in the donation details in the form above to continue</p>
              <div id="paypal-button-container" <?php if (strpos($settings->prefered_gateway, "paypal") == false) { ?> style="display:none;" <?php } ?>></div>
              <div id="stripe-div" <?php if (strpos($settings->prefered_gateway, "paypal") == false && strpos($settings->prefered_gateway, "stripe") !== false) { ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?>>
                <form id='checkout-form' method='post' action="<?php echo base_url('/stripe/create-charge'); ?>">
                  <input type='hidden' name='stripeToken' id='stripe-token-id'>
                  <div id="card-element" class="form-control form-group"></div>
                  <button id='pay-btn' class="btn btn-success mt-3" type="button" style="margin-top: 20px; width: 100%;padding: 7px;" onclick="createToken()">Donate with stripe
                  </button>
                  <form>
              </div>


          </form>
        </div>
      </div>
    </main>


  </div>
  <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $settings->paypal_client; ?>" crossorigin="anonymous"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://js.paystack.co/v1/inline.js"></script>
  <script src="https://checkout.flutterwave.com/v3.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="<?php echo base_url(); ?>/public/assets/sweetalert/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
  <script src="<?php echo base_url(); ?>/public/assets/js/ajax.js"></script>
  <script>
    var currency = '<?php echo $settings->currency_code; ?>';
    var prefered_gateway = '<?php echo $settings->prefered_gateway; ?>';
    var email = 'c@ggmail.com';
    var name = 'testuser';
    var amountpayable = 100;
    var selected = "usd";
    var reason = "hello world";
    function validatedetails() {
      reason = document.getElementById("reason").value;
      amountpayable = document.getElementById("amount").value;
      email = document.getElementById("email-address").value;
      name = document.getElementById("full-name").value;

      if (email == "") {
        swal("ooops", "Please add your email address to proceed", "error");
        return false;
      }
      if (name == "") {
        swal("ooops", "Please add your full name to proceed", "error");
        return false;
      }
      if (amount <= 0) {
        swal("ooops", "Please add amount to proceed", "error");
        return false;
      }
      return true;
    }

    if (prefered_gateway.indexOf("paypal") == -1 && prefered_gateway.indexOf("stripe") == -1) {
      selected = "local";
    }

    var radioButtons = document.querySelectorAll('input[name="options"]');
    for (const radioButton of radioButtons) {
      radioButton.addEventListener('change', showSelected);
    }
    var radioButtons2 = document.querySelectorAll('input[name="options2"]');
    for (const radioButton of radioButtons2) {
      radioButton.addEventListener('change', showSelected2);
    }

    function showSelected(e) {
      console.log(e);
      if (this.checked) {
        switch (this.value) {
          case "paypal":
            selected = "usd";
            document.getElementById('stripe-div').style.display = "none";
            document.getElementById('paypal-button-container').style.display = "block";
            break;
          case "stripe":
            selected = "usd";
            document.getElementById('paypal-button-container').style.display = "none";
            document.getElementById('stripe-div').style.display = "block";
            break;
          default:
        }
      }
    }

    function showSelected2(e) {

      if (validatedetails() == false) {
        this.checked = !this.checked;
        return;
      }
      console.log(e);
      if (this.checked) {
        switch (this.value) {
          case "paystack":
            selected = "local";
            payWithPaystack();
            this.checked = !this.checked;
            break;
          case "flutterwaves":
            selected = "local";
            makePaymentwithFlutterwaves();
            this.checked = !this.checked;
            break;
          default:
        }
      }
    }
    var stripepublickey = "<?php echo $settings->stripe_public; ?>";
    var stripe = Stripe(stripepublickey == "" ? "NOT-SET" : stripepublickey);
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    function createToken() {
      if (document.getElementById("amount").value == 0 ||
        document.getElementById("email-address").value == "" ||
        document.getElementById("full-name").value == "") {
        document.querySelector("#error").style.display = "block";
        return;
      } else {
        document.querySelector("#error").style.display = "none";
      }
      console.log(document.getElementById('checkout-form'));
      document.getElementById("pay-btn").disabled = true;
      stripe.createToken(cardElement).then(function(result) {
        console.log(result);
        if (typeof result.error != 'undefined') {
          document.getElementById("pay-btn").disabled = false;
          //alert(result.error.message);
          swal({
            text: result.error.message,
          });
        }

        // creating token success
        if (typeof result.token != 'undefined') {
          //console.log(document.getElementById('checkout-form'));
          //document.getElementById('checkout-form').submit();
          $.LoadingOverlay("show");
          var form_obj = JSON.stringify({
            token: result.token.id,
            email: email,
            name: name,
            reason: reason,
            amount: amountpayable,
          });
          //console.log(form_obj); return;
          var fd = new FormData();
          fd.append("data", form_obj);
          makeAjaxCall(baseURL + "/stripe/create-donation-charge", "POST", fd).then(function(response) {
            $.LoadingOverlay("hide");
            document.getElementById("pay-btn").disabled = false;
            console.log(response);
            window.location.href = baseURL + "/thank_you";

          }, function(status) {
            $.LoadingOverlay("hide");
            document.getElementById("pay-btn").disabled = false;
            //console.log("failed with status", status);
            //window.location.href = baseURL+"/thank_you";
            swal({
              text: status,
            });
          });
        }
      });
    }
    /*,*/

    paypal.Buttons({
      onInit(data, actions) {
        // Disable the buttons
        actions.disable();
        var shouldenable = false;
        $('input#amount').on('input', (e) => {
          if (document.getElementById("amount").value == 0 ||
            document.getElementById("email-address").value == "" ||
            document.getElementById("full-name").value == "") {
            actions.disable();
          } else {
            actions.enable();
          }
        });
        $('input#email-address').on('input', (e) => {
          if (document.getElementById("amount").value == 0 ||
            document.getElementById("email-address").value == "" ||
            document.getElementById("full-name").value == "") {
            actions.disable();
          } else {
            actions.enable();
          }
        });
        $('input#full-name').on('input', (e) => {
          if (document.getElementById("amount").value == 0 ||
            document.getElementById("email-address").value == "" ||
            document.getElementById("full-name").value == "") {
            actions.disable();
          } else {
            actions.enable();
          }
        });
        // Listen for changes to the checkbox
        /* document.querySelector("#check").addEventListener("change", function(event) {
           // Enable or disable the button when it is checked or unchecked
           if (event.target.checked) {
             actions.enable();
           } else {
             actions.disable();
           }
         });*/
      },

      // onClick is called when the button is selected
      onClick() {
        if (document.getElementById("amount").value == 0 ||
          document.getElementById("email-address").value == "" ||
          document.getElementById("full-name").value == "") {
          document.querySelector("#error").style.display = "block";
        } else {
          document.querySelector("#error").style.display = "none";
        }
      },
      createOrder: function(data, actions) {
        // This function sets up the details of the transaction, including the amount and line item details.
        console.log(data);
        console.log(actions);
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: document.getElementById("amount").value
            }
          }]
        });
      },
      onApprove: function(data, actions) {
        // This function captures the funds from the transaction.
        return actions.order.capture().then(function(details) {
          //console.log(details);
          // This function shows a transaction success message to your buyer.
          sendpaymenttoserver(details.id, "paypal");
        });
      }
    }).render('#paypal-button-container');


    function payWithPaystack() {
      var paystackkey = "<?php echo $settings->paystack_api_key; ?>";
      console.log(paystackkey);
      let handler = PaystackPop.setup({
        key: paystackkey,
        email: email,
        currency: currency,
        amount: amountpayable * 100,
        firstname: name,
        onClose: function() {

        },
        callback: function(response) {
          console.log(response);
          //senddonationtoserver(branch, email,firstname+" "+lastname,amount,response.trxref,"Paystack",reason);
          if (response.message == "Approved") {
            sendpaymenttoserver(response.trxref, "paystack");
          }
        }
      });
      handler.openIframe();
    }

    //FlutterWaves
    function makePaymentwithFlutterwaves() {
      var ref = "FLWSECK_" + Math.floor((Math.random() * 1000000000) + 1);
      var flutterwavespublickey = "<?php echo $settings->flutterwaves_api_key; ?>";
      console.log(flutterwavespublickey);
      FlutterwaveCheckout({
        public_key: flutterwavespublickey,
        tx_ref: ref,
        amount: amountpayable,
        currency: currency,
        //payment_options: "card, mobilemoneyghana, ussd",
        //redirect_url: baseURL+"thank_you",
        customer: {
          email: email,
          name: name,
        },
        callback: function(data) {
          console.log(data);
          //senddonationtoserver(branch,email,firstname+" "+lastname,amount,ref,"FlutterWave",reason);
          if (data.status == "successful" || data.status == "completed") {
            sendpaymenttoserver(data.flw_ref, "flutterwaves");
          }
        },
        onclose: function() {
          // close modal
        },
      });
    }

    function sendpaymenttoserver(reference, type) {
      $.LoadingOverlay("show");
      var form_obj = JSON.stringify({
        name: name,
        email: email,
        reason: reason,
        reference: reference,
        amount: amountpayable,
        type: type,
      });
      //console.log(form_obj); return;
      var fd = new FormData();
      fd.append("data", form_obj);
      makeAjaxCall(baseURL + "/savedonation", "POST", fd).then(function(response) {
        $.LoadingOverlay("hide");
        window.location.href = baseURL + "/thank_you";
        /*swal({
          title: 'Success!',
          text: response.message,
          type: "success"
        }, function() {
          // window.history.back();
        });*/
      }, function(status) {
        $.LoadingOverlay("hide");
        //console.log("failed with status", status);
        //window.location.href = baseURL+"/thank_you";
        swal({
          title: 'Failed!',
          text: "Subscription was not succesful",
          type: "error"
        });
      });
    }
  </script>
</body>

</html>