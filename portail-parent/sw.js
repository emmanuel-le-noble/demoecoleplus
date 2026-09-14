// ==========================================================================
// SERVICE WORKER - Ecole Plus Portail Parent
// Version : 1.2.0 - 2026-07-05
// Stratégie : Network First (toujours données fraîches, fallback cache)
//              + Cache First pour assets statiques
//              + Fallback offline.php
// ==========================================================================

const CACHE_NAME    = 'ecoleplus-v1.9';
const OFFLINE_URL   = '/demoecoleplus/portail-parent/offline.php';

// Ressources statiques à mettre en cache immédiatement lors de l'installation
const STATIC_ASSETS = [
    '/demoecoleplus/portail-parent/assets/css/style.css',
    '/demoecoleplus/portail-parent/offline.php',
    '/demoecoleplus/portail-parent/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
    'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js',
];

// ─────────────────────────────────────────────────────────────────────────────
// ÉVÉNEMENT : INSTALL — Cache les ressources statiques au premier lancement
// ─────────────────────────────────────────────────────────────────────────────
self.addEventListener('install', function(event) {
    console.log('[SW] Installation v1.9...');
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return Promise.allSettled(STATIC_ASSETS.map(function(asset) { return cache.add(asset); }))
                .then(function(results) {
                    if (results.some(function(result) { return result.status === 'rejected'; })) {
                        console.warn('[SW] Certaines ressources n’ont pas pu être mises en cache.');
                    }
                    return self.skipWaiting();
                });
        })
    );
});

// ─────────────────────────────────────────────────────────────────────────────
// ÉVÉNEMENT : ACTIVATE — Supprime les anciens caches
// ─────────────────────────────────────────────────────────────────────────────
self.addEventListener('activate', function(event) {
    console.log('[SW] Activation v1.9...');
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames
                    .filter(function(name) { return name !== CACHE_NAME; })
                    .map(function(name) {
                        console.log('[SW] Suppression du vieux cache :', name);
                        return caches.delete(name);
                    })
            ).catch(function(err) {
                console.warn('[SW] Erreur nettoyage cache:', err);
            });
        }).then(function() {
            return self.clients.claim();
        })
    );
});

// ─────────────────────────────────────────────────────────────────────────────
// ÉVÉNEMENT : FETCH
// ─────────────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', function(event) {
    const url = new URL(event.request.url);

    // Ne pas intercepter les requêtes POST (formulaires, API)
    if (event.request.method !== 'GET') return;

    // Stratégie Cache First pour les assets statiques (CDN, CSS, fonts, images)
    const isStaticAsset = (
        url.pathname.match(/\.(css|js|woff2?|ttf|eot|svg|png|jpe?g|webp|ico)($|\?)/) ||
        url.hostname.includes('jsdelivr.net') ||
        url.hostname.includes('cloudflare.com') ||
        url.hostname.includes('fonts.googleapis.com') ||
        url.hostname.includes('fonts.gstatic.com')
    );

    if (isStaticAsset) {
        event.respondWith(
            caches.match(event.request).then(function(cached) {
                return cached || fetch(event.request).then(function(response) {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(c => c.put(event.request, clone)).catch(function() {});
                    }
                    return response;
                });
            }).catch(function() {
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    // Stratégie Network First pour les pages PHP
    // 1. Essayer le réseau
    // 2. Si échec → servir depuis le cache
    // 3. Si pas en cache → servir offline.php
    event.respondWith(
        fetch(event.request)
            .then(function(response) {
                // Ne pas mettre en cache les réponses avec no-store/private
                const cacheControl = response.headers.get('Cache-Control') || '';
                const pragma = response.headers.get('Pragma') || '';
                const shouldCache = !cacheControl.includes('no-store') && 
                                   !cacheControl.includes('private') &&
                                   !pragma.includes('no-cache');
                
                if (shouldCache && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(function(cache) {
                        cache.put(event.request, clone);
                    }).catch(function() {});
                }
                return response;
            })
            .catch(function() {
                // 1. Vérifier si la requête est en cache
                return caches.match(event.request).then(function(cached) {
                    if (cached) return cached;
                    // 2. Fallback vers offline.php
                    return caches.match(OFFLINE_URL).then(function(offline) {
                        // 3. Si offline.php pas en cache → réponse native
                        return offline || new Response('Hors-ligne', {
                            status: 503,
                            headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                        });
                    });
                }).catch(function() {
                    return new Response('Hors-ligne', {
                        status: 503,
                        headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                    });
                });
            })
    );
});

// ─────────────────────────────────────────────────────────────────────────────
// ÉVÉNEMENT : MESSAGE — Reçoit les commandes du client
// ─────────────────────────────────────────────────────────────────────────────
self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
