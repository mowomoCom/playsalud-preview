(function () {
  var REVEAL_SELECTOR = ".reveal";
  var VISIBLE_CLASS = "visible";
  var OBSERVER_OPTIONS = {
    threshold: 0.12,
    rootMargin: "0px 0px -60px 0px",
  };

  function revealAll(elements) {
    elements.forEach(function (element) {
      element.classList.add(VISIBLE_CLASS);
    });
  }

  function initRevealOnScroll() {
    var reveals = document.querySelectorAll(REVEAL_SELECTOR);
    if (!reveals.length) {
      return;
    }

    var prefersReducedMotion =
      typeof window.matchMedia === "function" &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (prefersReducedMotion || typeof window.IntersectionObserver !== "function") {
      revealAll(reveals);
      return;
    }

    var observer = new window.IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add(VISIBLE_CLASS);
        observer.unobserve(entry.target);
      });
    }, OBSERVER_OPTIONS);

    reveals.forEach(function (element) {
      observer.observe(element);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initRevealOnScroll);
  } else {
    initRevealOnScroll();
  }
})();
