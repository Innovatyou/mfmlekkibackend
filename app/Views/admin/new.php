<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Admin Users</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Admin User</li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <?= view('_flash') ?>

        <form method="POST" action="<?php echo base_url(); ?>/savenewadmin" style="margin-top:30px;">

          <div class="form-group">
            <label>Full Name</label>

            <div class="form-line">
              <input type="text" class="form-control" name="name" placeholder="Full Name" required="" autofocus="">
            </div>
          </div>
          <div class="form-group">
            <label>Email Address</label>

            <div class="form-line">
              <input type="email" class="form-control" name="email" placeholder="Email Address" required="" autofocus="">
            </div>
          </div>
          <div class="form-group">
            <label>Password</label>

            <div class="form-line">
              <input type="password" class="form-control" name="password" placeholder="Password" required="" autocomplete="new-password">
            </div>
          </div>
          <div class="box-footer text-center">
            <button class="btn btn-primary waves-effect" type="submit">SAVE NEW</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>