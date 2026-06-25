(function (window) {
    'use strict';

    var viteLoadPromise = null;

    function ensureViteEntry() {
        if (typeof window.mountReactApps === 'function') {
            return Promise.resolve();
        }

        if (!window.__vitePosEntry) {
            return Promise.resolve();
        }

        if (viteLoadPromise) {
            return viteLoadPromise;
        }

        viteLoadPromise = new Promise(function (resolve) {
            var script = document.createElement('script');
            script.type = 'module';
            script.src = window.__vitePosEntry;
            script.onload = resolve;
            script.onerror = resolve;
            document.body.appendChild(script);
        });

        return viteLoadPromise;
    }

    function bootPosApp(rootId, attempt) {
        attempt = attempt || 0;

        ensureViteEntry().then(function () {
            if (typeof window.mountReactApps === 'function') {
                window.mountReactApps();
            }

            var root = document.getElementById(rootId);
            if (!root) {
                return;
            }

            if (root.querySelector('.pos-shell__loading') && attempt < 60) {
                window.setTimeout(function () {
                    bootPosApp(rootId, attempt + 1);
                }, 100);
            }
        });
    }

    window.bootPosApp = bootPosApp;

    document.addEventListener('app:page-loaded', function () {
        if (document.getElementById('cart')) {
            bootPosApp('cart');
        }
        if (document.getElementById('purchase')) {
            bootPosApp('purchase');
        }
    });
}(window));
