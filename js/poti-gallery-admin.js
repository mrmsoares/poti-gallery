/**
 * Poti Gallery - Scripts Admin
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        let galleryImages = [];

        // Carrega imagens existentes
        const existingImages = $('#poti-gallery-images-input').val();
        if (existingImages) {
            try {
                galleryImages = JSON.parse(existingImages);
            } catch (e) {
                galleryImages = [];
            }
        }

        // Media Uploader
        let mediaUploader;

        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Adicionar Imagens à Galeria',
                button: {
                    text: 'Adicionar à Galeria'
                },
                multiple: true
            });

            mediaUploader.on('select', function() {
                const attachments = mediaUploader.state().get('selection').toJSON();

                attachments.forEach(function(attachment) {
                    if (galleryImages.indexOf(attachment.id) === -1) {
                        galleryImages.push(attachment.id);
                        addImageToGallery(attachment);
                    }
                });

                updateHiddenInput();
            });

            mediaUploader.open();
        });

        // Adiciona imagem ao container
        function addImageToGallery(attachment) {
            const thumbnail = attachment.sizes && attachment.sizes.thumbnail
                ? attachment.sizes.thumbnail.url
                : attachment.url;

            const imageHtml = `
                <div class="gallery-image-item" data-id="${attachment.id}">
                    <img src="${thumbnail}" />
                    <button type="button" class="remove-image">×</button>
                </div>
            `;

            $('#poti-gallery-images').append(imageHtml);
        }

        // Remove imagem
        $(document).on('click', '.remove-image', function(e) {
            e.preventDefault();
            const imageItem = $(this).closest('.gallery-image-item');
            const imageId = parseInt(imageItem.data('id'));

            imageItem.fadeOut(300, function() {
                $(this).remove();
            });

            const index = galleryImages.indexOf(imageId);
            if (index > -1) {
                galleryImages.splice(index, 1);
            }

            updateHiddenInput();
        });

        // Atualiza input hidden
        function updateHiddenInput() {
            $('#poti-gallery-images-input').val(JSON.stringify(galleryImages));
        }

        // Sortable (reordenar imagens)
        if ($.fn.sortable) {
            $('#poti-gallery-images').sortable({
                placeholder: 'ui-sortable-placeholder',
                update: function() {
                    galleryImages = [];
                    $('#poti-gallery-images .gallery-image-item').each(function() {
                        galleryImages.push(parseInt($(this).data('id')));
                    });
                    updateHiddenInput();
                }
            });
        }

        // Copia shortcode ao clicar
        $(document).on('click', '.poti-gallery-settings code', function() {
            const code = $(this).text();
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(code).select();
            document.execCommand('copy');
            $temp.remove();

            // Feedback visual
            const originalText = $(this).text();
            $(this).text('Copiado!').css('background', '#46b450');
            setTimeout(() => {
                $(this).text(originalText).css('background', '#f5f5f5');
            }, 1500);
        });
    });

})(jQuery);
