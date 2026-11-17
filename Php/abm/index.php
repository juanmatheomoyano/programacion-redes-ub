<?php include "./manejoDeSesion.inc"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notas de Credito — Tabla 9</title>
<style>

/* ── Reset y base ── */
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
  height: 100%;
  font-family: Arial, sans-serif;
  background: #f0f0f0;
  overflow: hidden;
}

/* ── Clases para el fondo cuando hay modal abierto ── */
.contenedorActivo { opacity: 1;   pointer-events: auto; transition: opacity 0.2s; }
.contenedorPasivo { opacity: 0.3; pointer-events: none; transition: opacity 0.2s; }

/* ── Contenedor principal ── */
#contenedorPrincipal {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

/* ── Cabecera ── */
header {
  background: #1b4332;
  color: white;
  padding: 9px 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
  border-bottom: 3px solid #52b788;
}
header h1 { font-size: 0.88em; flex: 1; }
.info-sesion { font-size: 0.75em; color: #95d5b2; white-space: nowrap; }
.info-sesion a { color: #52b788; text-decoration: none; margin-left: 10px; }
.info-sesion a:hover { text-decoration: underline; }
header button {
  background: #52b788;
  color: #1b4332;
  border: none;
  padding: 6px 12px;
  font-size: 0.78em;
  font-weight: bold;
  cursor: pointer;
}
header button:hover { background: #95d5b2; }
#btnAbrirAlta { background: #d8f3dc; color: #1b4332; border-left: 2px solid #52b788; }

/* ── Zona de filtros ── */
.zona-filtros {
  background: #2d6a4f;
  padding: 6px 18px;
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}
.zona-filtros label {
  font-size: 0.72em;
  color: #95d5b2;
  white-space: nowrap;
}
.zona-filtros input.inputFiltro {
  flex: 1;
  padding: 4px 8px;
  font-size: 0.78em;
  border: none;
  border-bottom: 2px solid #52b788;
  background: rgba(255,255,255,0.12);
  color: white;
  outline: none;
}
.zona-filtros input.inputFiltro::placeholder { color: #95d5b2; }
.zona-filtros input.inputFiltro:focus { border-bottom-color: #d8f3dc; }

/* ── Zona de tabla ── */
.zona-tabla {
  flex: 1;
  overflow: hidden;
  padding: 10px 14px;
  display: flex;
  flex-direction: column;
}

/* Truco flex para thead fijo + tbody scrolleable */
table {
  width: 100%;
  border-collapse: collapse;
  display: flex;
  flex-direction: column;
  height: 100%;
}
thead, tfoot {
  flex-shrink: 0;
  display: table;
  width: 100%;
  table-layout: fixed;
}
thead { background: #2d6a4f; color: white; }
tfoot { background: #f4faf6; color: #666; font-size: 0.75em; }
tbody {
  flex: 1;
  overflow-y: auto;
  display: block;
  width: 100%;
}
tbody tr { display: table; width: 100%; table-layout: fixed; }

th, td {
  padding: 7px 8px;
  font-size: 0.77em;
  border-bottom: 1px solid #d8f3dc;
  text-align: left;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
thead th {
  border-bottom: 2px solid #52b788;
}
thead th[id] { cursor: pointer; user-select: none; }
thead th[id]:hover { background: #1b4332; }
thead th[id]::after { content: " \25B4\25BE"; font-size: 0.75em; color: #95d5b2; }
tbody tr:nth-child(even) { background: #f4faf6; }
tbody tr:hover { background: #d8f3dc; }

/* Anchos de columnas */
th:nth-child(1), td:nth-child(1) { width: 20%; }
th:nth-child(2), td:nth-child(2) { width: 18%; }
th:nth-child(3), td:nth-child(3) { width: 15%; }
th:nth-child(4), td:nth-child(4) { width: 11%; }
th:nth-child(5), td:nth-child(5) { width: 11%; }
th:nth-child(6), td:nth-child(6) { width: 10%; text-align: right; }
th:nth-child(7), td:nth-child(7) { width: 7%; text-align: center; }
th:nth-child(8), td:nth-child(8) { width: 8%; text-align: center; }

/* Botones dentro de celdas */
td button {
  padding: 3px 7px;
  font-size: 0.74em;
  border: none;
  cursor: pointer;
  margin: 1px;
}
td .btn-pdf  { background: #2d6a4f; color: white; }
td .btn-pdf:hover  { background: #1b4332; }
td .btn-modi { background: #457b9d; color: white; }
td .btn-modi:hover { background: #2c5f7a; }
td .btn-baja { background: #c0392b; color: white; }
td .btn-baja:hover { background: #922b21; }

/* ── Modales (posicion fija, fuera del flujo) ── */
.ventanaModal {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  border-top: 4px solid #52b788;
  box-shadow: 0 10px 40px rgba(0,0,0,0.5);
  z-index: 400;
  min-width: 440px;
  max-width: 580px;
  width: 94%;
  max-height: 88vh;
  overflow-y: auto;
}
.modal-header {
  background: #1b4332;
  color: white;
  padding: 11px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky; top: 0; z-index: 1;
}
.modal-header h2 { font-size: 0.95em; }
.modal-body { padding: 20px 24px 10px; }

/* Campos del formulario */
.campo { margin-bottom: 13px; display: flex; flex-direction: column; }
.campo label { font-size: 0.8em; font-weight: bold; color: #2d6a4f; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.03em; }
.campo input, .campo select, .campo textarea {
  padding: 7px 9px;
  border: 1px solid #ccc;
  font-size: 0.88em;
  outline: none;
  transition: border-color 0.2s;
}
.campo input:focus, .campo select:focus, .campo textarea:focus { border-color: #52b788; }
.campo textarea { resize: vertical; min-height: 55px; }
.campo small { font-size: 0.74em; color: #888; margin-top: 3px; }

.pie-modal {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 14px 24px 18px;
  border-top: 1px solid #eee;
}
.pie-modal button { padding: 8px 22px; border: none; font-size: 0.88em; cursor: pointer; font-weight: bold; }
#btnSubmitForm { background: #2d6a4f; color: white; }
#btnSubmitForm:hover:not(:disabled) { background: #1b4332; }
#btnSubmitForm:disabled { background: #aaa; cursor: not-allowed; }
.btn-cancelar { background: #e0e0e0; color: #333; }
.btn-cancelar:hover { background: #ccc; }

/* Modal de respuesta */
#cuerpoModalRespuesta pre {
  background: #f4f4f4;
  padding: 12px;
  font-size: 0.82em;
  line-height: 1.6;
  white-space: pre-wrap;
  border-left: 3px solid #52b788;
}

</style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════════
     CONTENEDOR PRINCIPAL (se opaca cuando hay modal abierto)
     ══════════════════════════════════════════════════════════ -->
<div id="contenedorPrincipal" class="contenedorActivo">

  <!-- Cabecera -->
  <header>
    <h1>Encabezados de Notas de Credito de Ventas &mdash; Tabla 9</h1>
    <span class="info-sesion">
      <?php echo htmlspecialchars($_SESSION['login'] ?? ''); ?> |
      Sesion #<?php echo htmlspecialchars($_SESSION['contador'] ?? '1'); ?>
      <a href="./destruirSesion.php">[ Salir ]</a>
    </span>
    <button id="btnCargarDatos">Cargar Datos</button>
    <button id="btnVaciarDatos">Vaciar Datos</button>
    <button id="btnLimpiarFiltros">Limpiar Filtros</button>
    <button id="btnAbrirAlta">+ Alta</button>
  </header>

  <!-- Filtros -->
  <div class="zona-filtros">
    <label>Filtros:</label>
    <input class="inputFiltro" id="f_nroComprobante" placeholder="Nro. Comprobante">
    <input class="inputFiltro" id="f_nroFactura"     placeholder="Nro. Factura">
    <input class="inputFiltro" id="f_codigoTipo"     placeholder="Tipo (01/02/03)">
    <input class="inputFiltro" id="f_codCliente"     placeholder="Cod. Cliente">
    <input class="inputFiltro" id="f_fecha"          placeholder="Fecha (YYYY-MM-DD)">
    <input type="hidden"       id="orden"            value="NroComprobante">
  </div>

  <!-- Tabla -->
  <div class="zona-tabla">
    <table>
      <thead>
        <tr>
          <th id="th_nroComprobante">Nro. Comprobante</th>
          <th id="th_nroFactura">Factura Imputada</th>
          <th id="th_tipo">Tipo</th>
          <th id="th_cliente">Cod. Cliente</th>
          <th id="th_fecha">Fecha</th>
          <th id="th_total" style="text-align:right;">Total Neto</th>
          <th style="cursor:default;">PDF</th>
          <th style="cursor:default;">Acciones</th>
        </tr>
      </thead>
      <tbody id="tbDatos"></tbody>
      <tfoot>
        <tr>
          <td colspan="8">
            Total de registros: <strong id="totalRegistros">0</strong>
            &nbsp;&mdash;&nbsp; Notas de Credito de Ventas &mdash; Juan Moyano &mdash; Tabla 9
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

</div><!-- /contenedorPrincipal -->


<!-- ══════════════════════════════════════════════════════════
     MODAL 1: FORMULARIO DE ALTA / MODIFICACION
     ══════════════════════════════════════════════════════════ -->
<div id="modalFormulario" class="ventanaModal" style="visibility:hidden;">
  <div class="modal-header">
    <h2 id="tituloModalForm">Formulario</h2>
    <button class="btnCerrarModal" style="background:transparent;color:white;border:1px solid #52b788;padding:3px 10px;cursor:pointer;">X Cerrar</button>
  </div>

  <form id="formAbm" autocomplete="off">
    <input type="hidden" id="accionForm" name="accionForm">

    <div class="modal-body">

      <div class="campo">
        <label for="entNroComp">Nro. Comprobante</label>
        <input type="text" id="entNroComp" name="NroComprobante" placeholder="NC-0001-00000000" required>
      </div>

      <div class="campo">
        <label for="entNroFact">Nro. Factura Imputada</label>
        <input type="text" id="entNroFact" name="NroDeFacturaImputada" placeholder="FC-0001-00000000" required>
      </div>

      <div class="campo">
        <label for="entCodigoTipo">Tipo de Comprobante</label>
        <select id="entCodigoTipo" name="codigoTipo" required></select>
      </div>

      <div class="campo">
        <label for="entCodCliente">Codigo de Cliente</label>
        <input type="text" id="entCodCliente" name="CodCliente" placeholder="CLI-0000" required>
      </div>

      <div class="campo">
        <label for="entObservaciones">Observaciones</label>
        <textarea id="entObservaciones" name="Observaciones" placeholder="Descripcion del motivo..."></textarea>
      </div>

      <div class="campo">
        <label for="entFecha">Fecha del Comprobante</label>
        <input type="date" id="entFecha" name="fechaComprobante" required>
      </div>

      <div class="campo">
        <label for="entTotal">Total Neto ($)</label>
        <input type="number" id="entTotal" name="TotalNetoComprobante" step="0.01" min="0" placeholder="0.00" required>
      </div>

      <div class="campo">
        <label for="entPdf">PDF del Comprobante (opcional)</label>
        <input type="file" id="entPdf" name="documentoPdf" accept=".pdf,application/pdf">
        <small>Solo archivos .pdf. En Modificacion: si no se elige archivo nuevo, el PDF anterior se conserva.</small>
      </div>

    </div><!-- /modal-body -->

    <div class="pie-modal">
      <button type="button" class="btn-cancelar btnCerrarModal">Cancelar</button>
      <button type="submit" id="btnSubmitForm">Dar de Alta</button>
    </div>
  </form>
</div>


<!-- ══════════════════════════════════════════════════════════
     MODAL 2: RESPUESTA DEL SERVIDOR
     ══════════════════════════════════════════════════════════ -->
<div id="modalRespuesta" class="ventanaModal" style="visibility:hidden;">
  <div class="modal-header">
    <h2 id="encabezadoModalRespuesta">Respuesta del Servidor</h2>
    <button class="btnCerrarModal" style="background:transparent;color:white;border:1px solid #52b788;padding:3px 10px;cursor:pointer;">X Cerrar</button>
  </div>
  <div class="modal-body">
    <div id="cuerpoModalRespuesta"></div>
  </div>
  <div class="pie-modal">
    <button class="btnCerrarModal btn-cancelar">Cerrar</button>
  </div>
</div>


<!-- jQuery desde CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Logica de la SPA -->
<script src="./app.js"></script>

</body>
</html>
