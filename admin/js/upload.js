jQuery(function ($) {

  'use strict';

  /**
   * File input change.
   */
  $(document).on('change', '#bbmwpv-upload-file', function () {

    let file = this.files[0];

    if (!file) {
      return;
    }

    let maxSize = 1024 * 1024 * 1024 * 2;

    /**
     * 2GB limit.
     */
    if (file.size > maxSize) {

      alert('The selected file is too large.');

      $(this).val('');

      return;
    }

    let extension = '';

    if (file.name.indexOf('.') !== -1) {

      extension = file.name
        .split('.')
        .pop()
        .toLowerCase();
    }

    if (extension !== 'zip') {

      alert('Only ZIP files are allowed.');

      $(this).val('');

      return;
    }

    let info =
      '<div class="bbmwpv-upload-file-info">' +
      '<strong>Selected File:</strong><br>' +
      file.name +
      '<br><br>' +
      '<strong>Size:</strong><br>' +
      formatBytes(file.size) +
      '</div>';

    $('#bbmwpv-upload-result').html(info);
  });

  /**
   * Format bytes.
   */
  function formatBytes(bytes) {

    if (bytes === 0) {
      return '0 Bytes';
    }

    let k = 1024;

    let sizes = [
      'Bytes',
      'KB',
      'MB',
      'GB',
      'TB'
    ];

    let i = Math.floor(
      Math.log(bytes) / Math.log(k)
    );

    return parseFloat(
      (bytes / Math.pow(k, i)).toFixed(2)
    ) + ' ' + sizes[i];
  }

});