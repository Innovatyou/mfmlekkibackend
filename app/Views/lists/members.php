<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['email_sms_list_members'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('lists') ?>"><?= $locale['email_sms_list'] ?></a><span>/</span><span><?= esc($list->title) ?></span></nav>
      </div>
      <a href="<?= base_url('addMemberstoList/' . $list->id) ?>" class="btn btn-primary btn-sm" style="border-radius:8px;font-weight:600;">
        <i class="dw dw-add" style="margin-right:4px;"></i><?= $locale['add_members'] ?>
      </a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="lt-card">
      <div style="overflow-x:auto;">
        <table id="categories-table" class="table lt-table table-hover exportable">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $locale['email'] ?></th>
              <th><?= $locale['name'] ?></th>
              <th><?= $locale['date'] ?></th>
              <th><?= $locale['action'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; foreach ($members as $record): ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $count ?></td>
              <td><?= esc($record->email) ?></td>
              <td style="font-weight:500;"><?= esc($record->name) ?></td>
              <td style="color:var(--t3);font-size:.82rem;"><?= esc($record->date) ?></td>
              <td>
                <button onclick="delete_item(event)" data-type="listmember" data-id="<?= $record->id ?>" data-list="<?= $list->id ?>"
                        style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:.78rem;font-weight:600;background:#fee2e2;color:#991b1b;border:none;cursor:pointer;">
                  <i class="dw dw-delete-3"></i> Remove
                </button>
              </td>
            </tr>
            <?php $count++; endforeach; ?>
            <?php if(empty($members)): ?>
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--t3);">No members yet</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
