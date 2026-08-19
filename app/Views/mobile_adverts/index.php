<div class="main-container"><div class="pd-ltr-20"><div class="card-box pd-20 mb-30">
  <h4 class="mb-20">Mobile App Adverts</h4>
  <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
  <form method="post" action="<?= base_url('mobileAdverts/store') ?>" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-4"><label>Banner Image</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" required><small>Portrait 4:5 image recommended. JPG, PNG or WEBP.</small></div>
      <div class="col-md-3"><label>Title (optional)</label><input class="form-control" name="title" maxlength="160"></div>
      <div class="col-md-3"><label>Click Link (optional)</label><input class="form-control" type="url" name="link" placeholder="https://example.com"></div>
      <div class="col-md-1"><label>Order</label><input class="form-control" type="number" name="sort_order" value="0"></div>
      <div class="col-md-1"><label>Status</label><select class="form-control" name="active"><option value="1">Live</option><option value="0">Hidden</option></select></div>
    </div><button class="btn btn-primary mt-20" type="submit">Add Advert</button>
  </form>
</div>
<div class="card-box pd-20"><div class="row">
<?php foreach ($adverts as $advert): ?><div class="col-md-4 mb-20"><div style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;">
  <img src="<?= esc($advert->image) ?>" alt="<?= esc($advert->title) ?>" style="width:100%;aspect-ratio:16/7;object-fit:cover;">
  <div style="padding:14px;"><strong><?= esc($advert->title ?: 'Untitled advert') ?></strong><div class="text-muted" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($advert->link ?: 'No link') ?></div>
  <div class="mt-10"><a class="btn btn-sm <?= $advert->active ? 'btn-warning' : 'btn-success' ?>" href="<?= base_url('mobileAdverts/toggle/' . $advert->id) ?>"><?= $advert->active ? 'Hide' : 'Show' ?></a> <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this advert?')" href="<?= base_url('mobileAdverts/delete/' . $advert->id) ?>">Delete</a></div></div>
</div></div><?php endforeach; ?>
<?php if (!$adverts): ?><div class="col-12 text-center text-muted pd-20">No adverts yet.</div><?php endif; ?>
</div></div></div></div>
