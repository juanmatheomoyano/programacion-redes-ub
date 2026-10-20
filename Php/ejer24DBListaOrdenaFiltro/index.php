<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ejer24 - Lista, Orden y Filtro</title>
<style>
  /* Reset: sin scrollbar general en el viewport */
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; overflow: hidden; font-family: Arial, sans-serif; background: #f0f0f0; color: #222; }

  .pantalla { display: flex; flex-direction: column; height: 100vh; }

  /* Encabezado con botones */
  header { background: #1b4332; color: white; padding: 10px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 3px solid #52b788; flex-shrink: 0; }
  header h1 { font-size: 0.9em; flex: 1; }
  header button { background: #52b788; color: #1b4332; border: none; padding: 7px 14px; font-weight: bold; font-size: 0.82em; cursor: pointer; }
  header button:hover { background: #95d5b2; }

  /* Zona de tabla */
  .zona-tabla { flex: 1; overflow: hidden; padding: 12px; display: flex; flex-direction: column; }

  /* Tabla con thead/tfoot fijos y tbody con scroll */
  table { width: 100%; border-collapse: collapse; display: flex; flex-direction: column; height: 100%; }
  thead, tfoot { flex-shrink: 0; display: table; width: 100%; table-layout: fixed; }
  thead { background: #2d6a4f; color: white; }
  tfoot { background: #f4faf6; color: #555; font-size: 0.78em; }
  tbody { flex: 1; overflow-y: auto; display: block; width: 100%; }
  tbody tr { display: table; width: 100%; table-layout: fixed; }

  th, td { padding: 6px 8px; font-size: 0.78em; border-bottom: 1px solid #d8f3dc; text-align: left; }
  thead tr:first-child th { border-bottom: 1px solid #52b788; cursor: pointer; }
  thead tr:first-child th:hover { background: #1b4332; }
  /* Fila de filtros */
  thead tr:last-child { background: #1b4332; }
  thead tr:last-child input, thead tr:last-child select { width: 100%; padding: 4px 6px; font-size: 0.78em; border: 1px solid #52b788; background: #f4faf6; }
  tbody tr:nth-child(even) { background: #f4faf6; }
  tbody tr:hover { background: #d8f3dc; }

  /* Columnas responsivas: ocultar en pantallas pequeñas */
  @media (max-width: 700px) {
    [campo-dato="Observaciones"], [campo-dato="NroDeFacturaImputada"] { display: none; }
  }

  .volver { text-align: center; padding: 6px; font-size: 0.78em; flex-shrink: 0; }
  .volver a { color: #1b4332; text-decoration: none; }
</style>
</head>
<body>

<div class="pantalla">

  <header>
    <h1>Ejer24 — Notas de Crédito: Lista, Orden y Filtro</h1>
    <button id="btCargar">Cargar Datos</button>
    <button id="btVaciar">Vaciar Datos</button>
  </header>

  <div class="zona-tabla">
    <table>
      <thead>
        <!-- Fila 1: títulos clickeables para ordenar -->
        <tr>
          <th campo-dato="NroComprobante">Nro. Comprobante</th>
          <th campo-dato="NroDeFacturaImputada">Factura Imputada</th>
          <th campo-dato="codigoTipo">Tipo</th>
          <th campo-dato="CodCliente">Cliente</th>
          <th campo-dato="Observaciones">Observaciones</th>
          <th campo-dato="fechaComprobante">Fecha</th>
          <th campo-dato="TotalNetoComprobante">Total Neto</th>
        </tr>
        <!-- Fila 2: inputs de filtro por columna -->
        <tr>
          <th><input type="text" id="fNroComprobante"       placeholder="Filtrar..."></th>
          <th><input type="text" id="fNroDeFacturaImputada" placeholder="Filtrar..."></th>
          <th>
            <select id="fCodigoTipo">
              <option value="">Todos</option>
              <!-- Las opciones se cargan dinámicamente desde la DB -->
            </select>
          </th>
          <th><input type="text" id="fCodCliente"           placeholder="Filtrar..."></th>
          <th><input type="text" id="fObservaciones"        placeholder="Filtrar..."></th>
          <th><input type="text" id="fFechaComprobante"     placeholder="Filtrar..."></th>
          <th></th>
        </tr>
      </thead>
      <tbody id="tbDatos"></tbody>
      <tfoot>
        <tr>
          <td colspan="7" id="tdCantidad">Sin datos cargados</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <p class="volver"><a href="../index.html">← Volver al índice de PHP</a></p>

</div>

<script>
  var ordenActual = 'NroComprobante';

  /* Cargar el select de tipos de comprobante desde la DB */
  function cargarTipos() {
    fetch("salidaJsonTiposComprobante.php")
    .then(function(r) { return r.json(); })
    .then(function(obj) {
      obj.tiposComprobante.forEach(function(tipo) {
        var opt = document.createElement("option");
        opt.value = tipo.codigoTipo;
        opt.innerHTML = tipo.codigoTipo + " - " + tipo.DescripcionTipo;
        document.getElementById("fCodigoTipo").appendChild(opt);
      });
    });
  }

  /* Construir y enviar la petición con los filtros activos */
  function cargarDatos() {
    const data = new URLSearchParams();
    data.append("NroComprobante",       document.getElementById("fNroComprobante").value);
    data.append("NroDeFacturaImputada", document.getElementById("fNroDeFacturaImputada").value);
    data.append("codigoTipo",           document.getElementById("fCodigoTipo").value);
    data.append("CodCliente",           document.getElementById("fCodCliente").value);
    data.append("fechaComprobante",     document.getElementById("fFechaComprobante").value);
    data.append("orden",                ordenActual);

    fetch("salidaJsonNotasCredito.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: data
    })
    .then(function(r) { return r.json(); })
    .then(function(obj) {
      var tbody = document.getElementById("tbDatos");
      tbody.innerHTML = "";

      obj.notasCredito.forEach(function(nota) {
        var tr = document.createElement("tr");

        var campos = ["NroComprobante","NroDeFacturaImputada","codigoTipo","CodCliente","Observaciones","fechaComprobante","TotalNetoComprobante"];
        campos.forEach(function(campo) {
          var td = document.createElement("td");
          td.setAttribute("campo-dato", campo);
          td.innerHTML = nota[campo];
          tr.appendChild(td);
        });

        tbody.appendChild(tr);
      });

      document.getElementById("tdCantidad").innerHTML = "Registros encontrados: " + obj.cantidad;
    });
  }

  /* Click en encabezado: cambiar orden y recargar */
  document.querySelectorAll("thead tr:first-child th").forEach(function(th) {
    th.addEventListener("click", function() {
      ordenActual = this.getAttribute("campo-dato");
      cargarDatos();
    });
  });

  /* Botón cargar */
  document.getElementById("btCargar").addEventListener("click", cargarDatos);

  /* Botón vaciar */
  document.getElementById("btVaciar").addEventListener("click", function() {
    document.getElementById("tbDatos").innerHTML = "";
    document.getElementById("tdCantidad").innerHTML = "Sin datos cargados";
  });

  /* Filtros en tiempo real al escribir */
  ["fNroComprobante","fNroDeFacturaImputada","fCodCliente","fFechaComprobante"].forEach(function(id) {
    document.getElementById(id).addEventListener("input", cargarDatos);
  });
  document.getElementById("fCodigoTipo").addEventListener("change", cargarDatos);

  /* Al cargar la página: poblar el select de tipos */
  cargarTipos();
</script>

</body>
</html>
