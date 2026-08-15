<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['mail_sms'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><a href="<?= base_url('messaging') ?>"><?= $locale['mail_sms'] ?></a><span>/</span><span><?= $locale['new_mail_sms'] ?></span></nav>
      </div>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <form method="POST" action="<?= base_url('sendnewmessage') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title">Resend / Edit Message</h3></div>
            <div class="nf-card-body">
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['member_list'] ?></label>
                <select id="listpicker" name="list" class="nf-input nf-select" required>
                  <option value="0"><?= $locale['all_members'] ?></option>
                  <?php foreach($lists as $res): ?>
                  <option value="<?= $res->id ?>"><?= esc($res->title) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div style="margin-bottom:16px;">
                <label class="nf-label"><?= $locale['msg_sub'] ?></label>
                <input type="text" name="title" class="nf-input" value="<?= esc($message->title) ?>" required>
              </div>
              <div>
                <label class="nf-label"><?= $locale['msg_content'] ?></label>
                <textarea name="message" class="editor" required><?= $message->message ?></textarea>
              </div>
            </div>
          </div>
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary nf-submit"><?= $locale['send_msg'] ?></button>
            <a href="<?= base_url('messaging') ?>" class="btn btn-light nf-cancel">Cancel</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="nf-card">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['member_format'] ?></h3><p class="nf-card-sub">Delivery channels</p></div>
            <div class="nf-card-body">
              <label class="msg-ch-opt <?= ($istwilioenabled==1&&$istermiienabled==1)?'msg-ch-disabled':'' ?>">
                <input type="checkbox" name="formats[]['sms']" id="smsgateway" value="sms" <?= ($istwilioenabled==1&&$istermiienabled==1)?'disabled':'checked' ?>>
                <span class="msg-ch-icon" style="background:#eef2ff;color:#6366f1;"><i class="dw dw-phone-1"></i></span>
                <span class="msg-ch-info">
                  <span class="msg-ch-name"><?= $locale['text_msg'] ?></span>
                  <?php if($istwilioenabled==1&&$istermiienabled==1):?><span class="msg-ch-note"><?= $locale['enable_sms_gateway'] ?></span><?php endif;?>
                </span>
              </label>
              <label class="msg-ch-opt <?= $isemailenabled==1?'msg-ch-disabled':'' ?>" style="margin-top:10px;">
                <input type="checkbox" name="formats[]['email']" id="email" value="email" <?= $isemailenabled==1?'disabled':'checked' ?>>
                <span class="msg-ch-icon" style="background:#ecfdf5;color:#059669;"><i class="dw dw-mail"></i></span>
                <span class="msg-ch-info">
                  <span class="msg-ch-name"><?= $locale['email_msg'] ?></span>
                  <?php if($isemailenabled==1):?><span class="msg-ch-note"><?= $locale['enable_email_sender'] ?></span><?php endif;?>
                </span>
              </label>
            </div>
          </div>
          <div id="smsgatewaydiv" class="nf-card" style="margin-top:16px;<?= ($istwilioenabled==1&&$istermiienabled==1)?'display:none;':'' ?>">
            <div class="nf-card-head"><h3 class="nf-card-title"><?= $locale['sms_gateway'] ?></h3></div>
            <div class="nf-card-body">
              <select class="nf-input nf-select" id="smsgatewayselect" name="smsgateway" <?= ($istwilioenabled==0||$istermiienabled==0)?'required':'' ?>>
                <?php if($istwilioenabled==0&&$istermiienabled==0):?><option value=""><?= $locale['select_sms_gateway'] ?></option><?php endif;?>
                <?php if($istwilioenabled==0):?><option value="twilio">TWILIO</option><?php endif;?>
                <?php if($istermiienabled==0):?><option value="termii">TERMII</option><?php endif;?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?= view('_nf_styles') ?>
<style>
.msg-ch-opt{display:flex;align-items:center;gap:12px;padding:12px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;transition:border-color .15s}
.msg-ch-opt:has(input:checked){border-color:var(--accent);background:#f8f7ff}
.msg-ch-disabled{opacity:.55;cursor:not-allowed}
.msg-ch-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.msg-ch-info{display:flex;flex-direction:column}
.msg-ch-name{font-size:.85rem;font-weight:600;color:var(--t1)}
.msg-ch-note{font-size:.72rem;color:var(--t3);margin-top:2px}
.msg-ch-opt input{display:none}
</style>
<script>
document.getElementById('smsgateway').addEventListener('change', function() {
  document.getElementById('smsgatewaydiv').style.display = this.checked ? '' : 'none';
});
</script>
