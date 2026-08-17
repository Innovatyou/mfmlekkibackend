<script src="<?= base_url() ?>/public/assets/vendors/scripts/core.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/scripts/script.min.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/scripts/process.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/scripts/layout-settings.js"></script>
<script src="<?= base_url() ?>/public/assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/public/assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/public/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/scripts/dashboard3.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/dropify/dist/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/public/assets/vendors/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>/public/assets/js/ajax.js"></script>
<script>
  /* ── CSRF: must run before common.js initialises DataTables ── */
  (function () {
    var m = document.querySelector('meta[name="csrf-token"]');
    if (m) { $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': m.getAttribute('content') } }); }
  })();
</script>
<script src="<?= base_url() ?>/public/assets/js/common.js"></script>
<script src="<?= base_url() ?>/public/assets/src/plugins/dropzone/src/dropzone.js"></script>

<script>
  /* ── Donations link copy ── */
  let churchpersonaldonationslink = $('#churchpersonaldonationslink').val();
  const copyContent = async () => {
    try {
      await navigator.clipboard.writeText(churchpersonaldonationslink);
      swal({ title: 'Copied!', text: 'Donations link copied to clipboard', type: 'success' });
    } catch (err) {
      console.error('Failed to copy:', err);
    }
  };

  /* ── Dropzone (photos page) ── */
  if (document.querySelector('.dropzone')) {
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone('.dropzone', {
      url: baseURL + '/savenewphoto',
      paramName: 'file',
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 100,
      maxFilesize: 100,
      maxFiles: 20,
      acceptedFiles: '.jpg,.jpeg,.png,.gif',
      addRemoveLinks: true,
      dictFileTooBig: 'File too large ({{filesize}}MB). Max: {{maxFilesize}}MB',
      dictInvalidFileType: 'Invalid file type',
      dictCancelUpload: 'Cancel',
      dictRemoveFile: 'Remove',
      dictMaxFilesExceeded: 'Max {{maxFiles}} files allowed',
      dictDefaultMessage: '<span style="font-size:1.5rem;">&#128247;</span><br>Drop photos here or click to upload',
    });

    function uploadphotos(event) {
      event.preventDefault();
      var title = $('#title').val();
      var description = $('#description').val();
      if (title === '') { error_alert('Please enter a title to continue'); return; }
      myDropzone.on('sendingmultiple', function(file, xhr, formData) {
        formData.append('title', title);
        formData.append('description', description);
      });
      myDropzone.on('successmultiple', function() {
        swal({ title: 'Success', text: 'Photos uploaded successfully', type: 'success', confirmButtonText: 'OK' },
          function() { document.location.reload(); });
      });
      myDropzone.processQueue();
    }
  }

  /* ── Active sidebar item polish ──
     The existing layout-settings.js handles toggle; we just
     ensure active submenu parents stay visually open. */
  $(document).ready(function () {
    // Keep submenu open if a child is active
    $('#accordion-menu .submenu .active').closest('li.dropdown').children('a').addClass('active');

    // DataTables global defaults
    if ($.fn.DataTable) {
      $.extend(true, $.fn.dataTable.defaults, {
        language: {
          search: '',
          searchPlaceholder: 'Search…',
          lengthMenu: 'Show _MENU_ entries',
          info: 'Showing _START_ to _END_ of _TOTAL_ entries',
          paginate: {
            previous: '&lsaquo;',
            next: '&rsaquo;'
          }
        }
      });
    }
  });
</script>
</body>
</html>
