/* app.js  |  ABM Notas de Credito  |  Juan Moyano  |  Tabla 9 */

$(document).ready(function () {

    cargaTabla();

    /* ── Ordenamiento por clic en encabezados ── */
    $("#th_nroComprobante").click(function () { $("#orden").val("NroComprobante");      cargaTabla(); });
    $("#th_nroFactura")    .click(function () { $("#orden").val("NroDeFacturaImputada"); cargaTabla(); });
    $("#th_tipo")          .click(function () { $("#orden").val("codigoTipo");           cargaTabla(); });
    $("#th_cliente")       .click(function () { $("#orden").val("CodCliente");           cargaTabla(); });
    $("#th_fecha")         .click(function () { $("#orden").val("fechaComprobante");     cargaTabla(); });
    $("#th_total")         .click(function () { $("#orden").val("TotalNetoComprobante"); cargaTabla(); });

    /* ── Botones de cabecera ── */
    $("#btnCargarDatos")  .click(function () { cargaTabla(); });
    $("#btnVaciarDatos")  .click(function () {
        document.getElementById("tbDatos").innerHTML = "";
        $("#totalRegistros").text("0");
    });
    $("#btnLimpiarFiltros").click(function () {
        $(".inputFiltro").val("");
        $("#orden").val("NroComprobante");
        cargaTabla();
    });
    $("#btnAbrirAlta").click(function () { abrirModalFormulario("alta"); });

    /* ── Filtros: disparar con Enter ── */
    $(".inputFiltro").on("keydown", function (e) {
        if (e.key === "Enter") { e.preventDefault(); cargaTabla(); }
    });

    /* ── Cerrar modales ── */
    $(".btnCerrarModal").click(function () { cerrarModales(); });

    /* ── Submit del formulario ABM ── */
    $("#formAbm").submit(function (e) {
        e.preventDefault();
        const accion   = $("#accionForm").val();
        const endpoint = (accion === "alta") ? "./alta.php" : "./modi.php";
        const formData = new FormData(this);

        fetch(endpoint, { method: "POST", body: formData })
            .then(res => res.text())
            .then(resp => {
                alert("Respuesta del Servidor:\n" + resp);
                cerrarModales();
                abrirModalRespuesta("Estado de la Operacion", "<pre>" + resp + "</pre>");
                cargaTabla();
            })
            .catch(err => alert("Error al enviar formulario: " + err));
    });
});


/* ══════════════════════════════════════════════════════════════
   cargaTabla  —  fetch JSON + alert obligatorio del profesor
   ══════════════════════════════════════════════════════════════ */
