<?php
  $condLabel = ['new' => 'New', 'used' => 'Used', 'free' => 'Free'][$item->item_condition] ?? $item->item_condition;
  $statusColor = ['active' => '#10b981', 'pending' => '#f59e0b', 'sold' => '#6366f1', 'inactive' => '#94a3b8'][$item->status] ?? '#94a3b8';
?>
<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <div class="page-header">
      <div>
        <h1 class="page-title"><?= esc($item->title) ?></h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="margin:0;padding:0;background:none;font-size:.82rem;">
            <li class="breadcrumb-item"><a href="<?= base_url('marketplaceListing') ?>">Marketplace</a></li>
            <li class="breadcrumb-item active"><?= esc($item->title) ?></li>
          </ol>
        </nav>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="<?= base_url('editMarketplaceItem/' . $item->id) ?>" class="btn btn-outline-secondary">
          <i class="dw dw-edit2" style="margin-right:6px;"></i>Edit
        </a>
        <a href="<?= base_url('approveMarketplaceItem/' . $item->id) ?>" class="btn btn-primary"
           onclick="return confirm('Approve this listing?')">
          <i class="dw dw-check-circle-2" style="margin-right:6px;"></i>Approve
        </a>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

      <!-- Main -->
      <div>

        <!-- Image Gallery + Details -->
        <div class="card-box" style="padding:0;overflow:hidden;margin-bottom:20px;">
          <?php $allPhotos = !empty($photos) ? $photos : ($item->image ? [(object)['filename' => $item->image]] : []); ?>
          <?php if (!empty($allPhotos)): ?>
            <!-- Main photo -->
            <div style="position:relative;background:#000;">
              <img id="mp_main_img"
                   src="<?= base_url('uploads/marketplace/' . $allPhotos[0]->filename) ?>"
                   style="width:100%;max-height:400px;object-fit:contain;display:block;">
            </div>
            <?php if (count($allPhotos) > 1): ?>
              <!-- Thumbnail strip -->
              <div style="display:flex;gap:6px;padding:10px 12px;background:#f8fafc;overflow-x:auto;">
                <?php foreach ($allPhotos as $i => $p): ?>
                  <img src="<?= base_url('uploads/marketplace/' . $p->filename) ?>"
                       onclick="document.getElementById('mp_main_img').src=this.src; document.querySelectorAll('.mp-strip-thumb').forEach(function(t){t.style.borderColor='var(--border)'}); this.style.borderColor='var(--accent)';"
                       class="mp-strip-thumb"
                       style="width:64px;height:64px;object-fit:cover;border-radius:7px;cursor:pointer;flex-shrink:0;
                              border:2px solid <?= $i === 0 ? 'var(--accent)' : 'var(--border)' ?>;">
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div style="height:200px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
              <i class="dw dw-shop" style="font-size:3rem;color:#cbd5e1;"></i>
            </div>
          <?php endif; ?>

          <div style="padding:24px;">
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
              <span class="badge badge-pill" style="background:<?= $statusColor ?>22;color:<?= $statusColor ?>;font-size:.78rem;padding:4px 12px;">
                <?= ucfirst(esc($item->status)) ?>
              </span>
              <?php if ($item->is_free): ?>
                <span class="badge badge-pill badge-success">Free</span>
              <?php endif; ?>
              <?php if ($item->is_featured): ?>
                <span class="badge badge-pill" style="background:#fef3c7;color:#78350f;">
                  <i class="dw dw-star"></i> Featured
                </span>
              <?php endif; ?>
              <?php if ($item->category_name): ?>
                <span class="badge badge-pill badge-secondary"><?= esc($item->category_name) ?></span>
              <?php endif; ?>
              <span class="badge badge-pill" style="background:#e0f2fe;color:#0c4a6e;"><?= esc($condLabel) ?></span>
            </div>

            <div style="font-size:2rem;font-weight:800;color:var(--t1);margin-bottom:8px;">
              <?= $item->is_free ? '<span style="color:#10b981;">Free</span>' : esc($currency_symbol) . number_format((float)$item->price, 2) ?>
            </div>

            <?php if ($item->location): ?>
              <div style="font-size:.875rem;color:var(--t3);margin-bottom:16px;">
                <i class="dw dw-location" style="margin-right:4px;"></i><?= esc($item->location) ?>
              </div>
            <?php endif; ?>

            <hr style="border-color:var(--border);margin:16px 0;">

            <h5 style="font-size:.9rem;font-weight:700;color:var(--t2);margin-bottom:8px;">Description</h5>
            <div style="font-size:.9rem;color:var(--t1);line-height:1.7;white-space:pre-wrap;"><?= esc($item->description ?: 'No description provided.') ?></div>
          </div>
        </div>

        <!-- Inquiries -->
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div>
              <h5 style="margin:0;font-size:.95rem;font-weight:700;color:var(--t1);">Inquiries</h5>
              <p style="margin:2px 0 0;font-size:.8rem;color:var(--t3);"><?= count($inquiries) ?> message<?= count($inquiries) != 1 ? 's' : '' ?> from interested members</p>
            </div>
          </div>
          <?php if (empty($inquiries)): ?>
            <div style="padding:40px;text-align:center;font-size:.875rem;color:var(--t3);">No inquiries yet.</div>
          <?php else: ?>
            <?php foreach ($inquiries as $q): ?>
              <div style="padding:16px 22px;border-bottom:1px solid var(--border);position:relative;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                  <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);
                    display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.78rem;flex-shrink:0;">
                    <?= strtoupper(substr($q->name, 0, 1)) ?>
                  </div>
                  <div>
                    <div style="font-weight:600;font-size:.875rem;color:var(--t1);"><?= esc($q->name) ?></div>
                    <div style="font-size:.78rem;color:var(--t3);"><?= esc($q->email) ?><?= $q->phone ? ' · ' . esc($q->phone) : '' ?></div>
                  </div>
                  <div style="margin-left:auto;font-size:.75rem;color:var(--t3);">
                    <?= date('M j, Y g:i A', strtotime($q->created_at)) ?>
                  </div>
                </div>
                <div style="font-size:.875rem;color:var(--t1);line-height:1.6;margin-left:46px;">
                  <?= nl2br(esc($q->message)) ?>
                </div>
                <a href="<?= base_url('deleteMarketplaceInquiry/' . $q->id) ?>"
                   onclick="return confirm('Delete this inquiry?')"
                   style="position:absolute;right:16px;bottom:14px;font-size:.75rem;color:#ef4444;text-decoration:none;">
                  <i class="dw dw-delete-3"></i> Delete
                </a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>

      <!-- Sidebar -->
      <div>

        <!-- Seller Card -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h5 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0 0 14px;">Seller Information</h5>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <div style="width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,#6366f1,#8b5cf6);
              display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.9rem;flex-shrink:0;">
              <?= strtoupper(substr($item->seller_name, 0, 1)) ?>
            </div>
            <div>
              <div style="font-weight:600;font-size:.9rem;color:var(--t1);"><?= esc($item->seller_name) ?></div>
              <div style="font-size:.78rem;color:var(--t3);"><?= esc($item->seller_email) ?></div>
              <?php if ($item->seller_phone): ?>
                <div style="font-size:.78rem;color:var(--t3);"><?= esc($item->seller_phone) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <div style="font-size:.78rem;color:var(--t3);border-top:1px solid var(--border);padding-top:12px;">
            Listed <?= date('M j, Y', strtotime($item->created_at)) ?> &nbsp;·&nbsp;
            <?= number_format($item->views) ?> view<?= $item->views != 1 ? 's' : '' ?>
          </div>
        </div>

        <!-- Send Inquiry -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h5 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0 0 14px;">Send Inquiry</h5>
          <form action="<?= base_url('submitMarketplaceInquiry') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="item_id" value="<?= $item->id ?>">
            <div class="form-group">
              <input type="text" name="name" class="form-control" placeholder="Your name" required>
            </div>
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Your email" required>
            </div>
            <div class="form-group">
              <input type="text" name="phone" class="form-control" placeholder="Phone (optional)">
            </div>
            <div class="form-group">
              <textarea name="message" class="form-control" rows="4" placeholder="I'm interested in this item…" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Message</button>
          </form>
        </div>

        <!-- Quick Actions -->
        <div class="card-box" style="padding:20px;">
          <h5 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0 0 14px;">Quick Actions</h5>
          <div style="display:flex;flex-direction:column;gap:8px;">
            <a href="<?= base_url('approveMarketplaceItem/' . $item->id) ?>"
               onclick="return confirm('Set listing to Active?')"
               class="btn btn-sm" style="background:#d1fae5;color:#065f46;border:none;text-align:left;">
              <i class="dw dw-check-circle-2" style="margin-right:6px;"></i>Set Active
            </a>
            <a href="<?= base_url('markItemSold/' . $item->id) ?>"
               onclick="return confirm('Mark as sold?')"
               class="btn btn-sm" style="background:#e0f2fe;color:#0c4a6e;border:none;text-align:left;">
              <i class="dw dw-wallet1" style="margin-right:6px;"></i>Mark Sold
            </a>
            <a href="<?= base_url('editMarketplaceItem/' . $item->id) ?>"
               class="btn btn-sm" style="background:#f1f5f9;color:var(--t2);border:none;text-align:left;">
              <i class="dw dw-edit2" style="margin-right:6px;"></i>Edit Listing
            </a>
            <a href="<?= base_url('deleteMarketplaceListing/' . $item->id) ?>"
               onclick="return confirm('Delete this listing permanently?')"
               class="btn btn-sm" style="background:#fee2e2;color:#7f1d1d;border:none;text-align:left;">
              <i class="dw dw-delete-3" style="margin-right:6px;"></i>Delete Listing
            </a>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
