/* ----------------------------------------------------------------------
 * The catalogue editor's own behaviour. It is loaded only by the editor's
 * layout, so none of it reaches a visitor browsing the collection.
 *
 * Everything here is a convenience: the form submits and validates perfectly
 * well with JavaScript switched off.
 * ---------------------------------------------------------------------- */

/** Turkish letters have no place in a URL, so they are written out. */
function slugify(value) {
    const turkish = { ç: 'c', ğ: 'g', ı: 'i', ö: 'o', ş: 's', ü: 'u', İ: 'i' };

    return value
        .replace(/[çğıöşüİ]/g, (letter) => turkish[letter] ?? letter)
        .toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/**
 * Fills the address from the name while the address is still untouched. Once
 * it has been edited by hand, or on a piece that is already published, it is
 * left alone — changing a live address breaks every link to it.
 */
function watchSlug() {
    const source = document.querySelector('[data-slug-source]');
    const target = document.querySelector('[data-slug-target]');
    if (!source || !target) return;

    let linked = target.value === '';
    target.addEventListener('input', () => { linked = false; });

    source.addEventListener('input', () => {
        if (linked) target.value = slugify(source.value);
    });
}

/** The photo picker writes into the path field, which stays editable. */
function watchImage() {
    const input = document.querySelector('[data-image-input]');
    const preview = document.querySelector('[data-image-preview]');
    if (!input) return;

    const show = () => {
        if (!preview) return;
        const path = input.value.trim();

        if (path === '') {
            preview.removeAttribute('src');
            preview.classList.add('hidden');

            return;
        }

        preview.src = /^https?:\/\//.test(path) ? path : '/' + path.replace(/^\/+/, '');
        preview.classList.remove('hidden');
    };

    document.querySelectorAll('[data-pick-image]').forEach((button) => {
        button.addEventListener('click', () => {
            input.value = button.dataset.pickImage;
            show();
        });
    });

    input.addEventListener('input', show);
    show();
}

/** Shows the chosen file before it is uploaded, so a mistake is obvious. */
function watchPhoto() {
    const input = document.querySelector('[data-photo-input]');
    const preview = document.querySelector('[data-image-preview]');
    if (!input || !preview) return;

    input.addEventListener('change', () => {
        const file = input.files?.[0];
        if (!file) return;

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
}

/** The swatch and the hex box are two views of one value. */
function watchColour() {
    const picker = document.querySelector('[data-colour-picker]');
    const text = document.querySelector('[data-colour-text]');
    if (!picker || !text) return;

    picker.addEventListener('input', () => { text.value = picker.value.toUpperCase(); });

    text.addEventListener('input', () => {
        if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    watchSlug();
    watchImage();
    watchColour();
    watchPhoto();
});
