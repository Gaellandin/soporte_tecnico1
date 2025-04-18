<?php
// Conexión a la base de datos
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "soporte_clientes";
$conn = new mysqli($host, $usuario, $clave, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los filtros si se han enviado
$responsableFiltro = isset($_GET['responsable']) ? $_GET['responsable'] : '';
$equipoFiltro = isset($_GET['equipo']) ? $_GET['equipo'] : '';
$fechaFiltro = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Construir la consulta SQL con filtros dinámicos
$query = "SELECT * FROM limpieza_equipos WHERE 1";

if ($responsableFiltro) {
    $query .= " AND responsable LIKE '%$responsableFiltro%'";
}

if ($equipoFiltro) {
    $query .= " AND equipo = '$equipoFiltro'";
}

if ($fechaFiltro) {
    $query .= " AND fecha = '$fechaFiltro'";
}

$query .= " ORDER BY fecha ASC";

// Consultar las limpiezas programadas
$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver Limpiezas Programadas</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: #f4f4f4;
      color: #333;
      margin: 0;
      padding: 20px;
    }
    h2 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }
    th {
      background-color: #34495e;
      color: white;
      font-weight: 500;
      text-transform: uppercase;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .filters {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .filters select, .filters input {
      padding: 8px;
      margin-right: 10px;
      font-size: 14px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
  </style>
</head>
<body>

<h2>🔧 Limpiezas Programadas de Equipos</h2>

<!-- Filtros -->
<form class="filters" method="GET">
  <div>
    <input type="text" name="responsable" placeholder="Filtrar por responsable" value="<?= $responsableFiltro ?>">
    <select name="equipo">
      <option value="">Filtrar por tipo de equipo</option>
      <option value="Computadora de escritorio" <?= $equipoFiltro == 'Computadora de escritorio' ? 'selected' : '' ?>>Computadora de escritorio</option>
      <option value="Laptop" <?= $equipoFiltro == 'Laptop' ? 'selected' : '' ?>>Laptop</option>
      <option value="Servidor" <?= $equipoFiltro == 'Servidor' ? 'selected' : '' ?>>Servidor</option>
      <option value="Otro" <?= $equipoFiltro == 'Otro' ? 'selected' : '' ?>>Otro</option>
    </select>
    <input type="date" name="fecha" value="<?= $fechaFiltro ?>">
    <button type="submit">Filtrar</button>
  </div>
</form>

<!-- Tabla de limpiezas programadas -->
<table>
  <tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Responsable</th>
    <th>Equipo</th>
    <th>Detalles del Equipo</th>
    <th>Motivo</th>
    <th>Comentarios</th>
  </tr>
  <?php if ($resultado->num_rows > 0): ?>
    <?php while ($row = $resultado->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['fecha'] ?></td>
      <td><?= htmlspecialchars($row['responsable']) ?></td>
      <td><?= $row['equipo'] ?></td>
      <td><?= htmlspecialchars($row['detalles']) ?></td>
      <td><?= nl2br(htmlspecialchars($row['motivo'])) ?></td>
      <td><?= nl2br(htmlspecialchars($row['comentarios'])) ?></td>
    </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="7">No hay limpiezas programadas.</td>
    </tr>
  <?php endif; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
