<?php $session = session(); ?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <div class="row" style="padding:20px;">
      <div class="col-md-6 col-sm-12">
        <div class="title">
          <h2 class="h3 mb-0"><?php echo $churchname; ?> <?php echo $locale['overview']; ?></h2>
        </div>
      </div>


    </div>

    <div class="row pb-10">
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $branches; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['all_church_locations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-house-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $members; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['total_members']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-user1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $groups; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['total_groups']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon"><i class="icon-copy dw dw-group"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donations; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['total_donations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="row pb-10">
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthisweek . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['weekly_donations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthismonth . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['monthly_donations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthisyear . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['yearly_donations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $alldonations . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500"><?php echo $locale['total_donations']; ?></div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-box pb-10">
      <div class="h5 pd-20 mb-0"><?php echo $locale['recent_donations']; ?></div>
      <table class="data-table table nowrap">
        <thead>
          <tr>
            <th>#</th>
            <th><?php echo $locale['reason']; ?></th>
            <th><?php echo $locale['email']; ?></th>
            <th><?php echo $locale['name']; ?></th>
            <th><?php echo $locale['reference']; ?></th>
            <th><?php echo $locale['amount']; ?></th>
            <th><?php echo $locale['method']; ?></th>
            <th><?php echo $locale['date']; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1;
          foreach ($recentdonations as $res) { ?>
            <tr>
              <td><?php echo $count; ?></td>
              <td><?php echo $res->reason; ?></td>
              <td><?php echo $res->email; ?></td>
              <td><?php echo $res->name; ?></td>
              <td><?php echo $res->reference; ?></td>
              <td><?php echo $res->amount; ?></td>
              <td><?php echo $res->method; ?></td>
              <td><?php echo $res->date; ?></td>
            </tr>
          <?php $count++;
          } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>