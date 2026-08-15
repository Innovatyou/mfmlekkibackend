<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['books_literatures'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('books') ?>"><?= $locale['books_literatures'] ?></a><span>/</span><span><?= $locale['edit_book'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('editBookData') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $book->id ?>">
      <div class="row">
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_cover'] ?></h3><p class="nf-card-sub">Click to change cover</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="book-cover-zone" style="min-height:160px;display:flex;flex-direction:column;align-items:center;justify-content:center;" onclick="document.getElementById('book-cover-input').click()">
                <?php if(!empty($book->thumbnail)):?>
                <img src="<?=esc($book->thumbnail)?>" style="height:140px;object-fit:cover;border-radius:8px;" alt="Cover">
                <?php else:?>
                <div class="nf-upload-icon"><i class="dw dw-image"></i></div>
                <p class="nf-upload-text">Upload cover</p>
                <p class="nf-upload-hint">PNG or JPG</p>
                <?php endif;?>
              </div>
              <input type="file" id="book-cover-input" name="thumbnail" accept="image/png,image/jpeg" style="display:none;" onchange="nfPreview(this,'book-cover-zone')">
            </div>
          </div>
          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_pdf'] ?></h3><p class="nf-card-sub">Click to replace PDF</p></div>
            <div class="nf-card-body" style="padding-top:8px;">
              <div class="nf-upload-zone" id="book-pdf-zone" onclick="document.getElementById('book-pdf-input').click()">
                <div class="nf-upload-icon"><i class="dw dw-file-1"></i></div>
                <p class="nf-upload-text" id="book-pdf-name"><?= !empty($book->book) ? basename($book->book) : 'Upload PDF file' ?></p>
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
                  <input type="text" name="title" class="nf-input" value="<?= esc($book->title) ?>" required>
                </div>
                <div class="nf-col-third">
                  <label class="nf-label"><?= $locale['num_pages'] ?></label>
                  <input type="number" name="pages" class="nf-input" value="<?= esc($book->pages) ?>" min="1" required>
                </div>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['book_writer'] ?></label>
                <input type="text" name="author" class="nf-input" value="<?= esc($book->author) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['book_desc'] ?></label>
                <textarea name="description" rows="5" class="nf-input" style="resize:vertical;"><?= esc($book->description) ?></textarea>
              </div>
            </div>
          </div>

          <div class="nf-card" style="margin-top:16px;">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['book_pricing'] ?></h3><p class="nf-card-sub">Set a price or offer the book for free</p></div>
            <div class="nf-card-body">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <label class="nf-sale-toggle" for="is_for_sale_chk">
                  <input type="checkbox" id="is_for_sale_chk" name="is_for_sale" value="1" <?= !empty($book->is_for_sale) ? 'checked' : '' ?> onchange="nfToggleSale(this)">
                  <span class="nf-toggle-track"><span class="nf-toggle-thumb"></span></span>
                </label>
                <span style="font-size:.875rem;font-weight:600;color:var(--t1);"><?= $locale['sell_book'] ?></span>
              </div>
              <div id="sale-fields" style="display:<?= !empty($book->is_for_sale) ? 'block' : 'none' ?>;">
                <div class="nf-row">
                  <div class="nf-col-two-thirds">
                    <label class="nf-label"><?= $locale['book_price'] ?></label>
                    <input type="number" name="price" id="book_price" class="nf-input" placeholder="0.00" min="0" step="0.01" value="<?= esc($book->price ?? '0.00') ?>" <?= !empty($book->is_for_sale) ? 'required' : '' ?>>
                  </div>
                  <div class="nf-col-third">
                    <label class="nf-label"><?= $locale['book_currency'] ?></label>
                    <select name="currency" class="nf-input">
                      <?php foreach(['USD','EUR','GBP','NGN','GHS','KES','ZAR','CAD','AUD'] as $cur): ?>
                      <option value="<?= $cur ?>" <?= (isset($book->currency) && $book->currency === $cur) ? 'selected' : '' ?>><?= $cur ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['update_book'] ?></button>
            <a href="<?= base_url('books') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.nf-sale-toggle{display:inline-flex;align-items:center;cursor:pointer;}
.nf-sale-toggle input{position:absolute;opacity:0;width:0;height:0;}
.nf-toggle-track{display:inline-block;width:40px;height:22px;background:#d1d5db;border-radius:999px;position:relative;transition:background .2s;}
.nf-sale-toggle input:checked~.nf-toggle-track{background:var(--accent);}
.nf-toggle-thumb{position:absolute;top:3px;left:3px;width:16px;height:16px;background:#fff;border-radius:50%;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.nf-sale-toggle input:checked~.nf-toggle-track .nf-toggle-thumb{left:21px;}
</style>
<script>
function nfPreview(input,zoneId){var z=document.getElementById(zoneId);if(input.files&&input.files[0]){var r=new FileReader();r.onload=function(e){z.innerHTML='<img src="'+e.target.result+'" style="height:140px;object-fit:cover;border-radius:8px;" alt="Preview">';};r.readAsDataURL(input.files[0]);}}
function nfPickPdf(input){if(input.files&&input.files[0]){var f=input.files[0];document.getElementById('book-pdf-name').textContent=f.name+' ('+(f.size/1024).toFixed(0)+' KB)';document.getElementById('book-pdf-zone').style.borderColor='#6366f1';}}
function nfToggleSale(chk){document.getElementById('sale-fields').style.display=chk.checked?'block':'none';var p=document.getElementById('book_price');if(chk.checked){p.setAttribute('required','required');}else{p.removeAttribute('required');}}
</script>
