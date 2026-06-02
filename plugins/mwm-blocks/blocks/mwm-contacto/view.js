(function () {
  var forms = document.querySelectorAll("[data-mwm-contact-form]");
  forms.forEach(function (form) {
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      window.alert("Formulario de demostracion. Conectar al backend o plugin de formularios.");
    });
  });
})();
