/* Ejercicio 4: sistema integrado tabla + formulario en modal */
$(document).ready(function() {

  /* Cargar Datos: llena la tabla con filas generadas desde el JSON */
  $("#btCargarDatos").click(function() {
    var objJson = JSON.parse(textoNotasCredito);

    objJson.notasCredito.forEach(function(argValor, argIndice) {
      var objTr = document.createElement("tr");

      var tdNro = document.createElement("td");
      tdNro.setAttribute("campo-dato", "NroComprobante");
      tdNro.innerHTML = argValor.NroComprobante;
      objTr.appendChild(tdNro);

      var tdFactura = document.createElement("td");
      tdFactura.setAttribute("campo-dato", "NroDeFacturaImputada");
      tdFactura.innerHTML = argValor.NroDeFacturaImputada;
      objTr.appendChild(tdFactura);

      var tdTipo = document.createElement("td");
      tdTipo.setAttribute("campo-dato", "codigoTipo");
      tdTipo.innerHTML = argValor.codigoTipo;
      objTr.appendChild(tdTipo);

      var tdCliente = document.createElement("td");
      tdCliente.setAttribute("campo-dato", "CodCliente");
      tdCliente.innerHTML = argValor.CodCliente;
      objTr.appendChild(tdCliente);

      var tdObs = document.createElement("td");
      tdObs.setAttribute("campo-dato", "Observaciones");
      tdObs.innerHTML = argValor.Observaciones;
      objTr.appendChild(tdObs);

      var tdFecha = document.createElement("td");
      tdFecha.setAttribute("campo-dato", "fechaComprobante");
      tdFecha.innerHTML = argValor.fechaComprobante;
      objTr.appendChild(tdFecha);

      var tdTotal = document.createElement("td");
      tdTotal.setAttribute("campo-dato", "TotalNetoComprobante");
      tdTotal.innerHTML = argValor.TotalNetoComprobante;
      objTr.appendChild(tdTotal);

      document.getElementById("tbDatos").appendChild(objTr);
    });
  });

  /* Vaciar Datos: limpia el tbody con jQuery */
  $("#btVaciarDatos").click(function() {
    $("#tbDatos").empty();
  });

  /* Cargar Formulario: abre el modal y llena el select de tipos */
  $("#btCargarFormulario").click(function() {
    /* Limpiar el select antes de cargarlo para evitar duplicados */
    $("#selectTipoComprobante").empty();

    var objJson = JSON.parse(textoTiposComprobante);
    objJson.tiposComprobante.forEach(function(argValor, argIndice) {
      var objOpcion = document.createElement("option");
      objOpcion.value = argValor.codigoTipo;
      objOpcion.innerHTML = argValor.DescripcionTipo;
      document.getElementById("selectTipoComprobante").appendChild(objOpcion);
    });

    /* Opaca el fondo y muestra el modal */
    $("#contenedorFondo").removeClass("contenedorActivo").addClass("contenedorPasivo");
    $("#ventanaModal").removeClass("ventanaModalApagado").addClass("ventanaModalPrendido");
  });

  /* Cerrar modal: restaura el fondo y oculta el modal */
  $("#btnCerrar").click(function() {
    $("#ventanaModal").removeClass("ventanaModalPrendido").addClass("ventanaModalApagado");
    $("#contenedorFondo").removeClass("contenedorPasivo").addClass("contenedorActivo");
  });

});
