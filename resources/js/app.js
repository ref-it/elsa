import './bootstrap';

// Registers cropperjs v2's custom elements (<cropper-canvas> and friends) -
// v2 has no `new Cropper(img, options)` constructor to expose globally
// anymore, the markup lives in the views (see <x-image-cropper />).
import 'cropperjs';

// How much of the image the crop box covers when a file is picked (the same
// share v1 used for its `autoCropArea`).
const INITIAL_COVERAGE = 0.8;

document.addEventListener('alpine:init', () => {
    // Wires the cropper elements up to the Livewire component they sit in:
    // the picked file goes into <cropper-image>, the selection comes back
    // out as a JPEG data URL in the `imageCropped` property.
    Alpine.data('cropper', () => ({
        imageIsSelected: false,
        objectUrl: null,
        saving: false,

        initCropper() {
            const selection = this.$refs.selection;

            // Keep the crop box from being dragged/resized past the image's
            // own edges, into the empty canvas area (cropperjs v2 no longer
            // has a built-in equivalent of v1's viewMode).
            let clamping = false;

            selection.addEventListener('change', (event) => {
                if (clamping) {
                    return;
                }

                const canvasRect = this.$refs.canvas.getBoundingClientRect();
                const imageRect = this.$refs.image.getBoundingClientRect();

                if (imageRect.width === 0 || imageRect.height === 0) {
                    return;
                }

                const minX = imageRect.left - canvasRect.left;
                const minY = imageRect.top - canvasRect.top;

                let { x, y, width, height } = event.detail;

                // Shrink both dimensions by the same factor, so a crop box
                // larger than the image keeps its shape instead of being
                // re-expanded by cropperjs on the next change event.
                const scale = Math.min(1, imageRect.width / width, imageRect.height / height);
                width *= scale;
                height *= scale;
                x = Math.min(Math.max(x, minX), minX + imageRect.width - width);
                y = Math.min(Math.max(y, minY), minY + imageRect.height - height);

                if (x !== event.detail.x || y !== event.detail.y || width !== event.detail.width || height !== event.detail.height) {
                    event.preventDefault();
                    clamping = true;
                    selection.$change(x, y, width, height);
                    clamping = false;
                }
            });
        },

        loadImage(event) {
            const file = event.target.files[0];

            if (! file) {
                return;
            }

            if (file.type.indexOf('image/') === -1) {
                alert('Bitte wähle eine Bild-Datei aus. / Please select an image file.');
                event.target.value = '';

                return;
            }

            this.revokeObjectUrl();
            this.objectUrl = URL.createObjectURL(file);
            this.imageIsSelected = true;

            this.$nextTick(() => {
                this.$refs.image.setAttribute('src', this.objectUrl);
                this.$refs.image
                    .$ready(() => this.fitImage())
                    .catch(() => {
                        alert('Das Bild konnte nicht geladen werden. / The image could not be loaded.');
                        this.resetImage();
                    });
            });
        },

        // cropperjs fits the image into the canvas while the image is still
        // loading - at that point the canvas is the one that was hidden until
        // a file got picked, so it has no size yet and the image ends up
        // unscaled, overflowing the canvas. Fit it (and place the crop box
        // over it) once it is really there.
        fitImage() {
            if (! this.imageIsSelected) {
                return;
            }

            const canvasRect = this.$refs.canvas.getBoundingClientRect();

            // x-show has not unhidden the canvas yet - measuring now would
            // fit the image into a 0x0 box and leave it at its natural size.
            if (canvasRect.width === 0 || canvasRect.height === 0) {
                requestAnimationFrame(() => this.fitImage());

                return;
            }

            const image = this.$refs.image;

            // $center() grants itself the scalable/translatable flags it needs
            // only while the image is still loading - called later, on an
            // image that is neither (we keep both off so the picture can't be
            // panned or zoomed, like v1's `zoomable: false`), it silently does
            // nothing. So hand it the flags the same way cropperjs does.
            const { scalable, translatable } = image;

            image.scalable = true;
            image.translatable = true;
            image.$center('contain');
            image.scalable = scalable;
            image.translatable = translatable;

            const imageRect = image.getBoundingClientRect();
            const width = imageRect.width * INITIAL_COVERAGE;
            const height = imageRect.height * INITIAL_COVERAGE;

            this.$refs.selection.$change(
                imageRect.left - canvasRect.left + ((imageRect.width - width) / 2),
                imageRect.top - canvasRect.top + ((imageRect.height - height) / 2),
                width,
                height,
            );
        },

        async cropImage() {
            if (this.saving || ! this.imageIsSelected) {
                return;
            }

            this.saving = true;

            try {
                const selection = this.$refs.selection;
                const image = this.$refs.image;
                const imageRect = image.getBoundingClientRect();

                if (selection.width === 0 || selection.height === 0 || imageRect.width === 0) {
                    return;
                }

                // Export at the uploaded image's own resolution: $toCanvas()
                // defaults to the selection's on-screen size, which would
                // hand the server a picture downscaled to the preview's
                // height (v1's getCroppedCanvas() used the natural size).
                const scale = image.$image.naturalWidth / imageRect.width;
                const canvas = await selection.$toCanvas({
                    width: Math.round(selection.width * scale),
                    height: Math.round(selection.height * scale),
                });

                this.$wire.imageCropped = canvas.toDataURL('image/jpeg');

                await this.$wire.saveImage();

                this.resetImage();
            } finally {
                this.saving = false;
            }
        },

        removeImage() {
            this.$wire.removeImage();
            this.resetImage();
        },

        cancelImage() {
            this.resetImage();
        },

        resetImage() {
            this.imageIsSelected = false;

            // The refs are gone once Livewire re-renders into the
            // "picture already saved" branch, hence the guarded calls.
            this.$refs.image?.removeAttribute('src');

            if (this.$refs.input) {
                this.$refs.input.value = '';
            }

            this.revokeObjectUrl();
        },

        revokeObjectUrl() {
            if (this.objectUrl) {
                URL.revokeObjectURL(this.objectUrl);
                this.objectUrl = null;
            }
        },

        destroy() {
            this.revokeObjectUrl();
        },
    }));
});
