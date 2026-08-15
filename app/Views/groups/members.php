<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['group_members'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('groups') ?>"><?= $locale['groups'] ?></a><span>/</span><span><?= esc($group->title) ?></span></nav>
      </div>
      <a href="<?= base_url('addMemberstoGroup/' . $group->id) ?>" class="btn btn-primary btn-sm" style="border-radius:8px;font-weight:600;">
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
              <th><?= $locale['status'] ?></th>
              <th><?= $locale['date'] ?></th>
              <th><?= $locale['action'] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; foreach ($members as $record):
              $approved = $record->status == 0 ? 1 : 0; ?>
            <tr>
              <td style="color:var(--t3);font-weight:600;"><?= $count ?></td>
              <td><?= esc($record->email) ?></td>
              <td style="font-weight:500;"><?= esc($record->name) ?></td>
              <td>
                <?php if($record->status == 0): ?>
                <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#dcfce7;color:#166534;">Approved</span>
                <?php else: ?>
                <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#fef9c3;color:#854d0e;">Pending</span>
                <?php endif; ?>
              </td>
              <td style="color:var(--t3);font-size:.82rem;"><?= esc($record->date) ?></td>
              <td>
                <div style="display:flex;gap:6px;">
                  <a href="<?= base_url('editGroupMemberStatus/' . $record->id . '/' . $approved) ?>"
                     style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:.78rem;font-weight:600;background:<?= $record->status==1?'#dcfce7':'#fee2e2'?>;color:<?= $record->status==1?'#166534':'#991b1b'?>;text-decoration:none;">
                    <?= $record->status==1 ? 'Approve' : 'Revoke' ?>
                  </a>
                  <button onclick="delete_item(event)" data-type="groupmember" data-id="<?= $record->id ?>" data-list="<?= $group->id ?>"
                          style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:.78rem;font-weight:600;background:#fee2e2;color:#991b1b;border:none;cursor:pointer;">
                    <i class="dw dw-delete-3"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php $count++; endforeach; ?>
            <?php if(empty($members)): ?>
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--t3);">No members yet</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
