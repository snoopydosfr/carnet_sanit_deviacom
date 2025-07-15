// Nom du cache
const CACHE_NAME = 'carnet-sanitaire-cache-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/login.php',
    '/pages/maintenance_hebdo.php',
    '/pages/surveillance_temperatures.php',
    '/pages/analyse_legionelle.php',
    '/pages/fiche_intervenants.php',
    '/rapports/generate_pdf.php',
    '/pages/graphiques.php',
    '/includes/db.php',
    '/assets/css/style.css',
    '/assets/js/app.js'
];

// Installation du service worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker : Cache ouvert');
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting(); // Activer immédiatement
});

// Activation du service worker
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Interception des requêtes réseau
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Si trouvé dans le cache, retourne la réponse
                if (response) {
                    return response;
                }

                // Sinon, fait la requête normale
                return fetch(event.request).then(fetchResponse => {
                    // Ne mettre en cache que les ressources statiques
                    if (!event.request.url.startsWith('http')) {
                        return fetchResponse;
                    }

                    // Mettre en cache les nouvelles réponses réseau
                    return caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, fetchResponse.clone());
                        return fetchResponse;
                    });
                });
            })
            .catch(() => {
                // Réponse alternative si hors ligne et pas dans le cache
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
            })
    );
});