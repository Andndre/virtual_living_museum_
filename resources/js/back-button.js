// Flag untuk mencegah multiple navigasi
let isNavigating = false;

// Fungsi untuk menangani navigasi back
export function initBackButton(buttonSelector) {
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

                // Nonaktifkan tombol sementara
                this.disabled = true;

                try {
                    // Coba kembali ke halaman sebelumnya
                    window.history.go(-1);

                    // Reset flag setelah timeout
                    setTimeout(() => {
                        isNavigating = false;
                        this.disabled = false;
                    }, 1000);
                } catch (error) {
                    console.error('Navigation error:', error);
                    isNavigating = false;
                    this.disabled = false;
                }
            });
        });
    }
}

// Inisialisasi otomatis jika ada elemen dengan class 'back-button'
document.addEventListener('DOMContentLoaded', () => {
    initBackButton('.back-button', window.location.origin);
});
