import { PageFlip } from 'page-flip';

// PDF.js import (gunakan CDN atau import jika sudah diinstall)
// import * as pdfjsLib from 'pdfjs-dist';

window.initEbookPageFlip = function({ pdfUrl, totalPages, materiUrl, ebookId, csrfToken }) {
    // Helper untuk deteksi fullscreen
    function isFullscreen() {
        return document.fullscreenElement === flipbookContainer ||
            document.webkitFullscreenElement === flipbookContainer ||
            document.mozFullScreenElement === flipbookContainer ||
            document.msFullscreenElement === flipbookContainer;
    }
    // Ukuran fix untuk flipbook dan render PDF agar anti-blur
    const PAGE_WIDTH = 1400;
    const PAGE_HEIGHT = 1980;
		let pdfDoc = null;
    let currentPage = 1;
    let scale = 1.0;
    let renderedPages = {};
    let flipbookInitialized = false;
    let pageFlip = null;

    // Overlay logic
    const overlay = document.getElementById('flipbook-start-overlay');
    const flipbookContainer = document.getElementById('flipbook-container');
    function openFullscreen(elem, callback) {
        let fullscreenPromise;
        if (elem.requestFullscreen) {
            fullscreenPromise = elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            fullscreenPromise = elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            fullscreenPromise = elem.msRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            fullscreenPromise = elem.mozRequestFullScreen();
        }
        if (fullscreenPromise && typeof fullscreenPromise.then === 'function') {
            fullscreenPromise.then(() => {
                if (typeof callback === 'function') callback();
            });
        } else {
            setTimeout(() => { if (typeof callback === 'function') callback(); }, 500);
        }
    }
    if (overlay) {
        overlay.addEventListener('click', function() {
            overlay.style.display = 'none';
            // openFullscreen(flipbookContainer, function() {
            //     setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 300);
            // });
            initializePDFViewer();
        });
    } else {
        initializePDFViewer();
    }

    // Show finish dialog
    function showFinishDialog() {
        if (document.getElementById('finish-dialog')) return;
        const dialog = document.createElement('div');
        dialog.id = 'finish-dialog';
        dialog.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black/40';
        dialog.innerHTML = `
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-xs w-full text-center animate-fadeIn">
                <h2 class="text-lg font-bold mb-2">E-Book Selesai Dibaca!</h2>
                <p class="mb-4 text-gray-700">Anda telah membaca semua halaman e-book ini.<br>Lanjut ke materi atau tetap membaca?</p>
                <div class="flex justify-center gap-2">
                    <button id="btn-to-materi" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Kembali ke Materi</button>
                    <button id="btn-continue-reading" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Tetap Membaca</button>
                </div>
            </div>
        `;
        document.body.appendChild(dialog);
        document.getElementById('btn-to-materi').onclick = function() {
            window.location.href = materiUrl;
        };
        document.getElementById('btn-continue-reading').onclick = function() {
            dialog.remove();
        };
    }

    // Main initialization function
    function initializePDFViewer() {
        window['pdfjsLib'].getDocument(pdfUrl).promise.then(function(pdf) {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            document.getElementById('total-pages').textContent = totalPages;
            renderMultiplePages().then(() => {
                initializePageFlip();
                hideLoading();
            });
        }).catch(function(error) {
            showError(error);
        });
    }

    async function renderMultiplePages() {
        const promises = [];
        for (let i = 1; i <= totalPages; i++) {
            promises.push(renderPageToCanvas(i));
        }
        // Tunggu semua canvas selesai render
        await Promise.all(promises);
    }

    function renderPageToCanvas(pageNum) {
        return new Promise((resolve) => {
            if (renderedPages[pageNum]) {
                resolve(renderedPages[pageNum]);
                return;
            }
            window['pdfjsLib'].getDocument(pdfUrl).promise.then(function(pdf) {
                pdf.getPage(pageNum).then(function(page) {
                    const devicePixelRatio = window.devicePixelRatio || 1;
                    // Skala agar hasil render PDF = PAGE_WIDTH x PAGE_HEIGHT
                    const pdfOriginalWidth = page.view[2];
                    const pdfOriginalHeight = page.view[3];
                    const scaleX = (PAGE_WIDTH * devicePixelRatio) / pdfOriginalWidth;
                    const scaleY = (PAGE_HEIGHT * devicePixelRatio) / pdfOriginalHeight;
										console.log(`Rendering page ${pageNum} with scaleX: ${scaleX}, scaleY: ${scaleY}`);
                    const renderScale = Math.min(scaleX, scaleY);
                    const viewport = page.getViewport({ scale: renderScale });
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    // Set style width/height agar sesuai flipbook (CSS), tapi canvas-nya tetap resolusi tinggi
                    canvas.style.width = PAGE_WIDTH + 'px';
                    canvas.style.height = PAGE_HEIGHT + 'px';
                    const renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    page.render(renderContext).promise.then(function() {
                        renderedPages[pageNum] = canvas;
                        resolve(canvas);
                    });
                });
            });
        });
    }

    function initializePageFlip() {
        const viewer = document.getElementById('pageflip-viewer');
        viewer.innerHTML = '';
        pageFlip = new PageFlip(viewer, {
            width: PAGE_WIDTH,
            height: PAGE_HEIGHT,
            size: 'stretch',
            minWidth: 315,
            minHeight: 420,
            maxWidth: PAGE_WIDTH,
            maxHeight: PAGE_HEIGHT,
            drawShadow: true,
            flippingTime: 700,
            usePortrait: true,
            startPage: 0,
            autoSize: true,
            showCover: false,
            mobileScrollSupport: false,
            swipeDistance: 30,
            clickEventForward: true,
            useMouseEvents: true,
            showPageCorners: true,
            mode: 'single'
        });
        // Kumpulkan semua canvas hasil render
        const canvases = [];
        for (let i = 1; i <= totalPages; i++) {
            if (renderedPages[i]) {
                canvases.push(renderedPages[i]);
            } else {
                // Jika gagal render, tambahkan canvas kosong
                const blank = document.createElement('canvas');
                blank.width = PAGE_WIDTH;
                blank.height = PAGE_HEIGHT;
                canvases.push(blank);
            }
        }
        // Masukkan langsung canvas ke PageFlip
        pageFlip.loadFromHTML(canvases);
        flipbookInitialized = true;
        updatePageInfo();
        updateNavigationButtons();
        pageFlip.on('flip', (e) => {
            currentPage = e.data + 1;
            updatePageInfo();
            updateNavigationButtons();
            if (currentPage === totalPages) {
                // Jika sedang fullscreen, keluar dari fullscreen
                if (isFullscreen()) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen().then(r => {
                            console.log('Fullscreen exit success');
                        });
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
                fetch(`/kunjungi-peninggalan/ebook/${ebookId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    showFinishDialog();
                });
            }
            // Update nomor halaman di fullscreen
            updateFullscreenPageIndicator();
        });

        // Tampilkan/hide tombol navigasi & nomor halaman di fullscreen
        document.addEventListener('fullscreenchange', handleFullscreenUI);
        document.addEventListener('webkitfullscreenchange', handleFullscreenUI);
        document.addEventListener('mozfullscreenchange', handleFullscreenUI);
        document.addEventListener('MSFullscreenChange', handleFullscreenUI);
        handleFullscreenUI();
    }

    function updatePageInfo() {
        document.getElementById('current-page').textContent = currentPage;
    }
    function updateNavigationButtons() {
        document.getElementById('prev-page').disabled = currentPage <= 1;
        document.getElementById('next-page').disabled = currentPage >= totalPages;
        // Update tombol fullscreen jika ada
        updateFullscreenNavButtons();
    }

    // Tampilkan nomor halaman di fullscreen
    function updateFullscreenPageIndicator() {
        const indicator = document.getElementById('fullscreen-page-indicator');
        const controls = document.getElementById('fullscreen-controls');
        if (isFullscreen()) {
            indicator.textContent = `${currentPage} / ${totalPages}`;
            controls.classList.remove('hidden');
        } else {
            controls.classList.add('hidden');
        }
    }

    // Tampilkan tombol prev/next di fullscreen
    function updateFullscreenNavButtons() {
        const prevBtn = document.getElementById('fullscreen-prev');
        const nextBtn = document.getElementById('fullscreen-next');
        if (isFullscreen()) {
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
        }
    }

    // Handler untuk perubahan fullscreen
    function handleFullscreenUI() {
        updateFullscreenPageIndicator();
        updateFullscreenNavButtons();
    }

    // Event tombol prev/next fullscreen
    document.getElementById('fullscreen-prev').addEventListener('click', function(e) {
        e.stopPropagation();
        if (flipbookInitialized && pageFlip && currentPage > 1) {
            pageFlip.flipPrev();
        }
    });
    document.getElementById('fullscreen-next').addEventListener('click', function(e) {
        e.stopPropagation();
        if (flipbookInitialized && pageFlip && currentPage < totalPages) {
            pageFlip.flipNext();
        }
    });

    function hideLoading() {
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer && loadingContainer.style) {
            loadingContainer.style.display = 'none';
        } else if (loadingContainer && loadingContainer.parentNode) {
            loadingContainer.parentNode.removeChild(loadingContainer);
        }
    }
    function showError(error) {
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer && loadingContainer.parentNode) {
            loadingContainer.parentNode.removeChild(loadingContainer);
        }
        const container = document.getElementById('flipbook-container');
        let errorMsg = 'Gagal memuat e-book';
        if (error && error.message) {
            errorMsg += `<br><span class='text-xs text-red-500'>${error.message}</span>`;
        }
        container.innerHTML = `
            <div class="flex items-center justify-center h-96">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <p class="text-gray-600">${errorMsg}</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Coba Lagi
                    </button>
                </div>
            </div>
        `;
    }

    document.getElementById('prev-page').addEventListener('click', function() {
        if (flipbookInitialized && pageFlip) {
            pageFlip.flipPrev();
        }
    });
    document.getElementById('next-page').addEventListener('click', function() {
        if (flipbookInitialized && pageFlip) {
            pageFlip.flipNext();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            if (flipbookInitialized && pageFlip) {
                pageFlip.flipPrev();
            }
        } else if (e.key === 'ArrowRight') {
            if (flipbookInitialized && pageFlip) {
                pageFlip.flipNext();
            }
        }
    });
}
