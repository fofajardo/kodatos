const version = "1.2.0";
const cacheName = `kodatos-${version}`;
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(cacheName).then(cache => {
            return cache.addAll([
                `/`, 
                `/enter-code`, 
                `/assets/images/404.svg`, 
                `/assets/images/code.svg`, 
                `/assets/images/taken.svg`, 
                `/assets/images/branding/logo.svg`, 
                `/assets/images/branding/logo-full.svg`, 
                `/assets/images/sections/0.jpg`, 
                `/assets/images/sections/1.jpg`, 
                `/assets/common.css`, 
                `/assets/common.proto.css`, 
                `/assets/common.js`, 
                `/assets/qrcode.min.js`, 
                `/assets/qrcode-scan.min.js`
            ]).then(() => self.skipWaiting());
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.open(cacheName).then(cache =>
            cache.match(event.request, {
                ignoreSearch: true
            })
        ).then(response => {
            return response || fetch(event.request);
        })
    );
});
