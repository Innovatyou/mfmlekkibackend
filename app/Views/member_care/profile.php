<?php
$typeIcon  = ['call'=>'dw-phone','visit'=>'dw-home','email'=>'dw-email','prayer'=>'dw-open-book','message'=>'dw-chat','other'=>'dw-heart'];
$typeColor = ['call'=>'#6366f1','visit'=>'#10b981','email'=>'#f59e0b','prayer'=>'#8b5cf6','message'=>'#06b6d4','other'=>'#ec4899'];
$gradeColor = ['high'=>'#10b981','medium'=>'#f59e0b','low'=>'#f97316','none'=>'#ef4444'];
$gradeLabel = ['high'=>'Active','medium'=>'Moderate','low'=>'Low','none'=>'Inactive'];

$score  = (int) $engagement['score'];
$grade  = $engagement['grade'];
$flags  = $engagement['flags'];
$init   = strtoupper(substr($member->firstname, 0, 1) . substr($member->lastname, 0, 1));
?>

<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Care Profile</h1>
        <nav class="cp-breadcrumb">
          <a href="<?= base_url() ?>">Dashboard</a><span>/</span>
          <a href="<?= base_url('memberCare') ?>">Member Care</a><span>/</span>
          <span><?= esc($member->firstname . ' ' . $member->lastname) ?></span>
        </nav>
      </div>
      <div style="display:flex;gap:10px;">
        <a href="<?= base_url('memberCare') ?>" class="btn btn-secondary">
          <i class="dw dw-left-arrow" style="margin-right:6px;"></i>Back
        </a>
        <a href="<?= base_url('editMember/' . $member->id) ?>" class="btn btn-primary">
          <i class="dw dw-edit-2" style="margin-right:6px;"></i>Edit Member
        </a>
      </div>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="cp-alert cp-alert-success">
        <i class="dw dw-check-circle-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button class="cp-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="cp-alert cp-alert-danger">
        <i class="dw dw-close-circle-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button class="cp-alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;">

      <!-- ── Left Column: Member info + Engagement ── -->
      <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- Member Card -->
        <div class="card-box" style="padding:24px;text-align:center;">
          <div style="width:72px;height:72px;border-radius:18px;margin:0 auto 14px;
            display:flex;align-items:center;justify-content:center;color:#fff;
            font-size:1.6rem;font-weight:800;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);">
            <?php if ($member->thumbnail): ?>
              <img src="<?= esc($member->thumbnail) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
            <?php else: ?>
              <?= $init ?>
            <?php endif; ?>
          </div>
          <h2 style="font-size:1.1rem;font-weight:800;color:var(--t1);margin:0 0 4px;">
            <?= esc($member->firstname . ' ' . $member->lastname) ?>
          </h2>
          <p style="font-size:.83rem;color:var(--t3);margin:0 0 16px;"><?= esc($member->email) ?></p>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;text-align:left;">
            <?php if ($member->phonenumber): ?>
            <div class="cp-info-cell">
              <span class="cp-info-label">Phone</span>
              <span class="cp-info-val"><?= esc($member->phonenumber) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($member->gender): ?>
            <div class="cp-info-cell">
              <span class="cp-info-label">Gender</span>
              <span class="cp-info-val"><?= esc($member->gender) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($member->dob): ?>
            <div class="cp-info-cell">
              <span class="cp-info-label">Birthday</span>
              <span class="cp-info-val"><?= date('M j, Y', strtotime($member->dob)) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($member->occupation): ?>
            <div class="cp-info-cell">
              <span class="cp-info-label">Occupation</span>
              <span class="cp-info-val"><?= esc($member->occupation) ?></span>
            </div>
            <?php endif; ?>
          </div>

          <?php if ($member->address): ?>
          <div style="margin-top:12px;padding:10px;background:#f8fafc;border-radius:8px;font-size:.82rem;color:var(--t2);text-align:left;">
            <i class="dw dw-location" style="color:var(--t3);margin-right:4px;"></i><?= esc($member->address) ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Engagement Score -->
        <div class="card-box" style="padding:20px;">
          <h4 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0 0 16px;">Engagement Score</h4>

          <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
            <div style="width:64px;height:64px;border-radius:50%;flex-shrink:0;
              background:conic-gradient(<?= $gradeColor[$grade] ?? '#ef4444' ?> <?= $score * 3.6 ?>deg, #f1f5f9 0);
              display:flex;align-items:center;justify-content:center;position:relative;">
              <div style="width:48px;height:48px;border-radius:50%;background:#fff;
                display:flex;align-items:center;justify-content:center;
                font-size:1.1rem;font-weight:800;color:<?= $gradeColor[$grade] ?? '#ef4444' ?>;">
                <?= $score ?>
              </div>
            </div>
            <div>
              <div style="font-size:1rem;font-weight:700;color:var(--t1);">
                <?= $gradeLabel[$grade] ?? 'Inactive' ?>
              </div>
              <div style="font-size:.78rem;color:var(--t3);">out of 100 points</div>
            </div>
          </div>

          <!-- Flag badges -->
          <div style="display:flex;flex-wrap:wrap;gap:6px;">
            <?php if (in_array('in_group', $flags)): ?>
              <span class="cp-flag cp-flag-green"><i class="dw dw-group" style="font-size:.72rem;"></i> Group member</span>
            <?php endif; ?>
            <?php if (in_array('donor', $flags)): ?>
              <span class="cp-flag cp-flag-blue"><i class="dw dw-wallet1" style="font-size:.72rem;"></i> Donor</span>
            <?php endif; ?>
            <?php if (in_array('prayer', $flags)): ?>
              <span class="cp-flag cp-flag-purple"><i class="dw dw-open-book" style="font-size:.72rem;"></i> Prayer request</span>
            <?php endif; ?>
            <?php if (in_array('testimony', $flags)): ?>
              <span class="cp-flag cp-flag-teal"><i class="dw dw-star" style="font-size:.72rem;"></i> Testimony</span>
            <?php endif; ?>
            <?php if (in_array('cared', $flags)): ?>
              <span class="cp-flag cp-flag-pink"><i class="dw dw-heart" style="font-size:.72rem;"></i> Care logged</span>
            <?php endif; ?>
            <?php if (empty($flags)): ?>
              <span style="font-size:.8rem;color:var(--t3);">No engagement signals recorded yet.</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Log Care Event -->
        <div class="card-box" style="padding:20px;">
          <h4 style="font-size:.9rem;font-weight:700;color:var(--t1);margin:0 0 16px;">
            <i class="dw dw-add" style="color:var(--accent);margin-right:6px;"></i>Log Care Interaction
          </h4>
          <form action="<?= base_url('logCareEvent') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="member_id" value="<?= $member->id ?>">
            <div style="margin-bottom:12px;">
              <label style="display:block;margin-bottom:5px;">Interaction Type</label>
              <select name="event_type" class="form-control" required style="font-size:.875rem;">
                <option value="call">📞 Phone Call</option>
                <option value="visit">🏠 Home Visit</option>
                <option value="email">📧 Email</option>
                <option value="prayer">🙏 Prayer</option>
                <option value="message">💬 Message</option>
                <option value="other">❤️ Other</option>
              </select>
            </div>
            <div style="margin-bottom:14px;">
              <label style="display:block;margin-bottom:5px;">Notes <span style="color:var(--t3);font-weight:400;">(optional)</span></label>
              <textarea name="note" class="form-control" rows="3" style="resize:vertical;font-size:.875rem;" placeholder="Brief notes about the interaction…"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">
              <i class="dw dw-check" style="margin-right:6px;"></i>Log Interaction
            </button>
          </form>
        </div>

      </div>

      <!-- ── Right Column: History + Notes ── -->
      <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- Care History -->
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border);">
            <div>
              <h3 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0;">Care History</h3>
              <p style="font-size:.76rem;color:var(--t3);margin:1px 0 0;"><?= count($care_history) ?> interaction<?= count($care_history) !== 1 ? 's' : '' ?> logged</p>
            </div>
          </div>

          <?php if (empty($care_history)): ?>
            <div style="padding:36px;text-align:center;color:var(--t3);font-size:.88rem;">
              No care interactions logged yet.<br>
              <span style="font-size:.8rem;">Use the form on the left to log your first interaction.</span>
            </div>
          <?php else: ?>
            <div style="max-height:400px;overflow-y:auto;">
              <?php foreach ($care_history as $e): ?>
                <?php
                  $t    = $e->event_type ?: 'other';
                  $icon = $typeIcon[$t] ?? 'dw-heart';
                  $col  = $typeColor[$t] ?? '#6366f1';
                  $label = ['call'=>'Phone Call','visit'=>'Home Visit','email'=>'Email','prayer'=>'Prayer','message'=>'Message','other'=>'Other'];
                ?>
                <div style="display:flex;align-items:flex-start;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);position:relative;">
                  <div style="width:36px;height:36px;border-radius:9px;background:<?= $col ?>1a;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <i class="dw <?= $icon ?>" style="color:<?= $col ?>;font-size:.95rem;"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                      <span style="font-size:.88rem;font-weight:700;color:var(--t1);"><?= $label[$t] ?? ucfirst($t) ?></span>
                      <span style="font-size:.72rem;color:var(--t3);margin-left:auto;"><?= date('M j, Y · g:i a', strtotime($e->created_at)) ?></span>
                    </div>
                    <?php if ($e->note): ?>
                      <p style="font-size:.84rem;color:var(--t2);margin:0 0 4px;line-height:1.5;"><?= nl2br(esc($e->note)) ?></p>
                    <?php endif; ?>
                    <span style="font-size:.74rem;color:var(--t3);">by <?= esc($e->created_by) ?></span>
                  </div>
                  <a href="javascript:void(0)" onclick="confirmDeleteEvent(<?= $e->id ?>)"
                    style="color:#ef4444;font-size:.8rem;opacity:.5;transition:opacity .15s;flex-shrink:0;padding:2px 4px;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.5'" title="Delete">
                    <i class="dw dw-delete-3"></i>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Pastoral Notes -->
        <div class="card-box" style="padding:0;overflow:hidden;">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border);">
            <div>
              <h3 style="font-size:.95rem;font-weight:700;color:var(--t1);margin:0;">Pastoral Notes</h3>
              <p style="font-size:.76rem;color:var(--t3);margin:1px 0 0;"><?= count($notes) ?> note<?= count($notes) !== 1 ? 's' : '' ?></p>
            </div>
            <button onclick="document.getElementById('note-form').classList.toggle('cp-hidden')"
              class="btn btn-primary" style="padding:6px 14px;font-size:.82rem;">
              <i class="dw dw-add" style="margin-right:4px;"></i>Add Note
            </button>
          </div>

          <!-- Note form (hidden by default) -->
          <div id="note-form" class="cp-hidden" style="padding:16px 20px;border-bottom:1px solid var(--border);background:#f8fafc;">
            <form action="<?= base_url('addCareNote') ?>" method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="member_id" value="<?= $member->id ?>">
              <div style="margin-bottom:10px;">
                <textarea name="note" class="form-control" rows="3" required
                  style="resize:vertical;font-size:.875rem;"
                  placeholder="Write pastoral note here…"></textarea>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <label style="display:flex;align-items:center;gap:6px;font-size:.82rem;color:var(--t2);font-weight:500;cursor:pointer;">
                  <input type="checkbox" name="is_private" value="1" style="margin:0;">
                  Private note (not visible to other staff)
                </label>
                <button type="submit" class="btn btn-primary" style="padding:7px 18px;font-size:.83rem;">Save Note</button>
              </div>
            </form>
          </div>

          <?php if (empty($notes)): ?>
            <div style="padding:36px;text-align:center;color:var(--t3);font-size:.88rem;">
              No pastoral notes yet.<br>
              <span style="font-size:.8rem;">Use "Add Note" above to create the first note.</span>
            </div>
          <?php else: ?>
            <div style="max-height:400px;overflow-y:auto;">
              <?php foreach ($notes as $n): ?>
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
                  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">
                    <p style="font-size:.875rem;color:var(--t1);margin:0 0 6px;line-height:1.55;flex:1;"><?= nl2br(esc($n->note)) ?></p>
                    <a href="javascript:void(0)" onclick="confirmDeleteNote(<?= $n->id ?>)"
                      style="color:#ef4444;font-size:.8rem;opacity:.5;transition:opacity .15s;flex-shrink:0;"
                      onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.5'" title="Delete">
                      <i class="dw dw-delete-3"></i>
                    </a>
                  </div>
                  <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:.74rem;color:var(--t3);">by <?= esc($n->created_by) ?> · <?= date('M j, Y', strtotime($n->created_at)) ?></span>
                    <?php if ($n->is_private): ?>
                      <span style="font-size:.68rem;background:#fef3c7;color:#78350f;padding:1px 7px;border-radius:10px;font-weight:600;">Private</span>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>

  </div>
