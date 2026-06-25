(function (window, document) {
    'use strict';

    var loading = false;

    function normalizePath(path) {
        var normalized = path.replace(/\/+$/, '');
        return normalized || '/';
    }

    function shouldUsePartialNav(link) {
        if (!link || link.dataset.partialNav === 'false') {
            return false;
        }

        if (link.target === '_blank' || link.hasAttribute('download')) {
            return false;
        }

        if (link.closest('.dataTables_wrapper') && !link.closest('.table-actions-inline') && !link.classList.contains('table-actions-btn')) {
            return false;
        }

        if (link.origin && link.origin !== window.location.origin) {
            return false;
        }

        var href = link.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) {
            return false;
        }

        var path = normalizePath(link.pathname);
        if (path.indexOf('/logout') !== -1 || path.indexOf('/print') !== -1) {
            return false;
        }

        return true;
    }

    function mergeHeadAssets(doc) {
        doc.querySelectorAll('head link[rel="stylesheet"]').forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href) {
                return;
            }

            if (!document.querySelector('link[rel="stylesheet"][href="' + href + '"]')) {
                document.head.appendChild(link.cloneNode(true));
            }
        });
    }

    function runScriptElement(oldScript) {
        return new Promise(function (resolve) {
            var script = document.createElement('script');

            if (oldScript.src) {
                if (document.querySelector('script[src="' + oldScript.src + '"]')) {
                    resolve();
                    return;
                }

                script.src = oldScript.src;
                script.async = false;
                script.onload = function () {
                    resolve();
                };
                script.onerror = function () {
                    resolve();
                };
            } else {
                script.textContent = oldScript.textContent;
            }

            document.body.appendChild(script);

            if (!oldScript.src) {
                resolve();
            }
        });
    }

    function runScripts(container) {
        if (!container) {
            return Promise.resolve();
        }

        var scripts = Array.prototype.slice.call(container.querySelectorAll('script'));

        return scripts.reduce(function (chain, oldScript) {
            return chain.then(function () {
                return runScriptElement(oldScript);
            });
        }, Promise.resolve());
    }

    function updateSidebarNav() {
        var sidebar = document.querySelector('.sidebar-modern .nav-sidebar');
        if (!sidebar) {
            return;
        }

        var path = normalizePath(window.location.pathname);
        var bestMatch = null;
        var bestLength = 0;

        sidebar.querySelectorAll('.nav-item.menu-open').forEach(function (item) {
            item.classList.remove('menu-open');
        });
        sidebar.querySelectorAll('.nav-link.active').forEach(function (link) {
            link.classList.remove('active');
        });

        sidebar.querySelectorAll('.nav-link[href]').forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href || href === '#') {
                return;
            }

            var linkPath;
            try {
                linkPath = normalizePath(new URL(href, window.location.origin).pathname);
            } catch (error) {
                return;
            }

            if (path === linkPath || (linkPath.length > 1 && path.indexOf(linkPath + '/') === 0)) {
                if (linkPath.length >= bestLength) {
                    bestLength = linkPath.length;
                    bestMatch = link;
                }
            }
        });

        if (!bestMatch) {
            return;
        }

        bestMatch.classList.add('active');

        var subTree = bestMatch.closest('.nav-treeview');
        if (subTree) {
            var parentItem = subTree.closest('.nav-item');
            if (parentItem) {
                parentItem.classList.add('menu-open');
                var parentLink = parentItem.querySelector(':scope > .nav-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
            return;
        }

        if (typeof window.closeAllSidebarSubmenus === 'function') {
            window.closeAllSidebarSubmenus();
        }
    }

    function dispatchPageLoaded() {
        document.dispatchEvent(new CustomEvent('app:page-loaded'));
        if (window.jQuery) {
            window.jQuery(document).trigger('app:page-loaded');
        }

        if (typeof window.mountReactApps === 'function') {
            window.mountReactApps();
        }

        window.requestAnimationFrame(function () {
            if (typeof window.mountReactApps === 'function') {
                window.mountReactApps();
            }
        });
    }

    function setLoadingState(isLoading) {
        var root = document.getElementById('app-page-root');
        if (root) {
            root.classList.toggle('is-loading', isLoading);
        }
    }

    function swapPartialPage(doc) {
        var nextRoot = doc.getElementById('app-page-root');
        var nextScripts = doc.getElementById('page-scripts');
        var currentRoot = document.getElementById('app-page-root');
        var currentScripts = document.getElementById('page-scripts');

        if (!nextRoot || !currentRoot) {
            return false;
        }

        mergeHeadAssets(doc);

        var title = doc.querySelector('title');
        if (title) {
            document.title = title.textContent;
        }

        currentRoot.replaceWith(document.importNode(nextRoot, true));

        if (currentScripts && nextScripts) {
            currentScripts.replaceWith(document.importNode(nextScripts, true));
        }

        return true;
    }

    function finishPageSwap(url, pushState) {
        if (pushState !== false && url) {
            window.history.pushState({ partialNav: true }, '', url);
        }

        updateSidebarNav();
        window.scrollTo({ top: 0, behavior: 'auto' });
        loading = false;
        setLoadingState(false);
        dispatchPageLoaded();
    }

    function navigate(url, pushState) {
        if (loading) {
            return Promise.resolve();
        }

        loading = true;
        setLoadingState(true);

        return fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'text/html',
                'X-Partial-Navigation': '1',
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Navigation failed');
                }
                return response.text();
            })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');

                if (!swapPartialPage(doc)) {
                    window.location.href = url;
                    return;
                }

                return runScripts(document.getElementById('page-scripts')).then(function () {
                    finishPageSwap(url, pushState);
                });
            })
            .catch(function () {
                loading = false;
                setLoadingState(false);
                window.location.href = url;
            });
    }

    document.addEventListener('click', function (event) {
        if (event.defaultPrevented || event.button !== 0) {
            return;
        }

        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        var link = event.target.closest('a[href]');
        if (!link || !shouldUsePartialNav(link)) {
            return;
        }

        var inShell = link.closest('.main-sidebar, .main-header, .content-wrapper, #app-page-root');
        if (!inShell) {
            return;
        }

        event.preventDefault();
        navigate(link.href);
    });

    window.addEventListener('popstate', function () {
        navigate(window.location.href, false);
    });

    document.addEventListener('DOMContentLoaded', updateSidebarNav);
    function applyHtml(html, url, pushState) {
        var doc = new DOMParser().parseFromString(html, 'text/html');

        if (!swapPartialPage(doc)) {
            window.location.href = url || window.location.href;
            return Promise.resolve();
        }

        return runScripts(document.getElementById('page-scripts')).then(function () {
            finishPageSwap(url, pushState);
        });
    }

    window.AppNavigation = {
        navigate: navigate,
        applyHtml: applyHtml,
        updateSidebarNav: updateSidebarNav,
    };
}(window, document));
