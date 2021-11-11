const CACHE_VERSION = "1.4.1";
const CACHE_NAME = `kodatos-${CACHE_VERSION}`;

const OFFLINE_URL = "/pwa/offline";
const CACHE_ASSETS = [
    `/`,
    `/enter-code`,
    `${OFFLINE_URL}`,
    `/assets/images/404.svg`,
    `/assets/images/code.svg`,
    `/assets/images/taken.svg`,
    `/assets/images/void.svg`,
    `/assets/images/sections/0.jpg`,
    `/assets/images/sections/1.jpg`,
    `/assets/branding/logo.svg`,
    `/assets/branding/logo-full.svg`,
    `/assets/common.css`,
    `/assets/common.proto.css`,
    `/assets/common.js`,
    `/assets/qrcode.min.js`,
    `/assets/qrcode-scan.min.js`
];

self.addEventListener("install", e => {
    e.waitUntil(
        caches.open(CACHE_NAME)
              .then(cache => {
            return cache.addAll(CACHE_ASSETS)
                        .then(() => self.skipWaiting());
        })
    );
});

self.addEventListener("activate", event => {
    event.waitUntil((async () => {
        if ("navigationPreload" in self.registration) {
            await self.registration.navigationPreload.enable();
        }
    })());

    self.clients.claim();
});

self.addEventListener("fetch", event => {
    event.respondWith((async () => {
        const cache = await caches.open(CACHE_NAME);
        const cachedResponse = await cache.match(
            event.request,
            { ignoreSearch: true }
        );
        if (cachedResponse) {
            return cachedResponse;
        }

        try {
            const preloadResponse = await event.preloadResponse;
            if (preloadResponse) {
                return preloadResponse;
            }

            const networkResponse = await fetch(event.request);
            return networkResponse;
        } catch (e) {
            if (event.request.mode === "navigate") {
                const offlineResponse = await cache.match(OFFLINE_URL);
                return offlineResponse;
            }
            return e;
        }
    })());
});
