/* Ejercicio 2: cargar y vaciar la tabla de notas de crédito con jQuery */
$(document).ready(function() {

  /* Clic en "Cargar Datos": parsea el JSON y genera una fila por cada nota */
  $("#btCargarDatos").click(function() {
    var objJson = JSON.parse(textoNotasCredito);

    objJson.notasCredito.forEach(function(argValor, argIndice) {
      /* Crear la fila */
      var objTr = document.createElement("tr");

      /* Crear cada celda con su atributo de campo */
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

      /* Agregar la fila al tbody */
      document.getElementById("tbDatos").appendChild(objTr);
    });
  });

  /* Clic en "Vaciar Datos": vacía el tbody con jQuery */
  $("#btVaciarDatos").click(function() {
    $("#tbDatos").empty();
  });

});
