<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Open New Counseling Case</h1>
        <p class="page-subtitle">All case records are confidential and restricted to pastoral staff</p>
      </div>
      <a href="<?= base_url('counseling') ?>" class="btn btn-outline-secondary">
        <i class="dw dw-left-arrow1" style="margin-right:5px;"></i>Back
      </a>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('error')): ?>
      <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#fee2e2;color:#7f1d1d;border-radius:9px;margin-bottom:20px;">
        <i class="dw dw-warning-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button style="margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;color:inherit;" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

      <!-- Main Form -->
      <div class="card-box" style="padding:28px;">
        <form action="<?= base_url('saveNewCounselingCase') ?>" method="post">
          <?= csrf_field() ?>

          <!-- Member Selection -->
          <div style="margin-bottom:20px;">
            <label style="display:block;margin-bottom:6px;">Link to a Member <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
            <select id="member_select" name="member_id" class="form-control">
              <option value="">— Non-member / Walk-in —</option>
              <?php foreach ($members as $m): ?>
                <option value="<?= $m->id ?>"
                  data-name="<?= esc($m->firstname . ' ' . $m->lastname) ?>"
                  data-email="<?= esc($m->email) ?>"
                  data-phone="<?= esc($m->phonenumber) ?>">
                  <?= esc($m->firstname . ' ' . $m->lastname) ?> — <?= esc($m->email) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Name -->
          <div style="margin-bottom:20px;">
            <label style="display:block;margin-bottom:6px;">Full Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="member_name" id="member_name" class="form-control"
                   placeholder="Counselee's full name" value="<?= old('member_name') ?>" required>
          </div>

          <!-- Category & Priority in a row -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
              <label style="display:block;margin-bottom:6px;">Category <span style="color:#ef4444;">*</span></label>
              <select name="category" class="form-control" required>
                <option value="">Select category…</option>
                <option value="marriage">Marriage</option>
                <option value="family">Family</option>
                <option value="grief">Grief &amp; Loss</option>
                <option value="addiction">Addiction</option>
                <option value="mental_health">Mental Health</option>
                <option value="financial">Financial</option>
                <option value="spiritual">Spiritual</option>
                <option value="relationship">Relationships</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;">Priority <span style="color:#ef4444;">*</span></label>
              <select name="priority" class="form-control" required>
                <option value="normal" selected>Normal</option>
                <option value="low">Low</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
          </div>

          <!-- Title / Presenting Issue -->
          <div style="margin-bottom:20px;">
            <label style="display:block;margin-bottom:6px;">Presenting Issue / Case Title <span style="color:#ef4444;">*</span></label>
            <input type="text" name="title" class="form-control"
                   placeholder="Brief summary of the concern (e.g. Marriage conflict requiring mediation)"
                   value="<?= old('title') ?>" required>
          </div>

          <!-- Assigned To & Follow-up -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
              <label style="display:block;margin-bottom:6px;">Assigned Counselor / Pastor</label>
              <input type="text" name="assigned_to" class="form-control"
                     placeholder="Pastor / Counselor name" value="<?= old('assigned_to') ?>">
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;">First Follow-up Date</label>
              <input type="date" name="next_followup" class="form-control" value="<?= old('next_followup') ?>">
            </div>
          </div>

          <!-- Initial Note -->
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:6px;">Initial Intake Notes <span style="color:#94a3b8;font-weight:400;">(confidential)</span></label>
            <textarea name="initial_note" class="form-control" rows="5"
                      placeholder="Record the initial intake conversation, background information, or presenting concerns…"><?= old('initial_note') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
            <i class="dw dw-add" style="margin-right:6px;"></i>Open Case
          </button>
          <a href="<?= base_url('counseling') ?>" class="btn btn-outline-secondary" style="margin-left:8px;">Cancel</a>

        </form>
      </div>

      <!-- Sidebar Info -->
      <div>
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#f97316);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
              <i class="dw dw-padlock" style="font-size:.9rem;"></i>
            </div>
            <span style="font-weight:700;font-size:.9rem;color:#0f172a;">Confidentiality</span>
          </div>
          <p style="font-size:.82rem;color:#475569;margin:0;line-height:1.6;">
            This case will be marked <strong>confidential</strong> by default. Only authorised pastoral staff can view session notes and case history.
          </p>
        </div>

        <div class="card-box" style="padding:20px;">
          <div style="font-weight:700;font-size:.9rem;color:#0f172a;margin-bottom:12px;">Priority Guide</div>
          <div style="font-size:.8rem;line-height:1.8;color:#475569;">
            <div><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#94a3b8;margin-right:6px;"></span><strong>Low</strong> — No urgency, routine support</div>
            <div><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#3b82f6;margin-right:6px;"></span><strong>Normal</strong> — Standard counseling</div>
            <div><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#f97316;margin-right:6px;"></span><strong>High</strong> — Elevated need, follow up soon</div>
            <div><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ef4444;margin-right:6px;"></span><strong>Urgent</strong> — Crisis situation, immediate action</div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
document.getElementById('member_select').addEventListener('change', function(){
  var opt = this.options[this.selectedIndex];
  var nameField = document.getElementById('member_name');
  if (opt.value) {
    nameField.value = opt.getAttribute('data-name');
  }
});
</script>
