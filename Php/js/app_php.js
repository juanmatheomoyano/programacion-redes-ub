// Carga los tipos de comprobante desde el servidor y los carga en todos los <select> con id="selTipoComprobante"
function cargarTiposComprobante(callbackExtra) {
    $.getJSON('../Php/backend/obtener_tipos_comprobante.php', function(respuestaServer) {
        var $sel = $("#selTipoComprobante");
        $sel.empty().append('<option value="">-- Seleccione --</option>');
        respuestaServer.tiposComprobante.forEach(function(tipo) {
            $sel.append('<option value="' + tipo.codigo + '">' + tipo.codigo + ' – ' + tipo.descripcion + '</option>');
        });
        if (typeof callbackExtra === 'function') callbackExtra(respuestaServer);
    });
}

// Carga las notas de crédito y las renderiza en #tbDatos. Acepta codCliente opcional para filtrar.
function cargarNotasCredito(codCliente) {
    var url = '../Php/backend/obtener_notas_credito.php';
    if (codCliente && codCliente.trim() !== '') {
        url += '?codCliente=' + encodeURIComponent(codCliente.trim());
    }

    $.getJSON(url, function(respuestaServer) {
        var $tbody = $("#tbDatos");
        $tbody.empty();

        respuestaServer.notasCredito.forEach(function(nota) {
            var $tr = $('<tr>');
            $tr.append($('<td>').attr('campo-dato', 'NroComprobante').text(nota.NroComprobante));
            $tr.append($('<td>').attr('campo-dato', 'NroDeFacturaImputada').text(nota.NroDeFacturaImputada));
            $tr.append($('<td>').attr('campo-dato', 'TipoComprobante').text(nota.TipoComprobante));
            $tr.append($('<td>').attr('campo-dato', 'CodCliente').text(nota.CodCliente));
            $tr.append($('<td>').attr('campo-dato', 'Observaciones').text(nota.Observaciones));
            $tr.append($('<td>').attr('campo-dato', 'fechaComprobante').text(nota.fechaComprobante));
            $tr.append($('<td>').attr('campo-dato', 'TotalNetoComprobante').text('$' + nota.TotalNetoComprobante.toLocaleString('es-AR')));
            $tbody.append($tr);
        });

        $("#spanTotal").text(respuestaServer.totalRegistros);
        $("#spanIP").text(respuestaServer.ipServidor);
        $("#spanMetodo").text(respuestaServer.metodoRequerimiento);
    });
}
