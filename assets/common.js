var gSite = {
    initialize: function () {
        gActions.initTargets();
    },

    deferredLoad: function () {
    },
    
    lastKnownScrollPosition: 0,
    ticking: false,
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
    },

    toggleAttribute: function (elementId, attributeName) {
        let container = document.getElementById(elementId);
        let state = (container.getAttribute(attributeName) == "true") ? "false" : "true";
        container.setAttribute(attributeName, state);
        container.removeAttribute("prehidden");
    },
};

var gActions = {
    initTarget: function (aTarget) {
        if (!aTarget) {
            return false;
        }
        if (typeof aTarget === "string") {
            aTarget = document.getElementById(aTarget);
        }
        if (aTarget.getAttribute("_")) {
            return false;
        }
        
        let targetIDs = aTarget.getAttribute("targetId").split(",");
        let attributeName = aTarget.getAttribute("targetAttr");
        for (let j = 0; j < targetIDs.length; j++) {
            if (targetIDs[j] && attributeName) {
                aTarget.addEventListener(
                    "click",
                    function (e) {
                        gSite.toggleAttribute(targetIDs[j], attributeName);
                    }
                );
                aTarget.setAttribute("_", "true");
            }
        }
        
        return true;
    },
    
    initTargets: function () {
        let actionElements = document.getElementsByClassName("has-action");
        for (let i = 0; i < actionElements.length; i++) {
            gActions.initTarget(actionElements[i]);
        }
    },
};

window.addEventListener("DOMContentLoaded", gSite.initialize);
window.addEventListener("load", gSite.deferredLoad);
document.addEventListener("scroll", gSite.updateHeader);

// Initialize PWA service worker
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/pwa.js", {
        scope: "."
    }).then(function (registration) {
        console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
    }, function (err) {
        console.log('PWA: ServiceWorker registration failed: ', err);
    });
}
