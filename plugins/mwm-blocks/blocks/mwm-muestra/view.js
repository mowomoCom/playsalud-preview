(function () {
  var blocks = document.querySelectorAll(".wp-block-mwm-muestra");
  blocks.forEach(function (block) {
    var track = block.querySelector("[data-mwm-muestra-track]");
    var prev = block.querySelector("[data-mwm-prev]");
    var next = block.querySelector("[data-mwm-next]");
    if (!track || !prev || !next) {
      return;
    }

    var step = 300;
    prev.addEventListener("click", function () {
      track.scrollBy({ left: -step, behavior: "smooth" });
    });
    next.addEventListener("click", function () {
      track.scrollBy({ left: step, behavior: "smooth" });
    });
  });
})();
