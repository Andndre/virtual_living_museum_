// Flag untuk mencegah multiple navigasi
let isNavigating = false;

// Fungsi untuk menangani navigasi back dengan fallback cerdas
export function initBackButton(buttonSelector, defaultFallbackUrl = null) {
    const backButtons = document.querySelectorAll(buttonSelector);

    if (backButtons.length > 0) {
        backButtons.forEach(button => {
            // Hapus semua event listener yang ada untuk mencegah duplikasi
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function (e) {
                // Cegah default action dan stop propagation
                e.preventDefault();
                e.stopPropagation();

                // Cegah multiple clicks
                if (isNavigating) return;
                isNavigating = true;

                // Nonaktifkan tombol sementara jika bertipe button
                if (this.tagName === 'BUTTON') {
                    this.disabled = true;
                }

                // Ambil fallback URL dari atribut atau parameter
                const fallback = this.getAttribute('data-fallback-url')
                    || (this.getAttribute('href') && this.getAttribute('href') !== '#' ? this.getAttribute('href') : null)
                    || defaultFallbackUrl;

                // Periksa apakah browser memiliki riwayat navigasi dari origin yang sama
                const hasSameOriginReferrer = document.referrer && document.referrer.startsWith(window.location.origin);
                const hasHistory = window.history.length > 1;

                try {
                    if (hasHistory && hasSameOriginReferrer) {
                        window.history.back();
                    } else if (fallback) {
                        window.location.href = fallback;
                    } else {
                        window.history.back();
                    }

                    // Reset flag setelah timeout
                    setTimeout(() => {
                        isNavigating = false;
                        if (this.tagName === 'BUTTON') {
                            this.disabled = false;
                        }
                    }, 1000);
                } catch (error) {
                    console.error('Navigation error:', error);
                    isNavigating = false;
                    if (this.tagName === 'BUTTON') {
                        this.disabled = false;
                    }
                    if (fallback) {
                        window.location.href = fallback;
                    }
                }
            });
        });
    }
}

// Inisialisasi otomatis jika ada elemen dengan class 'back-button'
document.addEventListener('DOMContentLoaded', () => {
    initBackButton('.back-button', window.location.origin);
});
