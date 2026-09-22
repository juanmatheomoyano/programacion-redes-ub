/* Ejercicio 3: controlar la ventana modal con jQuery */
$(document).ready(function() {

  /* Al cargar la página: mostrar el ancho del navegador */
  alert("Ancho del navegador: " + $(window).width() + "px");

  /* Botón "Abrir Modal": opaca el fondo y muestra el modal */
  $("#btnAbrir").click(function() {
    $("#contenedorFondo").removeClass("contenedorActivo").addClass("contenedorPasivo");
    $("#ventanaModal").removeClass("ventanaModalApagado").addClass("ventanaModalPrendido");
  });

  /* Botón "Cerrar": restaura el fondo y oculta el modal */
  $("#btnCerrar").click(function() {
    $("#ventanaModal").removeClass("ventanaModalPrendido").addClass("ventanaModalApagado");
    $("#contenedorFondo").removeClass("contenedorPasivo").addClass("contenedorActivo");
  });

});
