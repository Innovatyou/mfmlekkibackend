<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['books_literatures'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('books') ?>"><?= $locale['books_literatures'] ?></a><span>/</span><span><?= $locale['add_new'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('saveNewBook') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_cover'] ?></h3><p class="nf-card-sub">Book cover image</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone nf-book-cover" id="book-cover-zone" onclick="document.getElementById('book-cover-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-image"></i></div>
                <p class="nf-upload-text">Upload cover</p>
                <p class="nf-upload-hint">PNG or JPG</p>
              </div>
              <input type="file" id="book-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreviewBook(this)">
            </div>
          </div>
          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_pdf'] ?></h3><p class="nf-card-sub">Book PDF file</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="book-pdf-zone" onclick="document.getElementById('book-pdf-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-file-1"></i></div>
                <p class="nf-upload-text" id="book-pdf-name">Upload PDF file</p>
                <p class="nf-upload-hint">PDF format only</p>
              </div>
              <input type="file" id="book-pdf-input" name="book" accept="application/pdf" style="display:none;" onchange="nfPickPdf(this)">
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Book Information</h3></div>
            <div class="nf-card-body">
              <div class="nf-row" style="margin-bottom:16px;">
                <div class="nf-col-two-thirds">
                  <label class="nf-label"><?= $locale['book_title'] ?></label>
                  <input type="text" name="title" class="nf-input" placeholder="<?= $locale['book_title'] ?>" required>
                </div>
                <div class="nf-col-third">
                  <label class="nf-label"><?= $locale['num_pages'] ?></label>
                  <input type="number" name="pages" class="nf-input" placeholder="e.g. 120" min="1" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['book_writer'] ?></label>
                <input type="text" name="author" class="nf-input" placeholder="<?= $locale['book_writer'] ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['book_desc'] ?></label>
                <textarea name="description" rows="5" class="nf-input" style="resize:vertical;" placeholder="Brief description of the book…"></textarea>
              </div>
            </div>
          </div>

          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_pricing'] ?></h3><p class="nf-card-sub">Set a price or offer the book for free</p></div>
            <div class="nf-card-body">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <label class="nf-sale-toggle" for="is_for_sale_chk">
                  <input type="checkbox" id="is_for_sale_chk" name="is_for_sale" value="1" onchange="nfToggleSale(this)">
                  <span class="nf-toggle-track"><span class="nf-toggle-thumb"></span></span>
                </label>
                <span style="font-size:.875rem;font-weight:600;color:var(--t1);"><?= $locale['sell_book'] ?></span>
              </div>
              <div id="sale-fields" style="display:none;">
                <div class="nf-row">
                  <div class="nf-col-two-thirds">
                    <label class="nf-label"><?= $locale['book_price'] ?></label>
                    <input type="number" name="price" id="book_price" class="nf-input" placeholder="0.00" min="0" step="0.01">
                  </div>
                  <div class="nf-col-third">
                    <label class="nf-label"><?= $locale['book_currency'] ?></label>
                    <select name="currency" class="nf-input">
                      <option value="USD">USD</option>
                      <option value="EUR">EUR</option>
                      <option value="GBP">GBP</option>
                      <option value="NGN">NGN</option>
                      <option value="GHS">GHS</option>
                      <option value="KES">KES</option>
                      <option value="ZAR">ZAR</option>
                      <option value="CAD">CAD</option>
                      <option value="AUD">AUD</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['save_new'] ?></button>
            <a href="<?= base_url('books') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<style>
.nf-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden}
.nf-card-head{padding:16px 20px 0;}.nf-card-title{font-size:.95rem;font-weight:700;color:var(--t1);margin:0 0 2px;}
.nf-card-sub{font-size:.78rem;color:var(--t3);margin:0 0 16px;}
.nf-card-body{padding:16px 20px 20px;}
.nf-label{display:block;font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.nf-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:.875rem;color:var(--t1);outline:none;transition:border-color .15s;}
.nf-input:focus{border-color:var(--accent);}
.nf-row{display:flex;gap:16px;}.nf-col-half{flex:1;min-width:0;}
.nf-col-two-thirds{flex:2;min-width:0;}.nf-col-third{flex:1;min-width:0;}
.nf-upload-zone{border:2px dashed var(--border);border-radius:10px;padding:24px 16px;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;}
.nf-upload-zone:hover{border-color:var(--accent);background:#f8f7ff;}
.nf-book-cover{min-height:160px;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.nf-upload-icon{font-size:2rem;color:var(--t3);margin-bottom:8px;}
.nf-upload-text{font-size:.85rem;font-weight:600;color:var(--t2);margin:0 0 4px;}
.nf-upload-hint{font-size:.75rem;color:var(--t3);margin:0;}
.nf-submit{padding:10px 28px;font-weight:600;border-radius:9px;}
.nf-cancel{padding:10px 20px;font-weight:600;border-radius:9px;color:var(--t2);}
.nf-sale-toggle{display:inline-flex;align-items:center;cursor:pointer;}
.nf-sale-toggle input{position:absolute;opacity:0;width:0;height:0;}
.nf-toggle-track{display:inline-block;width:40px;height:22px;background:#d1d5db;border-radius:999px;position:relative;transition:background .2s;}
.nf-sale-toggle input:checked~.nf-toggle-track{background:var(--accent);}
.nf-toggle-thumb{position:absolute;top:3px;left:3px;width:16px;height:16px;background:#fff;border-radius:50%;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.nf-sale-toggle input:checked~.nf-toggle-track .nf-toggle-thumb{left:21px;}
.lt-bc{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--t3);margin-top:2px;}
.lt-bc a{color:var(--t3);text-decoration:none;}.lt-bc a:hover{color:var(--accent);}
.lt-bc span{color:var(--t3);}
.lt-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:.875rem;font-weight:500;}
.lt-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.lt-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.lt-x{margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;opacity:.6;line-height:1;}
</style>
<script>
function nfToggleSale(chk) {
  document.getElementById('sale-fields').style.display = chk.checked ? 'block' : 'none';
  var p = document.getElementById('book_price');
  if (chk.checked) { p.setAttribute('required','required'); } else { p.removeAttribute('required'); }
}
function nfPreviewBook(input) {
  var zone = document.getElementById('book-cover-zone');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      zone.innerHTML = '<img src="'+e.target.result+'" style="height:160px;object-fit:cover;border-radius:8px;" alt="Cover">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function nfPickPdf(input) {
  if (input.files && input.files[0]) {
    var f = input.files[0];
    var kb = (f.size/1024).toFixed(0);
    document.getElementById('book-pdf-name').textContent = f.name + ' ('+kb+' KB)';
    document.getElementById('book-pdf-zone').style.borderColor = '#6366f1';
  }
}
</script>
