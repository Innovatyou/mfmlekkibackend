<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['donations'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['donations'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="lt-card">
      <div style="overflow-x:auto;">
        <table id="donations_table" class="table lt-table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th><?= $locale['reason'] ?></th>
              <th><?= $locale['email'] ?></th>
              <th><?= $locale['name'] ?></th>
              <th><?= $locale['reference'] ?></th>
              <th><?= $locale['amount'] ?></th>
              <th><?= $locale['method'] ?></th>
              <th><?= $locale['date'] ?></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
