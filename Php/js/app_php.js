// Carga tipos de comprobante y llena #selTipoComprobante.
// callbackExtra recibe la respuestaServer completa para uso adicional (ej: llenar tabla).
function cargarTiposComprobante(callbackExtra) {
    $.getJSON('./backend/obtener_tipos_comprobante.php', function(respuestaServer) {
        var objSelect = document.getElementById("selTipoComprobante");
        objSelect.innerHTML = '';

        var objOptionDefault = document.createElement("option");
        objOptionDefault.value = "";
        objOptionDefault.innerHTML = "-- Seleccione tipo --";
        objSelect.appendChild(objOptionDefault);

        respuestaServer.tiposComprobante.forEach(function(tipo) {
            var objOption = document.createElement("option");
            objOption.value = tipo.codigo;
            objOption.innerHTML = tipo.codigo + ' – ' + tipo.descripcion;
            objSelect.appendChild(objOption);
        });

        if (typeof callbackExtra === 'function') callbackExtra(respuestaServer);
    });
}

// Carga notas de crédito y llena #tbDatos. Acepta codCliente opcional para filtrar via $_GET.
function cargarNotasCredito(codCliente) {
    var url = './backend/obtener_notas_credito.php';
    if (codCliente && codCliente.trim() !== '') {
        url += '?codCliente=' + encodeURIComponent(codCliente.trim());
    }

    $.getJSON(url, function(respuestaServer) {
        $("#tbDatos").empty();

        respuestaServer.notasCredito.forEach(function(nota) {
            var objTr = document.createElement("tr");

            var objTdNro = document.createElement("td");
            objTdNro.setAttribute("campo-dato", "NroComprobante");
            objTdNro.innerHTML = nota.NroComprobante;
            objTr.appendChild(objTdNro);

            var objTdFactura = document.createElement("td");
            objTdFactura.setAttribute("campo-dato", "NroDeFacturaImputada");
            objTdFactura.innerHTML = nota.NroDeFacturaImputada;
            objTr.appendChild(objTdFactura);

            var objTdTipo = document.createElement("td");
            objTdTipo.setAttribute("campo-dato", "TipoComprobante");
            objTdTipo.innerHTML = nota.TipoComprobante;
            objTr.appendChild(objTdTipo);

            var objTdCliente = document.createElement("td");
            objTdCliente.setAttribute("campo-dato", "CodCliente");
            objTdCliente.innerHTML = nota.CodCliente;
            objTr.appendChild(objTdCliente);

            var objTdObs = document.createElement("td");
            objTdObs.setAttribute("campo-dato", "Observaciones");
            objTdObs.innerHTML = nota.Observaciones;
            objTr.appendChild(objTdObs);

            var objTdFecha = document.createElement("td");
            objTdFecha.setAttribute("campo-dato", "fechaComprobante");
            objTdFecha.innerHTML = nota.fechaComprobante;
            objTr.appendChild(objTdFecha);

            var objTdTotal = document.createElement("td");
            objTdTotal.setAttribute("campo-dato", "TotalNetoComprobante");
            objTdTotal.innerHTML = '$' + nota.TotalNetoComprobante.toLocaleString('es-AR');
            objTr.appendChild(objTdTotal);

            $("#tbDatos").append(objTr);
        });

        $("#spanTotal").text(respuestaServer.totalRegistros);
        $("#spanIP").text(respuestaServer.ipServidor);
        $("#spanMetodo").text(respuestaServer.metodoRequerimiento);
    });
}
