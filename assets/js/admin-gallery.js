/**
 * Poti Gallery Admin Script
 *
 * Handles image uploading and management in the admin area.
 *
 * @package Poti\MosaicGallery
 * @version 1.0.0
 */

(function ($) {
  'use strict';

  $(document).ready(function () {
    /**
     * Image Upload Handler
     */
    let mediaUploader;

    $('#poti-add-images').on('click', function (e) {
      e.preventDefault();

      // If the uploader object has already been created, reopen it
      if (mediaUploader) {
        mediaUploader.open();
        return;
      }

      // Extend the wp.media object
      mediaUploader = wp.media.frames.file_frame = wp.media({
        title: 'Selecione Imagens para a Galeria',
        button: {
          text: 'Adicionar à Galeria',
        },
        multiple: true,
      });

      // When a file is selected, run a callback
      mediaUploader.on('select', function () {
        const attachments = mediaUploader.state().get('selection').toJSON();

        attachments.forEach(function (attachment) {
          addImageToGallery(attachment.id, attachment.url);
        });
      });

      // Open the uploader dialog
      mediaUploader.open();
    });

    /**
     * Add image to gallery list
     *
     * @param {number} id - Attachment ID
     * @param {string} url - Thumbnail URL
     */
    function addImageToGallery(id, url) {
      const $list = $('#poti-gallery-images-list');

      const $item = $(`
        <div class="poti-gallery-image-item" data-id="${id}">
          <img src="${url}" alt="">
          <button type="button" class="poti-remove-image">&times;</button>
          <input type="hidden" name="poti_gallery_images[]" value="${id}">
        </div>
      `);

      $list.append($item);
    }

    /**
     * Remove Image Handler
     */
    $(document).on('click', '.poti-remove-image', function (e) {
      e.preventDefault();
      $(this).closest('.poti-gallery-image-item').remove();
    });

    /**
     * Sortable Images (Drag & Drop)
     */
    if (typeof $.fn.sortable !== 'undefined') {
      $('#poti-gallery-images-list').sortable({
        items: '.poti-gallery-image-item',
        cursor: 'move',
        opacity: 0.6,
        placeholder: 'poti-gallery-image-placeholder',
        tolerance: 'pointer',
      });
    }
  });
})(jQuery);
