/* Ejercicio 1: cargar el select de tipos de comprobante desde JSON */
$(document).ready(function() {

  /* Parsear el texto JSON a un objeto de JS */
  var objJson = JSON.parse(textoTiposComprobante);

  /* Recorrer el arreglo y crear una <option> por cada tipo */
  objJson.tiposComprobante.forEach(function(argValor, argIndice) {
    var objOpcion = document.createElement("option");
    objOpcion.value = argValor.codigoTipo;
    objOpcion.innerHTML = argValor.DescripcionTipo;
    document.getElementById("selectTipoComprobante").appendChild(objOpcion);
  });

});
