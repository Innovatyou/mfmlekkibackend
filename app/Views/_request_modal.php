<!-- ── Shared "view record" modal ──
     Include this partial once per page, then either:
       - openRequestModal(title, fields, approveUrl, approveLabel, rejectUrl, rejectLabel)
         directly with data already on hand (server-rendered rows), or
       - openRequestModalFromEndpoint(endpointUrl, title) to fetch JSON first
         (DataTables-driven rows, where full details aren't in the DOM) --
         the JSON may include approveUrl/approveLabel and/or
         rejectUrl/rejectLabel. -->
<div id="rq-modal-overlay" class="rq-modal-overlay" onclick="if(event.target===this) closeRequestModal();">
  <div class="rq-modal">
    <div class="rq-modal-head">
      <h3 id="rq-modal-title">Details</h3>
      <button type="button" class="rq-modal-close" onclick="closeRequestModal();">&times;</button>
    </div>
    <div class="rq-modal-body" id="rq-modal-body"></div>
    <div class="rq-modal-foot" id="rq-modal-foot"></div>
  </div>
</div>

<style>
.rq-modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:10000;align-items:center;justify-content:center;padding:20px;}
.rq-modal-overlay.rq-open{display:flex;}
.rq-modal{background:#fff;border-radius:14px;width:100%;max-width:480px;max-height:85vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.25);}
.rq-modal-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border);flex-shrink:0;}
.rq-modal-head h3{font-size:1rem;font-weight:700;color:var(--t1);margin:0;}
.rq-modal-close{background:none;border:none;font-size:1.4rem;line-height:1;color:var(--t3);cursor:pointer;opacity:.7;padding:0;}
.rq-modal-close:hover{opacity:1;}
.rq-modal-body{padding:18px 20px;overflow-y:auto;}
.rq-field{margin-bottom:14px;}
.rq-field:last-child{margin-bottom:0;}
.rq-field-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);margin-bottom:3px;}
.rq-field-value{font-size:.9rem;color:var(--t1);white-space:pre-wrap;word-break:break-word;}
.rq-modal-foot{padding:14px 20px;border-top:1px solid var(--border);flex-shrink:0;}
.rq-modal-loading{padding:40px;text-align:center;color:var(--t3);}
</style>

<script>
function rqClearChildren(el) { while (el.firstChild) el.removeChild(el.firstChild); }

function rqActionButton(url, label, iconClass, btnClass) {
  var icon = document.createElement('i');
  icon.className = iconClass;
  icon.style.marginRight = '6px';
  var btn = document.createElement('a');
  btn.href = url;
  btn.className = btnClass;
  btn.style.cssText = 'flex:1;border-radius:8px;font-weight:600;text-align:center;';
  btn.appendChild(icon);
  btn.appendChild(document.createTextNode(label));
  btn.addEventListener('click', function (e) {
    if (!confirm(label + ' this?')) e.preventDefault();
  });
  return btn;
}

function openRequestModal(title, fields, approveUrl, approveLabel, rejectUrl, rejectLabel) {
  document.getElementById('rq-modal-title').textContent = title || 'Details';

  var body = document.getElementById('rq-modal-body');
  rqClearChildren(body);
  (fields || []).forEach(function (f) {
    if (f.value === null || f.value === undefined || f.value === '') return;
    var wrap = document.createElement('div'); wrap.className = 'rq-field';
    var label = document.createElement('div'); label.className = 'rq-field-label'; label.textContent = f.label;
    var value = document.createElement('div'); value.className = 'rq-field-value'; value.textContent = f.value;
    wrap.appendChild(label); wrap.appendChild(value);
    body.appendChild(wrap);
  });

  var foot = document.getElementById('rq-modal-foot');
  rqClearChildren(foot);
  if (approveUrl || rejectUrl) {
    foot.style.display = 'flex';
    foot.style.gap = '10px';
    if (approveUrl) {
      foot.appendChild(rqActionButton(approveUrl, approveLabel || 'Approve', 'dw dw-check-circle-2', 'btn btn-success'));
    }
    if (rejectUrl) {
      foot.appendChild(rqActionButton(rejectUrl, rejectLabel || 'Reject', 'dw dw-close-circle-1', 'btn btn-danger'));
    }
  } else {
    foot.style.display = 'none';
  }

  document.getElementById('rq-modal-overlay').classList.add('rq-open');
}

function closeRequestModal() {
  document.getElementById('rq-modal-overlay').classList.remove('rq-open');
}

function openRequestModalFromEndpoint(endpointUrl, title) {
  document.getElementById('rq-modal-title').textContent = title || 'Details';
  var body = document.getElementById('rq-modal-body');
  rqClearChildren(body);
  var loading = document.createElement('div');
  loading.className = 'rq-modal-loading';
  loading.textContent = 'Loading…';
  body.appendChild(loading);
  rqClearChildren(document.getElementById('rq-modal-foot'));
  document.getElementById('rq-modal-foot').style.display = 'none';
  document.getElementById('rq-modal-overlay').classList.add('rq-open');

  fetch(endpointUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function (r) { return r.json(); })
    .then(function (res) {
      if (res.status !== 'ok') {
        rqClearChildren(body);
        var err = document.createElement('div');
        err.className = 'rq-modal-loading';
        err.textContent = res.message || 'Could not load this record.';
        body.appendChild(err);
        return;
      }
      openRequestModal(res.title, res.fields, res.approveUrl, res.approveLabel, res.rejectUrl, res.rejectLabel);
    })
    .catch(function () {
      rqClearChildren(body);
      var err = document.createElement('div');
      err.className = 'rq-modal-loading';
      err.textContent = 'Could not load this record.';
      body.appendChild(err);
    });
}

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeRequestModal();
});
</script>
