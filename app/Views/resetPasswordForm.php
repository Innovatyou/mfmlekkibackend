<?php
$session = session();
?>
<html>

<head>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
</head>
<style>
  body {
    background: #f7f7f7;
  }

  .form-box {
    max-width: 500px;
    margin: auto;
    padding: 50px;
    background: #ffffff;
    border: 10px solid #f2f2f2;
  }

  h1,
  p {
    text-align: center;
  }

  input,
  textarea {
    width: 100%;
  }
</style>

<body>
  <div class="form-box">
    <h1>Reset Your Password</h1>
    <form method="POST" action="<?php echo base_url(); ?>/changeUserPassword">
      <input type="hidden" name="email" required value="<?php echo $email; ?>">
      <input type="hidden" name="activation_id" value="<?php echo $activation_id; ?>">
      <div class="form-group">
        <label for="password1"><?php echo $locale['password']; ?></label>
        <input class="form-control" id="password1" type="password" name="password1" required>
      </div>
      <div class="form-group">
        <label for="password2"><?php echo $locale['repeat_password']; ?></label>
        <input class="form-control" id="password2" type="password" name="password2" required>
      </div>
        <?= view('_flash') ?>
      <input class="btn btn-primary" type="submit" value="Submit" />
  </div>
  </form>
  </div>
</body>

</html>