</div>

<style>
  .cp-breadcrumb { font-size:.8rem;color:var(--t3);margin-top:3px; }
  .cp-breadcrumb a { color:var(--t3);text-decoration:none; }
  .cp-breadcrumb a:hover { color:var(--accent); }
  .cp-breadcrumb span { margin:0 5px; }

  .cp-alert {
    display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius);
    margin-bottom:16px;font-size:.875rem;font-weight:500;position:relative;
  }
  .cp-alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
  .cp-alert-danger  { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
  .cp-alert-close { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;cursor:pointer;color:inherit;opacity:.6;padding:0; }

  .cp-info-cell { display:flex;flex-direction:column; }
  .cp-info-label { font-size:.7rem;color:var(--t3);text-transform:uppercase;letter-spacing:.04em;font-weight:700; }
  .cp-info-val   { font-size:.83rem;color:var(--t1);font-weight:500; }

  .cp-flag {
    display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;
    font-size:.72rem;font-weight:600;
  }
  .cp-flag-green  { background:#d1fae5;color:#065f46; }
  .cp-flag-blue   { background:#dbeafe;color:#1e40af; }
  .cp-flag-purple { background:#ede9fe;color:#5b21b6; }
  .cp-flag-teal   { background:#cffafe;color:#164e63; }
  .cp-flag-pink   { background:#fce7f3;color:#9d174d; }

  .cp-hidden { display:none !important; }

  @media (max-width: 900px) {
    div[style*="grid-template-columns:320px 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<script>
function confirmDeleteEvent(id){
  swal({
    title: 'Delete this care event?',
    text: 'This cannot be undone.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, delete'
  }, function(){
    window.location.href = baseURL + '/deleteCareEvent/' + id;
  });
}
function confirmDeleteNote(id){
  swal({
    title: 'Delete this note?',
    text: 'This cannot be undone.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Yes, delete'
  }, function(){
    window.location.href = baseURL + '/deleteCareNote/' + id;
  });
}
</script>