function cargaTabla() {
    var tbody = document.getElementById("tbDatos");
    tbody.innerHTML = "<tr><td colspan='8' style='text-align:center;padding:14px;'>Esperando respuesta del servidor...</td></tr>";

    const params = new URLSearchParams();
    params.append("orden",           $("#orden").val());
    params.append("f_nroComprobante",$("#f_nroComprobante").val());
    params.append("f_nroFactura",    $("#f_nroFactura").val());
    params.append("f_codigoTipo",    $("#f_codigoTipo").val());
    params.append("f_codCliente",    $("#f_codCliente").val());
    params.append("f_fecha",         $("#f_fecha").val());

    fetch("./salidaJsonNotasCredito.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params
    })
    .then(res => res.text())
    .then(textoJson => {
        // Exigencia del profesor: alert con el JSON crudo para verificar la respuesta HTTP
        alert("JSON recibido del servidor:\n" + textoJson);

        const data = JSON.parse(textoJson);
        tbody.innerHTML = "";

        if (!data.notasCredito || data.notasCredito.length === 0) {
            tbody.innerHTML = "<tr><td colspan='8' style='text-align:center;padding:14px;'>No se encontraron registros.</td></tr>";
            $("#totalRegistros").text("0");
            return;
        }

        $("#totalRegistros").text(data.cuenta);

        data.notasCredito.forEach(function (item) {
            var tr = document.createElement("tr");

            var tdComp = document.createElement("td");
            tdComp.setAttribute("campo-dato", "nroComprobante");
            tdComp.textContent = item.NroComprobante;
            tr.appendChild(tdComp);

            var tdFact = document.createElement("td");
            tdFact.setAttribute("campo-dato", "nroFactura");
            tdFact.textContent = item.NroDeFacturaImputada;
            tr.appendChild(tdFact);

            var tdTipo = document.createElement("td");
            tdTipo.setAttribute("campo-dato", "tipo");
            tdTipo.textContent = item.DescripcionTipo;
            tr.appendChild(tdTipo);

            var tdCli = document.createElement("td");
            tdCli.setAttribute("campo-dato", "cliente");
            tdCli.textContent = item.CodCliente;
            tr.appendChild(tdCli);

            var tdFecha = document.createElement("td");
            tdFecha.setAttribute("campo-dato", "fecha");
            tdFecha.textContent = item.fechaComprobante;
            tr.appendChild(tdFecha);

            var tdTotal = document.createElement("td");
            tdTotal.setAttribute("campo-dato", "total");
            tdTotal.style.textAlign = "right";
            tdTotal.textContent = "$ " + parseFloat(item.TotalNetoComprobante).toFixed(2);
            tr.appendChild(tdTotal);

            var tdPdf = document.createElement("td");
            tdPdf.setAttribute("campo-dato", "pdf");
            if (item.tienePdf === 1) {
                var btnPdf = document.createElement("button");
                btnPdf.textContent = "Ver PDF";
                btnPdf.className = "btn-pdf";
                btnPdf.onclick = (function (nro) {
                    return function () { verPdf(nro); };
                })(item.NroComprobante);
                tdPdf.appendChild(btnPdf);
            } else {
                tdPdf.textContent = "Sin PDF";
                tdPdf.style.color = "#aaa";
                tdPdf.style.fontSize = "0.75em";
            }
            tr.appendChild(tdPdf);

            var tdAcc = document.createElement("td");
            tdAcc.setAttribute("campo-dato", "acciones");

            var btnModi = document.createElement("button");
            btnModi.textContent = "Modi";
            btnModi.className = "btn-modi";
            btnModi.onclick = (function (obj) {
                return function () { abrirModalFormulario("modi", obj); };
            })(item);
            tdAcc.appendChild(btnModi);

            var btnBaja = document.createElement("button");
            btnBaja.textContent = "Borrar";
            btnBaja.className = "btn-baja";
            btnBaja.onclick = (function (nro) {
                return function () { ejecutarBaja(nro); };
            })(item.NroComprobante);
            tdAcc.appendChild(btnBaja);

            tr.appendChild(tdAcc);
            tbody.appendChild(tr);
        });
    })
    .catch(err => {
        alert("Error al cargar datos: " + err);
        tbody.innerHTML = "<tr><td colspan='8' style='text-align:center;color:red;'>Error de conexion con el servidor.</td></tr>";
    });
}


/* ══════════════════════════════════════════════════════════════
   verPdf  —  trae binario y lo incrusta como iframe base64
   ══════════════════════════════════════════════════════════════ */
function verPdf(nroComp) {
    fetch("./traeBinario.php?nroComprobante=" + encodeURIComponent(nroComp))
        .then(res => res.json())
        .then(data => {
            if (data.error) { alert(data.error); return; }
            $("#modalRespuesta").addClass("modal-pdf");
            abrirModalRespuesta(
                "Comprobante Digital: " + nroComp,
                "<iframe src='data:application/pdf;base64," + data.documentoPdf + "'></iframe>"
            );
        })
        .catch(err => alert("Error al cargar PDF: " + err));
}


/* ══════════════════════════════════════════════════════════════
   ejecutarBaja  —  confirm + DELETE + alert de respuesta
   ══════════════════════════════════════════════════════════════ */
function ejecutarBaja(nroComp) {
    if (!confirm("Desea eliminar la nota de credito " + nroComp + "?")) return;

    const params = new URLSearchParams();
    params.append("NroComprobante", nroComp);

    fetch("./baja.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params
    })
    .then(res => res.text())
    .then(resp => {
        alert("Respuesta del servidor:\n" + resp);
        abrirModalRespuesta("Resultado de la Baja", "<pre>" + resp + "</pre>");
        cargaTabla();
    })
    .catch(err => alert("Error en baja: " + err));
}


/* ══════════════════════════════════════════════════════════════
   abrirModalFormulario  —  alta o modi
   ══════════════════════════════════════════════════════════════ */
