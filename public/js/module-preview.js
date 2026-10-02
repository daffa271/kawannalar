/**
 * Pratinjau modul Ruang Nalar (PDF / gambar) yang tetap tampil di HP.
 *
 * PDF dirender ke <canvas> dengan PDF.js (dimuat dari cdnjs hanya saat dibutuhkan), karena
 * Chrome Android tidak bisa menampilkan PDF di dalam <iframe>.
 * Buka lewat event: $dispatch('module-preview', { title, url, download, type: 'pdf' | 'image' }).
 */
(function () {
    'use strict';

    const PDFJS_BASE = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/';
    const MAX_PAGES = 10;
    let pdfJsLoader = null;

    function loadPdfJs() {
        if (window.pdfjsLib) return Promise.resolve(window.pdfjsLib);
        if (pdfJsLoader) return pdfJsLoader;

        pdfJsLoader = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = PDFJS_BASE + 'pdf.min.js';
            script.onload = () => {
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_BASE + 'pdf.worker.min.js';
                resolve(window.pdfjsLib);
            };
            script.onerror = () => {
                pdfJsLoader = null;
                reject(new Error('PDF.js gagal dimuat'));
            };
            document.head.appendChild(script);
        });

        return pdfJsLoader;
    }

    window.modulePreview = function () {
        return {
            open: false,
            title: '',
            url: '',
            download: '',
            type: 'pdf',
            state: 'loading',   // loading | ready | error
            pageCount: 0,
            shown: 0,
            task: null,
            token: 0,

            show(detail) {
                this.close();
                Object.assign(this, {
                    title: detail.title,
                    url: detail.url,
                    download: detail.download,
                    type: detail.type === 'image' ? 'image' : 'pdf',
                    state: 'loading',
                    pageCount: 0,
                    shown: 0,
                    open: true,
                });
                document.documentElement.classList.add('overflow-hidden');

                if (this.type === 'pdf') this.renderPdf(++this.token);
            },

            close() {
                this.token++;
                if (this.task) {
                    this.task.destroy();
                    this.task = null;
                }
                if (this.$refs.pages) this.$refs.pages.innerHTML = '';
                this.open = false;
                document.documentElement.classList.remove('overflow-hidden');
            },

            async renderPdf(token) {
                try {
                    const pdfjs = await loadPdfJs();
                    if (token !== this.token) return;

                    this.task = pdfjs.getDocument(this.url);
                    const pdf = await this.task.promise;
                    if (token !== this.token) return;

                    this.pageCount = pdf.numPages;
                    const container = this.$refs.pages;
                    const width = container.clientWidth || Math.min(window.innerWidth - 32, 768);
                    const ratio = Math.min(window.devicePixelRatio || 1, 2);

                    for (let number = 1; number <= Math.min(pdf.numPages, MAX_PAGES); number++) {
                        const page = await pdf.getPage(number);
                        if (token !== this.token) return;

                        const viewport = page.getViewport({ scale: (width / page.getViewport({ scale: 1 }).width) * ratio });
                        const canvas = document.createElement('canvas');
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        canvas.className = 'block w-full rounded-lg bg-white shadow';
                        canvas.setAttribute('role', 'img');
                        canvas.setAttribute('aria-label', 'Halaman ' + number);
                        container.appendChild(canvas);

                        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                        if (token !== this.token) return;

                        this.shown = number;
                        this.state = 'ready';
                    }
                } catch (e) {
                    if (token === this.token) this.state = 'error';
                }
            },
        };
    };
})();
