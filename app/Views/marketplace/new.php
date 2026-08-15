<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <div class="page-header">
      <div>
        <h1 class="page-title">New Marketplace Listing</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="margin:0;padding:0;background:none;font-size:.82rem;">
            <li class="breadcrumb-item"><a href="<?= base_url('marketplaceListing') ?>">Marketplace</a></li>
            <li class="breadcrumb-item active">New Listing</li>
          </ol>
        </nav>
      </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('saveNewMarketplaceListing') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="mp-main-grid">

        <!-- Left column -->
        <div>

          <div class="card-box" style="padding:24px;margin-bottom:20px;">
            <h5 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 18px;">Item Details</h5>

            <div class="form-group">
              <label>Title <span style="color:#ef4444;">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="e.g. Like-new bicycle for sale" required>
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="5" placeholder="Describe the item — condition, dimensions, pick-up details…"></textarea>
            </div>

            <div class="mp-field-grid">
              <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                  <option value="">— Select category —</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Condition</label>
                <select name="item_condition" class="form-control">
                  <option value="new">New</option>
                  <option value="used" selected>Used</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Location / Pick-up Area</label>
              <input type="text" name="location" class="form-control" placeholder="e.g. Church foyer, North campus">
            </div>
          </div>

          <div class="card-box" style="padding:24px;margin-bottom:20px;">
            <h5 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 18px;">Pricing</h5>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_free" name="is_free" value="1" onchange="togglePrice(this)">
                <label class="custom-control-label" for="is_free" style="font-size:.9rem;font-weight:600;">This item is FREE</label>
              </div>
            </div>

            <div class="form-group" id="price_field">
              <label>Price (<?= esc($currency_symbol) ?>) <span style="color:#ef4444;">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" style="background:#f1f5f9;border-color:var(--border);font-weight:700;"><?= esc($currency_symbol) ?></span>
                </div>
                <input type="number" name="price" class="form-control" placeholder="0.00" min="0" step="0.01">
              </div>
            </div>
          </div>

        </div>

        <!-- Right column -->
        <div>

          <div class="card-box" style="padding:24px;margin-bottom:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
              <h5 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0;">Product Photos</h5>
              <span id="mp_counter" style="font-size:.78rem;color:var(--t3);">0 / 10</span>
            </div>

            <input type="file" id="product_images" name="photos[]"
                   accept="image/jpeg,image/png,image/webp" multiple style="display:none;">

            <div class="mp-upload-area" id="mp_upload_area">
              <i class="dw dw-gallery"></i>
              <div class="mp-upload-title">Drag &amp; drop product photos here</div>
              <div class="mp-upload-sub">or click to browse &nbsp;·&nbsp; JPG, PNG or WEBP only &nbsp;·&nbsp; max 10 MB each &nbsp;·&nbsp; up to 10 photos</div>
            </div>

            <div id="mp_previews" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;"></div>
          </div>

          <div class="card-box" style="padding:24px;margin-bottom:20px;">
            <h5 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 18px;">Seller Info</h5>

            <div class="form-group">
              <label>Seller Name <span style="color:#ef4444;">*</span></label>
              <input type="text" name="seller_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Seller Email <span style="color:#ef4444;">*</span></label>
              <input type="email" name="seller_email" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Seller Phone</label>
              <input type="text" name="seller_phone" class="form-control">
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">
            <i class="dw dw-add" style="margin-right:6px;"></i>Publish Listing
          </button>
          <a href="<?= base_url('marketplaceListing') ?>" class="btn btn-light btn-block mt-2">Cancel</a>

        </div>
      </div>

    </form>
  </div>
</div>