function abrirModalFormulario(tipo, item) {
    $("#modalFormulario").css("visibility", "visible");
    $("#contenedorPrincipal").addClass("contenedorPasivo").removeClass("contenedorActivo");

    if (tipo === "alta") {
        $("#tituloModalForm").text("Alta de Nota de Credito");
        $("#formAbm")[0].reset();
        $("#accionForm").val("alta");
        $("#entNroComp").prop("readonly", false);
        $("#btnSubmitForm").text("Dar de Alta").prop("disabled", false);
        $("#seccionPdfModi").hide();
        cargarTiposComprobante();
    } else {
        $("#tituloModalForm").text("Modificacion de Nota de Credito");
        $("#accionForm").val("modi");
        $("#entNroComp").val(item.NroComprobante).prop("readonly", true);
        $("#entNroFact").val(item.NroDeFacturaImputada);
        $("#entCodCliente").val(item.CodCliente);
        $("#entFecha").val(item.fechaComprobante);
        $("#entTotal").val(item.TotalNetoComprobante);
        $("#entObservaciones").val(item.Observaciones);

        // Limpiar file input (no puede pre-cargarse por seguridad)
        document.getElementById("entPdf").value = "";

        // Mostrar seccion PDF existente si corresponde
        if (item.tienePdf === 1) {
            $("#seccionPdfModi").show();
            $("#btnEliminarPdf").off("click").on("click", function () {
                eliminarPdfRegistro(item.NroComprobante);
            });
        } else {
            $("#seccionPdfModi").hide();
        }

        // Boton nace deshabilitado hasta que el usuario modifique algo
        $("#btnSubmitForm").text("Enviar Modificacion").prop("disabled", true);
        $("#formAbm input, #formAbm select, #formAbm textarea")
            .off("change input")
            .on("change input", function () {
                $("#btnSubmitForm").prop("disabled", false);
            });

        // Cargar tipos y luego seleccionar el actual
        cargarTiposComprobante().then(function () {
            $("#entCodigoTipo").val(item.codigoTipo);
        });
    }
}


/* ══════════════════════════════════════════════════════════════
   cargarTiposComprobante  —  llena el <select> del formulario
   ══════════════════════════════════════════════════════════════ */
function cargarTiposComprobante() {
    return fetch("./salidaTiposComprobante.php")
        .then(res => res.json())
        .then(data => {
            var select = document.getElementById("entCodigoTipo");
            select.innerHTML = "";
            if (data.error) {
                var opt = document.createElement("option");
                opt.textContent = "Error al cargar tipos";
                select.appendChild(opt);
                return;
            }
            data.tiposComprobante.forEach(function (tipo) {
                var opt = document.createElement("option");
                opt.value = tipo.codigoTipo;
                opt.textContent = tipo.codigoTipo + " - " + tipo.DescripcionTipo;
                select.appendChild(opt);
            });
        })
        .catch(err => alert("Error al cargar tipos: " + err));
}


/* ══════════════════════════════════════════════════════════════
   Modales de respuesta y cierre
   ══════════════════════════════════════════════════════════════ */
function abrirModalRespuesta(titulo, html) {
    $("#modalRespuesta").css("visibility", "visible");
    $("#contenedorPrincipal").addClass("contenedorPasivo").removeClass("contenedorActivo");
    $("#encabezadoModalRespuesta").text(titulo);
    $("#cuerpoModalRespuesta").html(html);
}

function cerrarModales() {
    $(".ventanaModal").css("visibility", "hidden");
    $("#modalRespuesta").removeClass("modal-pdf");
    $("#contenedorPrincipal").addClass("contenedorActivo").removeClass("contenedorPasivo");
}


/* ══════════════════════════════════════════════════════════════
   eliminarPdfRegistro  —  pone archivo = NULL en la DB
   ══════════════════════════════════════════════════════════════ */
function eliminarPdfRegistro(nroComp) {
    if (!confirm("Eliminar el PDF del comprobante " + nroComp + "?")) return;

    const params = new URLSearchParams();
    params.append("NroComprobante", nroComp);

    fetch("./eliminarPdf.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params
    })
    .then(res => res.text())
    .then(resp => {
        alert("Respuesta:\n" + resp);
        $("#seccionPdfModi").hide();
    })
    .catch(err => alert("Error al eliminar PDF: " + err));
}
