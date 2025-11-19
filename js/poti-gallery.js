/**
 * Poti Gallery - Scripts Frontais
 */
(function($) {
    'use strict';

    class PotiGallery {
        constructor() {
            this.init();
        }

        init() {
            this.initLightbox();
            this.initCarousel();
        }

        initLightbox() {
            const galleries = document.querySelectorAll('.poti-gallery');

            galleries.forEach(gallery => {
                const lightboxEnabled = gallery.getAttribute('data-lightbox') === 'yes';

                if (!lightboxEnabled) return;

                const galleryId = gallery.getAttribute('data-gallery-id');
                const links = gallery.querySelectorAll('.poti-gallery-link');

                links.forEach((link, index) => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openLightbox(links, index);
                    });
                });
            });
        }

        openLightbox(links, currentIndex) {
            const images = Array.from(links).map(link => ({
                src: link.getAttribute('href'),
                title: link.getAttribute('data-title')
            }));

            const lightbox = this.createLightboxElement();
            document.body.appendChild(lightbox);
            document.body.style.overflow = 'hidden';

            this.showImage(lightbox, images, currentIndex);

            // Navegação
            lightbox.querySelector('.poti-lightbox-prev').addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                this.showImage(lightbox, images, currentIndex);
            });

            lightbox.querySelector('.poti-lightbox-next').addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % images.length;
                this.showImage(lightbox, images, currentIndex);
            });

            // Fechar
            const close = () => {
                lightbox.remove();
                document.body.style.overflow = '';
            };

            lightbox.querySelector('.poti-lightbox-close').addEventListener('click', close);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) close();
            });

            // Teclado
            const handleKeyboard = (e) => {
                if (e.key === 'Escape') {
                    close();
                    document.removeEventListener('keydown', handleKeyboard);
                } else if (e.key === 'ArrowLeft') {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    this.showImage(lightbox, images, currentIndex);
                } else if (e.key === 'ArrowRight') {
                    currentIndex = (currentIndex + 1) % images.length;
                    this.showImage(lightbox, images, currentIndex);
                }
            };

            document.addEventListener('keydown', handleKeyboard);
        }

        createLightboxElement() {
            const lightbox = document.createElement('div');
            lightbox.className = 'poti-lightbox active';
            lightbox.innerHTML = `
                <button class="poti-lightbox-close">×</button>
                <button class="poti-lightbox-nav poti-lightbox-prev">‹</button>
                <button class="poti-lightbox-nav poti-lightbox-next">›</button>
                <div class="poti-lightbox-content">
                    <img class="poti-lightbox-image" src="" alt="">
                    <div class="poti-lightbox-title"></div>
                </div>
            `;
            return lightbox;
        }

        showImage(lightbox, images, index) {
            const img = lightbox.querySelector('.poti-lightbox-image');
            const title = lightbox.querySelector('.poti-lightbox-title');

            img.src = images[index].src;
            img.alt = images[index].title || '';
            title.textContent = images[index].title || '';

            if (!images[index].title) {
                title.style.display = 'none';
            } else {
                title.style.display = 'block';
            }
        }

        initCarousel() {
            const carousels = document.querySelectorAll('.poti-gallery-carousel');

            carousels.forEach(carousel => {
                let isDown = false;
                let startX;
                let scrollLeft;

                carousel.addEventListener('mousedown', (e) => {
                    isDown = true;
                    carousel.style.cursor = 'grabbing';
                    startX = e.pageX - carousel.offsetLeft;
                    scrollLeft = carousel.scrollLeft;
                });

                carousel.addEventListener('mouseleave', () => {
                    isDown = false;
                    carousel.style.cursor = 'grab';
                });

                carousel.addEventListener('mouseup', () => {
                    isDown = false;
                    carousel.style.cursor = 'grab';
                });

                carousel.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - carousel.offsetLeft;
                    const walk = (x - startX) * 2;
                    carousel.scrollLeft = scrollLeft - walk;
                });
            });
        }
    }

    // Inicializa quando o DOM estiver pronto
    $(document).ready(function() {
        new PotiGallery();
    });

})(jQuery);
