<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title">Membership Form</h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>/dashboard">Dashboard</a><span>/</span><a href="<?= base_url('landingContent') ?>">Website</a><span>/</span><span>Membership Form</span></nav>
      </div>
      <a href="<?= base_url('newMembershipField') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i>New Field</a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>

    <div class="lt-alert" style="background:#eef2ff;color:#3730a3;border:1px solid #c7d2fe;">
      <i class="dw dw-information"></i>
      This is the "Become a Member" form shown on the public website. The seven core fields (grey badge) power your Members list and can't be removed, but you can edit their labels, help text and whether they're required. Add as many custom fields as you like below them.
    </div>

    <div class="card-box" style="padding:0;overflow:hidden;">
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table class="table nowrap" style="width:100%;" id="mf_table">
          <thead><tr>
            <th style="width:70px;">Order</th><th>Label</th><th>Field Key</th><th>Type</th><th>Required</th><th>Kind</th><th>Status</th><th style="width:90px;">Action</th>
          </tr></thead>
          <tbody>
            <?php $c=1; $total = count($fields); foreach($fields as $r): ?>
            <tr>
              <td>
                <div style="display:flex;gap:4px;">
                  <a href="<?=base_url('moveMembershipFieldUp/'.$r->id)?>" class="mf-order-btn <?= $c==1?'mf-disabled':'' ?>" title="Move up"><i class="dw dw-up-chevron"></i></a>
                  <a href="<?=base_url('moveMembershipFieldDown/'.$r->id)?>" class="mf-order-btn <?= $c==$total?'mf-disabled':'' ?>" title="Move down"><i class="dw dw-down-chevron"></i></a>
                </div>
              </td>
              <td><span style="font-weight:600;color:var(--t1);"><?=esc($r->label)?></span></td>
              <td><code style="font-size:.78rem;color:var(--t3);"><?=esc($r->field_key)?></code></td>
              <td><span class="badge badge-pill badge-secondary"><?=esc($r->field_type)?></span></td>
              <td><?= $r->required ? '<i class="dw dw-check-circle-2" style="color:#10b981;"></i>' : '<span style="color:var(--t3);">—</span>' ?></td>
              <td><?= $r->is_core ? '<span class="badge badge-pill" style="background:#e2e8f0;color:#475569;">Core</span>' : '<span class="badge badge-pill" style="background:#eef2ff;color:#4338ca;">Custom</span>' ?></td>
              <td><?php if($r->status=='active'):?><span class="badge badge-pill badge-success">Active</span><?php else:?><span class="badge badge-pill badge-secondary">Inactive</span><?php endif;?></td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?=base_url('editMembershipField/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <?php if(!$r->is_core):?>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="mfDelConfirm(<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                  <?php endif;?>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.lt-ab{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;}
.lt-edit{background:#f59e0b;}.lt-del{background:#ef4444;}
.mf-order-btn{width:26px;height:26px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#f1f5f9;color:var(--t2);text-decoration:none;}
.mf-order-btn:hover{background:#e2e8f0;}
.mf-disabled{opacity:.35;pointer-events:none;}
#mf_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#mf_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#mf_table tbody tr:hover td{background:#f8fafc}
</style>
<script>
function mfDelConfirm(id){swal({title:'Delete this field?',text:'Any previously submitted answers for it are kept, but it will disappear from the public form.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){document.location.href=baseURL+'/deleteMembershipField/'+id;});}
</script>
