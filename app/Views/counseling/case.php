<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">

    <?php
      $statusColor   = ['open'=>'#3b82f6','in_progress'=>'#f59e0b','on_hold'=>'#94a3b8','closed'=>'#10b981','referred'=>'#8b5cf6'];
      $priorityColor = ['low'=>'#94a3b8','normal'=>'#3b82f6','high'=>'#f97316','urgent'=>'#ef4444'];
      $sc = $statusColor[$case->status]    ?? '#94a3b8';
      $pc = $priorityColor[$case->priority] ?? '#94a3b8';

      $sessionTypeLabel = ['in_person'=>'In Person','phone'=>'Phone','video'=>'Video','email'=>'Email','prayer'=>'Prayer','other'=>'Other'];
      $sessionTypeIcon  = ['in_person'=>'dw-torso','phone'=>'dw-phone-2','video'=>'dw-video-camera','email'=>'dw-email','prayer'=>'dw-open-book','other'=>'dw-more'];

      $platformLabel = ['zoom'=>'Zoom','google_meet'=>'Google Meet','teams'=>'Microsoft Teams','whatsapp'=>'WhatsApp'];
      $platformColor = ['zoom'=>'#2D8CFF','google_meet'=>'#00897B','teams'=>'#6264A7','whatsapp'=>'#25D366'];
      $platformIcon  = [
        'zoom'        => '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 8.5v7L20 18V6l-4.5 2.5zm-11 .5a2 2 0 012-2h7a2 2 0 012 2v6a2 2 0 01-2 2h-7a2 2 0 01-2-2V9z"/></svg>',
        'google_meet' => '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1v-3.5l4 4v-11l-4 4z"/></svg>',
        'teams'       => '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20 7h-4V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm-8-3h4v3h-4V4zm8 14H4V9h16v9z"/></svg>',
        'whatsapp'    => '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
      ];

      $meetStatusColor = ['pending'=>'#f59e0b','confirmed'=>'#3b82f6','completed'=>'#10b981','cancelled'=>'#ef4444'];
      $meetStatusLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled'];
    ?>

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <a href="<?= base_url('counseling') ?>" style="font-size:.8rem;color:#6366f1;text-decoration:none;">
          <i class="dw dw-left-arrow1" style="margin-right:3px;"></i>All Cases
        </a>
        <h1 class="page-title" style="margin-top:4px;"><?= esc($case->member_name) ?></h1>
        <p class="page-subtitle"><?= esc($case->title) ?></p>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <span style="padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:700;background:<?= $sc ?>22;color:<?= $sc ?>;">
          <?= ucfirst(str_replace('_',' ',$case->status)) ?>
        </span>
        <span style="padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:700;background:<?= $pc ?>22;color:<?= $pc ?>;">
          <?= ucfirst($case->priority) ?> Priority
        </span>
        <span style="padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:700;background:#fef3c7;color:#78350f;">
          <i class="dw dw-padlock" style="margin-right:3px;"></i>Confidential
        </span>
      </div>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#d1fae5;color:#065f46;border-radius:9px;margin-bottom:20px;">
        <i class="dw dw-check-circle-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button style="margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;color:inherit;" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#fee2e2;color:#7f1d1d;border-radius:9px;margin-bottom:20px;">
        <i class="dw dw-warning-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button style="margin-left:auto;background:none;border:none;font-size:1.1rem;cursor:pointer;color:inherit;" onclick="this.parentElement.remove()">&times;</button>
      </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

      <!-- LEFT COLUMN -->
      <div>

        <!-- Session / Video Scheduling Card with Tabs -->
        <div class="card-box" style="padding:24px;margin-bottom:20px;">

          <!-- Tab switcher -->
          <div style="display:flex;gap:0;margin-bottom:20px;background:#f1f5f9;border-radius:9px;padding:4px;">
            <button id="tab-log" onclick="switchTab('log')"
              style="flex:1;padding:8px 14px;border:none;border-radius:7px;font-size:.84rem;font-weight:600;cursor:pointer;background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,.08);">
              <i class="dw dw-edit" style="margin-right:5px;"></i>Log Interaction
            </button>
            <button id="tab-video" onclick="switchTab('video')"
              style="flex:1;padding:8px 14px;border:none;border-radius:7px;font-size:.84rem;font-weight:600;cursor:pointer;background:transparent;color:#94a3b8;">
              <i class="dw dw-video-camera" style="margin-right:5px;"></i>Schedule Video Call
            </button>
          </div>

          <!-- ── TAB: Log Interaction ── -->
          <div id="panel-log">
            <form action="<?= base_url('logCounselingSession') ?>" method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="case_id" value="<?= $case->id ?>">

              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Session Type</label>
                  <select name="session_type" class="form-control form-control-sm" required>
                    <option value="in_person">In Person</option>
                    <option value="phone">Phone</option>
                    <option value="video">Video</option>
                    <option value="email">Email</option>
                    <option value="prayer">Prayer</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Date</label>
                  <input type="date" name="session_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                </div>
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Duration (mins)</label>
                  <input type="number" name="duration_minutes" class="form-control form-control-sm" placeholder="60" min="1">
                </div>
              </div>

              <div style="margin-bottom:12px;">
                <label style="display:block;margin-bottom:5px;font-size:.8rem;">Session Notes <span style="color:#ef4444;">*</span> <span style="color:#94a3b8;">(confidential)</span></label>
                <textarea name="notes" class="form-control" rows="4"
                          placeholder="What was discussed, counselee's state, key observations…" required></textarea>
              </div>

              <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Outcome / Summary</label>
                  <textarea name="outcome" class="form-control form-control-sm" rows="2" placeholder="Session outcome…"></textarea>
                </div>
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Next Steps / Action Items</label>
                  <textarea name="next_steps" class="form-control form-control-sm" rows="2" placeholder="What happens next…"></textarea>
                </div>
              </div>

              <button type="submit" class="btn btn-primary btn-sm">
                <i class="dw dw-add" style="margin-right:4px;"></i>Log Session
              </button>
            </form>
          </div>

          <!-- ── TAB: Schedule Video Call ── -->
          <div id="panel-video" style="display:none;">

            <!-- Platform picker -->
            <div style="margin-bottom:18px;">
              <label style="display:block;margin-bottom:10px;font-size:.85rem;font-weight:600;">Select Platform <span style="color:#ef4444;">*</span></label>
              <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;" id="platform-grid">
                <?php
                  $platforms = [
                    'zoom'        => ['label'=>'Zoom',           'color'=>'#2D8CFF', 'bg'=>'#EFF6FF'],
                    'google_meet' => ['label'=>'Google Meet',    'color'=>'#00897B', 'bg'=>'#E6F4F1'],
                    'teams'       => ['label'=>'Microsoft Teams','color'=>'#6264A7', 'bg'=>'#F0EFF8'],
                    'whatsapp'    => ['label'=>'WhatsApp',       'color'=>'#25D366', 'bg'=>'#EDFAF1'],
                  ];
                ?>
                <?php foreach ($platforms as $key => $p): ?>
                  <label for="plat_<?= $key ?>" class="platform-card"
                    style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:all .15s;background:#fafafa;">
                    <input type="radio" name="platform_pick" id="plat_<?= $key ?>" value="<?= $key ?>" style="display:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:<?= $p['bg'] ?>;display:flex;align-items:center;justify-content:center;">
                      <?php if ($key === 'zoom'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $p['color'] ?>"><path d="M15.5 8.5v7L20 18V6l-4.5 2.5zm-11 .5a2 2 0 012-2h7a2 2 0 012 2v6a2 2 0 01-2 2h-7a2 2 0 01-2-2V9z"/></svg>
                      <?php elseif ($key === 'google_meet'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $p['color'] ?>"><path d="M17 10.5V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1v-3.5l4 4v-11l-4 4z"/></svg>
                      <?php elseif ($key === 'teams'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $p['color'] ?>"><path d="M20 7h-4V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm-8-3h4v3h-4V4zm8 14H4V9h16v9z"/></svg>
                      <?php else: ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $p['color'] ?>"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26C2.157 5.45 6.592 1.016 12.044 1.016c2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                      <?php endif; ?>
                    </div>
                    <span style="font-size:.75rem;font-weight:600;color:#475569;"><?= $p['label'] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <form action="<?= base_url('scheduleVideoSession') ?>" method="post" id="video-form">
              <?= csrf_field() ?>
              <input type="hidden" name="case_id" value="<?= $case->id ?>">
              <input type="hidden" name="meeting_platform" id="hidden_platform">

              <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Date &amp; Time <span style="color:#ef4444;">*</span></label>
                  <input type="datetime-local" name="meeting_scheduled_at" class="form-control form-control-sm" required>
                </div>
                <div>
                  <label style="display:block;margin-bottom:5px;font-size:.8rem;">Duration (mins)</label>
                  <input type="number" name="duration_minutes" class="form-control form-control-sm" placeholder="60" min="1" value="60">
                </div>
              </div>

              <div style="margin-bottom:14px;">
                <label style="display:block;margin-bottom:5px;font-size:.8rem;">Meeting Link / Join URL</label>
                <input type="url" name="meeting_link" class="form-control form-control-sm"
                       placeholder="https://zoom.us/j/… or https://meet.google.com/…">
              </div>

              <div style="margin-bottom:18px;">
                <label style="display:block;margin-bottom:5px;font-size:.8rem;">Prep Notes <span style="color:#94a3b8;">(optional)</span></label>
                <textarea name="notes" class="form-control form-control-sm" rows="2"
                          placeholder="Agenda, topics to cover, reminders for the session…"></textarea>
              </div>

              <button type="submit" id="video-submit" class="btn btn-primary btn-sm" disabled
                style="opacity:.5;cursor:not-allowed;">
                <i class="dw dw-video-camera" style="margin-right:5px;"></i>Schedule Video Session
              </button>
              <span id="platform-hint" style="font-size:.78rem;color:#f97316;margin-left:10px;display:none;">
                Please select a platform above first.
              </span>
            </form>
          </div>

        </div>

        <!-- Session History -->
        <div class="card-box" style="padding:24px;">
          <h5 style="font-size:.9rem;font-weight:700;color:#0f172a;margin:0 0 18px;">
            Session History
            <span style="margin-left:8px;background:#e0f2fe;color:#0c4a6e;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600;"><?= count($sessions) ?></span>
          </h5>

          <?php if (empty($sessions)): ?>
            <div style="text-align:center;padding:32px 0;color:#94a3b8;">
              <i class="dw dw-files" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
              No sessions logged yet.
            </div>
          <?php else: ?>
            <div style="position:relative;padding-left:28px;">
              <div style="position:absolute;left:9px;top:0;bottom:0;width:2px;background:#e2e8f0;"></div>

              <?php foreach ($sessions as $s):
                $isVideo    = ($s->session_type === 'video' && !empty($s->meeting_platform));
                $platKey    = $s->meeting_platform ?? '';
                $platColor  = $platformColor[$platKey]  ?? '#6366f1';
                $platLabel  = $platformLabel[$platKey]  ?? '';
                $platIconSvg= $platformIcon[$platKey]   ?? '';
                $mStatus    = $s->meeting_status ?? null;
                $isUpcoming = $isVideo && $mStatus && in_array($mStatus, ['pending','confirmed'])
                              && $s->meeting_scheduled_at && strtotime($s->meeting_scheduled_at) > time();
                $stype      = $s->session_type;
                $sicon      = $sessionTypeIcon[$stype]  ?? 'dw-more';
                $slabel     = $sessionTypeLabel[$stype] ?? ucfirst($stype);
              ?>

              <div style="position:relative;margin-bottom:24px;">
                <!-- Timeline dot -->
                <div style="position:absolute;left:-28px;width:20px;height:20px;border-radius:50%;
                     background:<?= $isVideo ? $platColor : '#6366f1' ?>;
                     display:flex;align-items:center;justify-content:center;top:4px;z-index:1;">
                  <?php if ($isVideo && $platIconSvg): ?>
                    <span style="color:#fff;display:flex;"><?= $platIconSvg ?></span>
                  <?php else: ?>
                    <i class="dw <?= $sicon ?>" style="font-size:.55rem;color:#fff;"></i>
                  <?php endif; ?>
                </div>

                <div style="background:<?= $isUpcoming ? '#f0f9ff' : '#f8fafc' ?>;
                     border:1px solid <?= $isUpcoming ? '#bae6fd' : '#e2e8f0' ?>;border-radius:10px;padding:16px;">

                  <!-- Header row -->
                  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px;">
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                      <?php if ($isVideo && $platLabel): ?>
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;background:<?= $platColor ?>22;color:<?= $platColor ?>;">
                          <?= $platIconSvg ?><?= $platLabel ?>
                        </span>
                      <?php else: ?>
                        <span style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $slabel ?></span>
                      <?php endif; ?>

                      <?php if ($s->meeting_scheduled_at): ?>
                        <span style="font-size:.78rem;color:#0369a1;font-weight:600;">
                          <?= date('M j, Y g:i A', strtotime($s->meeting_scheduled_at)) ?>
                        </span>
                      <?php elseif ($s->session_date): ?>
                        <span style="font-size:.78rem;color:#94a3b8;"><?= date('M j, Y', strtotime($s->session_date)) ?></span>
                      <?php endif; ?>

                      <?php if ($s->duration_minutes): ?>
                        <span style="font-size:.75rem;background:#e0f2fe;color:#0c4a6e;padding:1px 8px;border-radius:20px;"><?= $s->duration_minutes ?> min</span>
                      <?php endif; ?>

                      <?php if ($mStatus): ?>
                        <span style="font-size:.72rem;font-weight:700;padding:2px 9px;border-radius:20px;background:<?= $meetStatusColor[$mStatus] ?? '#94a3b8' ?>22;color:<?= $meetStatusColor[$mStatus] ?? '#94a3b8' ?>;">
                          <?= $meetStatusLabel[$mStatus] ?? $mStatus ?>
                        </span>
                      <?php endif; ?>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                      <!-- Join link -->
                      <?php if ($isVideo && !empty($s->meeting_link) && in_array($mStatus, ['pending','confirmed'])): ?>
                        <a href="<?= esc($s->meeting_link) ?>" target="_blank"
                           style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;background:<?= $platColor ?>;color:#fff;border-radius:7px;font-size:.78rem;font-weight:600;text-decoration:none;">
                          <i class="dw dw-video-camera"></i> Join
                        </a>
                      <?php endif; ?>

                      <!-- Meeting status changer -->
                      <?php if ($isVideo && $mStatus && $mStatus !== 'completed' && $mStatus !== 'cancelled'): ?>
                        <form action="<?= base_url('updateMeetingStatus/' . $s->id) ?>" method="post" style="display:inline-flex;gap:4px;">
                          <?= csrf_field() ?>
                          <select name="meeting_status" class="form-control" style="height:28px;font-size:.75rem;padding:2px 6px;border-radius:6px;">
                            <option value="pending"   <?= $mStatus==='pending'   ? 'selected':'' ?>>Pending</option>
                            <option value="confirmed" <?= $mStatus==='confirmed' ? 'selected':'' ?>>Confirmed</option>
                            <option value="completed" <?= $mStatus==='completed' ? 'selected':'' ?>>Completed</option>
                            <option value="cancelled" <?= $mStatus==='cancelled' ? 'selected':'' ?>>Cancelled</option>
                          </select>
                          <button type="submit" style="padding:3px 9px;background:#6366f1;color:#fff;border:none;border-radius:6px;font-size:.72rem;cursor:pointer;">Save</button>
                        </form>
                      <?php endif; ?>

                      <span style="font-size:.75rem;color:#94a3b8;">by <?= esc($s->logged_by) ?></span>
                      <a href="<?= base_url('deleteCounselingSession/' . $s->id) ?>"
                         style="font-size:.75rem;color:#ef4444;text-decoration:none;"
                         onclick="return confirm('Delete this session record?')">Delete</a>
                    </div>
                  </div>

                  <!-- Notes -->
                  <?php if ($s->notes && $s->notes !== 'Video session scheduled.'): ?>
                    <div style="font-size:.84rem;color:#0f172a;line-height:1.6;white-space:pre-wrap;"><?= esc($s->notes) ?></div>
                  <?php endif; ?>

                  <?php if ($s->outcome): ?>
                    <div style="margin-top:10px;padding-top:10px;border-top:1px solid #e2e8f0;">
                      <span style="font-size:.74rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.05em;">Outcome</span>
                      <div style="font-size:.83rem;color:#475569;margin-top:3px;white-space:pre-wrap;"><?= esc($s->outcome) ?></div>
                    </div>
                  <?php endif; ?>

                  <?php if ($s->next_steps): ?>
                    <div style="margin-top:8px;">
                      <span style="font-size:.74rem;font-weight:700;color:#6366f1;text-transform:uppercase;letter-spacing:.05em;">Next Steps</span>
                      <div style="font-size:.83rem;color:#475569;margin-top:3px;white-space:pre-wrap;"><?= esc($s->next_steps) ?></div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </div><!-- /LEFT -->

      <!-- RIGHT SIDEBAR -->
      <div>

        <!-- Case Info -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h6 style="font-size:.82rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px;">Case Information</h6>
          <div style="font-size:.84rem;line-height:2;">
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;margin-bottom:6px;">
              <span style="color:#94a3b8;">Category</span>
              <span style="font-weight:600;color:#0f172a;"><?= ucfirst(str_replace('_', ' ', $case->category)) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;margin-bottom:6px;">
              <span style="color:#94a3b8;">Assigned To</span>
              <span style="font-weight:600;color:#0f172a;"><?= $case->assigned_to ? esc($case->assigned_to) : '—' ?></span>
            </div>
            <?php if ($case->member_email): ?>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;margin-bottom:6px;">
              <span style="color:#94a3b8;">Email</span>
              <span style="font-weight:600;color:#0f172a;font-size:.78rem;"><?= esc($case->member_email) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($case->member_phone): ?>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;margin-bottom:6px;">
              <span style="color:#94a3b8;">Phone</span>
              <span style="font-weight:600;color:#0f172a;"><?= esc($case->member_phone) ?></span>
            </div>
            <?php endif; ?>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;margin-bottom:6px;">
              <span style="color:#94a3b8;">Opened</span>
              <span style="font-weight:600;color:#0f172a;"><?= $case->opened_at ? date('M j, Y', strtotime($case->opened_at)) : '—' ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;">
              <span style="color:#94a3b8;">Next Follow-up</span>
              <?php if ($case->next_followup): ?>
                <?php $overdue = strtotime($case->next_followup) < time(); ?>
                <span style="font-weight:600;color:<?= $overdue ? '#ef4444' : '#0f172a' ?>;">
                  <?= date('M j, Y', strtotime($case->next_followup)) ?>
                  <?= $overdue ? ' <small style="font-size:.7rem;">(overdue)</small>' : '' ?>
                </span>
              <?php else: ?>
                <span style="color:#94a3b8;">—</span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Update Status -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h6 style="font-size:.82rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px;">Update Status</h6>
          <form action="<?= base_url('updateCounselingStatus/' . $case->id) ?>" method="post">
            <?= csrf_field() ?>
            <select name="status" class="form-control form-control-sm" style="margin-bottom:10px;">
              <option value="open"        <?= $case->status==='open'        ?'selected':'' ?>>Open</option>
              <option value="in_progress" <?= $case->status==='in_progress' ?'selected':'' ?>>In Progress</option>
              <option value="on_hold"     <?= $case->status==='on_hold'     ?'selected':'' ?>>On Hold</option>
              <option value="referred"    <?= $case->status==='referred'    ?'selected':'' ?>>Referred</option>
              <option value="closed"      <?= $case->status==='closed'      ?'selected':'' ?>>Closed</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">Update Status</button>
          </form>
        </div>

        <!-- Assign To Pastor -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h6 style="font-size:.82rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px;">Assign To Pastor</h6>
          <form action="<?= base_url('assignCounselingCase/' . $case->id) ?>" method="post">
            <?= csrf_field() ?>
            <select name="assigned_to" class="form-control form-control-sm" style="margin-bottom:10px;">
              <option value="">— Unassigned —</option>
              <?php foreach ($pastors as $p): ?>
                <option value="<?= esc($p->fullname) ?>" <?= $case->assigned_to === $p->fullname ? 'selected' : '' ?>>
                  <?= esc($p->fullname) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
              <i class="dw dw-user1" style="margin-right:5px;"></i>Save Assignment
            </button>
          </form>
        </div>

        <!-- Reminders -->
        <div class="card-box" style="padding:20px;margin-bottom:16px;">
          <h6 style="font-size:.82rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px;">Follow-up Reminders</h6>

          <?php if (!empty($reminders)): ?>
            <div style="margin-bottom:14px;">
              <?php foreach ($reminders as $r): ?>
                <div style="display:flex;align-items:flex-start;gap:8px;padding:8px 0;border-bottom:1px solid #f1f5f9;">
                  <div style="width:16px;height:16px;border-radius:50%;background:<?= $r->is_done ? '#d1fae5' : '#fef3c7' ?>;
                       display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <i class="dw <?= $r->is_done ? 'dw-check-circle-2' : 'dw-alarm-clock' ?>"
                       style="font-size:.55rem;color:<?= $r->is_done ? '#10b981' : '#f59e0b' ?>;"></i>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div style="font-size:.8rem;font-weight:600;color:<?= $r->is_done ? '#94a3b8' : '#0f172a' ?>;<?= $r->is_done ? 'text-decoration:line-through;' : '' ?>">
                      <?= date('M j, Y', strtotime($r->reminder_date)) ?>
                    </div>
                    <?php if ($r->note): ?><div style="font-size:.75rem;color:#94a3b8;"><?= esc($r->note) ?></div><?php endif; ?>
                  </div>
                  <div style="display:flex;gap:4px;flex-shrink:0;">
                    <?php if (!$r->is_done): ?>
                      <a href="<?= base_url('counselingReminderDone/' . $r->id) ?>" style="font-size:.7rem;color:#10b981;">Done</a>
                    <?php endif; ?>
                    <a href="<?= base_url('deleteCounselingReminder/' . $r->id) ?>"
                       style="font-size:.7rem;color:#ef4444;" onclick="return confirm('Delete reminder?')">Del</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('addCounselingReminder') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="case_id" value="<?= $case->id ?>">
            <input type="date" name="reminder_date" class="form-control form-control-sm" style="margin-bottom:8px;"
                   value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
            <input type="text" name="note" class="form-control form-control-sm" style="margin-bottom:8px;"
                   placeholder="Reminder note (optional)">
            <button type="submit" class="btn btn-outline-primary btn-sm" style="width:100%;">Add Reminder</button>
          </form>
        </div>

        <!-- Danger Zone -->
        <div class="card-box" style="padding:16px;border:1px solid #fecaca;">
          <h6 style="font-size:.8rem;font-weight:700;color:#dc2626;margin:0 0 10px;">Danger Zone</h6>
          <a href="<?= base_url('deleteCounselingCase/' . $case->id) ?>"
             class="btn btn-sm" style="width:100%;background:#fee2e2;color:#dc2626;border:none;"
             onclick="return confirm('Permanently delete this case and ALL its sessions and reminders? This cannot be undone.')">
            <i class="dw dw-delete-3" style="margin-right:5px;"></i>Delete Case
          </a>
        </div>

      </div><!-- /RIGHT -->
    </div>

  </div>
</div>

<style>
.platform-card input:checked ~ * { /* handled via JS */ }
.platform-card.selected {
  border-color: var(--accent) !important;
  background: #f0f0ff !important;
  box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}
</style>

<script>
// Tab switcher
function switchTab(tab) {
  var isLog = tab === 'log';
  document.getElementById('panel-log').style.display   = isLog ? 'block' : 'none';
  document.getElementById('panel-video').style.display = isLog ? 'none'  : 'block';

  var btnLog   = document.getElementById('tab-log');
  var btnVideo = document.getElementById('tab-video');
  btnLog.style.background   = isLog ? '#fff' : 'transparent';
  btnLog.style.color        = isLog ? '#0f172a' : '#94a3b8';
  btnLog.style.boxShadow    = isLog ? '0 1px 3px rgba(0,0,0,.08)' : 'none';
  btnVideo.style.background = !isLog ? '#fff' : 'transparent';
  btnVideo.style.color      = !isLog ? '#0f172a' : '#94a3b8';
  btnVideo.style.boxShadow  = !isLog ? '0 1px 3px rgba(0,0,0,.08)' : 'none';
}

// Platform card picker
document.querySelectorAll('.platform-card').forEach(function(card) {
  card.addEventListener('click', function() {
    document.querySelectorAll('.platform-card').forEach(function(c){ c.classList.remove('selected'); });
    this.classList.add('selected');
    var val = this.querySelector('input[type=radio]').value;
    document.getElementById('hidden_platform').value = val;

    var btn = document.getElementById('video-submit');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor  = 'pointer';
    document.getElementById('platform-hint').style.display = 'none';
  });
});

// Guard submit if no platform selected
document.getElementById('video-form').addEventListener('submit', function(e) {
  if (!document.getElementById('hidden_platform').value) {
    e.preventDefault();
    document.getElementById('platform-hint').style.display = 'inline';
  }
});
</script>
