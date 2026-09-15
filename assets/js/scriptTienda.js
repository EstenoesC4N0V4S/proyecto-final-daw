window.onload = function () {

  const toast = document.getElementById("toast-carrito");

  if (toast) {

    // aparece
    setTimeout(() => {
      toast.classList.add("show");
    }, 100);

    // desaparece
    setTimeout(() => {
      toast.classList.remove("show");
    }, 2500);

  }

};