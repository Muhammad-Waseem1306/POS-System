// import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import Pos from './components/Pos';
import Purchase from './components/Purchase/Purchase';
import './bootstrap';

const mounts = {
    cart: { container: null, root: null },
    purchase: { container: null, root: null },
};

function mountApp(key, elementId, Component) {
    const el = document.getElementById(elementId);
    const state = mounts[key];

    if (!el) {
        if (state.root) {
            state.root.unmount();
            state.root = null;
            state.container = null;
        }
        return;
    }

    const needsMount = state.container !== el || !!el.querySelector('.pos-shell__loading');

    if (!needsMount) {
        return;
    }

    if (state.root) {
        state.root.unmount();
    }

    state.container = el;
    state.root = createRoot(el);
    state.root.render(<Component />);
}

function mountReactApps() {
    mountApp('cart', 'cart', Pos);
    mountApp('purchase', 'purchase', Purchase);
}

window.mountReactApps = mountReactApps;

function scheduleMountReactApps() {
    window.requestAnimationFrame(function () {
        mountReactApps();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', scheduleMountReactApps);
} else {
    scheduleMountReactApps();
}

document.addEventListener('app:page-loaded', scheduleMountReactApps);
