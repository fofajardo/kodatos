var gSite = {
    lastKnownScrollPosition: 0,
    ticking: false,

    initialize: function () {
    },

    deferredLoad: function () {
    },
    
    reflow: function () {
    },

    updateHeader: function (e) {
        gSite.lastKnownScrollPosition = window.scrollY;

        if (!gSite.ticking) {
            window.requestAnimationFrame(function() {
                let header = document.getElementById("primary-header");
                if (gSite.lastKnownScrollPosition > 100) {
                    header.setAttribute("opaque", "true");
                } else {
                    header.removeAttribute("opaque");
                }
                gSite.ticking = false;
            });
            gSite.ticking = true;
        }
    }
};

window.addEventListener("DOMContentLoaded", gSite.initialize);
window.addEventListener("load", gSite.deferredLoad);
window.addEventListener("resize", gSite.reflow);
document.addEventListener("scroll", gSite.updateHeader);

// Initialize PWA service worker
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/assets/pwa.js", {
        scope: "."
    }).then(function (registration) {
        console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
    }, function (err) {
        console.log('PWA: ServiceWorker registration failed: ', err);
    });
}
