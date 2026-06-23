// Client-side JS — minimal, alpine covers most interactivity
document.addEventListener('htmx:beforeSwap', function (e) {
    if (e.detail.xhr.status === 404) {
        e.detail.shouldSwap = true;
        e.detail.isError = false;
    }
});