<style>
  .mp-upload-area {
    border:2px dashed var(--border);border-radius:var(--radius);background:#f8fafc;
    padding:28px 16px;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;
  }
  .mp-upload-area:hover,.mp-upload-area.drag-over { border-color:var(--accent);background:#eef2ff; }
  .mp-upload-area i { font-size:2rem;color:#cbd5e1;margin-bottom:8px;display:block; }
  .mp-upload-area .mp-upload-title { font-size:.875rem;font-weight:600;color:var(--t2); }
  .mp-upload-area .mp-upload-sub   { font-size:.76rem;color:var(--t3);margin-top:4px; }
  .mp-thumb {
    position:relative;width:80px;height:80px;border-radius:8px;overflow:hidden;
    border:2px solid var(--border);flex-shrink:0;
  }
  .mp-thumb img { width:100%;height:100%;object-fit:cover;display:block; }
  .mp-thumb-rm {
    position:absolute;top:3px;right:3px;width:18px;height:18px;border-radius:50%;
    background:rgba(0,0,0,.55);color:#fff;border:none;cursor:pointer;
    font-size:11px;line-height:18px;text-align:center;padding:0;
  }
  .mp-thumb-first::after {
    content:'Cover';position:absolute;bottom:0;left:0;right:0;
    background:rgba(99,102,241,.8);color:#fff;font-size:.6rem;font-weight:700;
    text-align:center;padding:2px 0;
  }
  .mp-main-grid { display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start; }
  .mp-field-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
  @media(max-width:900px) {
    .mp-main-grid { grid-template-columns:1fr; }
  }
  @media(max-width:576px) {
    .mp-field-grid { grid-template-columns:1fr; }
  }
</style>

<script>
(function(){
  var MAX     = 10;
  var allowed = ['image/jpeg','image/png','image/webp'];
  var area    = document.getElementById('mp_upload_area');
  var input   = document.getElementById('product_images');
  var counter = document.getElementById('mp_counter');
  var grid    = document.getElementById('mp_previews');
  var dt      = new DataTransfer();

  if (!area) return;

  area.addEventListener('click', function(){ input.click(); });
  area.addEventListener('dragover',  function(e){ e.preventDefault(); area.classList.add('drag-over'); });
  area.addEventListener('dragleave', function()  { area.classList.remove('drag-over'); });
  area.addEventListener('drop', function(e){
    e.preventDefault(); area.classList.remove('drag-over');
    addFiles(e.dataTransfer.files);
  });
  input.addEventListener('change', function(){ addFiles(this.files); this.value=''; });

  function addFiles(list) {
    for (var i = 0; i < list.length; i++) {
      if (dt.files.length >= MAX) { alert('Maximum 10 photos allowed.'); break; }
      var f = list[i];
      if (!allowed.includes(f.type)) { alert(f.name + ': only JPG, PNG or WEBP images are allowed.'); continue; }
      if (f.size > 10*1024*1024)     { alert(f.name + ': must be under 10 MB.'); continue; }
      dt.items.add(f);
      renderThumb(f, dt.files.length - 1);
    }
    sync();
  }

  function renderThumb(file, idx) {
    var r = new FileReader();
    r.onload = function(e) {
      var wrap = document.createElement('div');
      wrap.className = 'mp-thumb' + (idx === 0 ? ' mp-thumb-first' : '');
      wrap.dataset.idx = idx;
      wrap.innerHTML =
        '<img src="' + e.target.result + '">' +
        '<button type="button" class="mp-thumb-rm" title="Remove">&times;</button>';
      wrap.querySelector('.mp-thumb-rm').addEventListener('click', function(){
        removeAt(parseInt(wrap.dataset.idx));
      });
      grid.appendChild(wrap);
    };
    r.readAsDataURL(file);
  }

  function removeAt(idx) {
    var newDt = new DataTransfer();
    Array.from(dt.files).forEach(function(f, i){ if (i !== idx) newDt.items.add(f); });
    dt = newDt;
    grid.innerHTML = '';
    Array.from(dt.files).forEach(function(f, i){ renderThumb(f, i); });
    sync();
  }

  function sync() {
    input.files = dt.files;
    counter.textContent = dt.files.length + ' / ' + MAX;
    counter.style.color = dt.files.length >= MAX ? '#ef4444' : 'var(--t3)';
  }
})();

function togglePrice(cb) {
  document.getElementById('price_field').style.display = cb.checked ? 'none' : 'block';
}
</script>
