<?php
// Conexión
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "soporte_clientes";
$conn = new mysqli($host, $usuario, $clave, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los filtros si se han enviado
$estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : '';
$departamentoFiltro = isset($_GET['departamento']) ? $_GET['departamento'] : '';
$fechaFiltro = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Construir la consulta SQL con filtros dinámicos
$query = "SELECT * FROM solicitudes WHERE 1";

if ($estadoFiltro) {
    $query .= " AND estado = '$estadoFiltro'";
}

if ($departamentoFiltro) {
    $query .= " AND departamento = '$departamentoFiltro'";
}

if ($fechaFiltro) {
    $query .= " AND fecha_solicitud >= '$fechaFiltro'";
}

$query .= " ORDER BY fecha_solicitud DESC";

// Si se envió una acción
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $accion = $_GET['accion'];
    if ($accion == "aprobar") {
        $estado = "Aprobado";
    } elseif ($accion == "rechazar") {
        $estado = "Rechazado";
    } else {
        $estado = "Pendiente";
    }

    $conn->query("UPDATE solicitudes SET estado='$estado' WHERE id=$id");
    header("Location: ver_solicitudes.php"); 
    exit();
}

// Consultar las solicitudes
$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes Recibidas</title>
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
    .btn {
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 500;
      font-size: 14px;
      display: inline-block;
      margin: 5px 0;
      text-align: center;
      width: 100px;
      transition: background-color 0.2s ease;
    }
    .aprobar {
      background-color: #27ae60;
      color: white;
    }
    .aprobar:hover {
      background-color: #2ecc71;
    }
    .rechazar {
      background-color: #e74c3c;
      color: white;
    }
    .rechazar:hover {
      background-color: #c0392b;
    }
    .estado {
      font-weight: 500;
      padding: 5px;
      border-radius: 4px;
      text-align: center;
    }
    .estado.aprobado {
      background-color: #27ae60;
      color: white;
    }
    .estado.rechazado {
      background-color: #e74c3c;
      color: white;
    }
    .estado.pendiente {
      background-color: #f39c12;
      color: white;
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

<h2>📋 Solicitudes de Recursos</h2>

<!-- Filtros -->
<form class="filters" method="GET">
  <div>
    <select name="estado">
      <option value="">Estado</option>
      <option value="Aprobado" <?= $estadoFiltro == 'Aprobado' ? 'selected' : '' ?>>Aprobado</option>
      <option value="Rechazado" <?= $estadoFiltro == 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
      <option value="Pendiente" <?= $estadoFiltro == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
    </select>
    <select name="departamento">
      <option value="">Departamento</option>
      <option value="Ventas" <?= $departamentoFiltro == 'Ventas' ? 'selected' : '' ?>>Ventas</option>
      <option value="Soporte" <?= $departamentoFiltro == 'Soporte' ? 'selected' : '' ?>>Soporte</option>
      <option value="IT" <?= $departamentoFiltro == 'IT' ? 'selected' : '' ?>>IT</option>
    </select>
    <input type="date" name="fecha" value="<?= $fechaFiltro ?>">
    <button type="submit">Filtrar</button>
  </div>
</form>

<!-- Tabla de solicitudes -->
<table>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Correo</th>
    <th>Departamento</th>
    <th>Recurso</th>
    <th>Cantidad</th>
    <th>Fecha</th>
    <th>Motivo</th>
    <th>Estado</th>
    <th>Acciones</th>
  </tr>
  <?php while ($row = $resultado->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['nombre']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= $row['departamento'] ?></td>
    <td><?= $row['recurso'] ?></td>
    <td><?= $row['cantidad'] ?></td>
    <td><?= $row['fecha'] ?></td>
    <td><?= nl2br(htmlspecialchars($row['motivo'])) ?></td>
    <td class="estado <?= strtolower($row['estado']) ?>"><?= $row['estado'] ?></td>
    <td>
      <a class="btn aprobar" href="?accion=aprobar&id=<?= $row['id'] ?>">Aprobar</a>
      <a class="btn rechazar" href="?accion=rechazar&id=<?= $row['id'] ?>">Rechazar</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
