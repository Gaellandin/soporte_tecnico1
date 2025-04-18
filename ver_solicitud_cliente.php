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

// Definir variable de búsqueda
$nombre_buscar = isset($_POST['nombre']) ? $_POST['nombre'] : '';

// Consultar las solicitudes basadas en el nombre
$sql = "SELECT * FROM solicitudes WHERE nombre LIKE ? ORDER BY fecha_solicitud DESC";
$stmt = $conn->prepare($sql);

// Si hay un nombre para buscar, lo utilizamos, de lo contrario, mostramos todos
$search_term = "%$nombre_buscar%";
$stmt->bind_param('s', $search_term);
$stmt->execute();
$resultado = $stmt->get_result();
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
    .search-form {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }
    .search-form input[type="text"] {
      padding: 10px;
      width: 300px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    .search-form button {
      padding: 10px 20px;
      margin-left: 10px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .search-form button:hover {
      background-color: #2980b9;
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
  </style>
</head>
<body>

<h2>📋 Solicitudes de Recursos</h2>

<!-- Formulario de búsqueda -->
<div class="search-form">
  <form method="POST" action="">
    <input type="text" name="nombre" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($nombre_buscar) ?>">
    <button type="submit">Buscar</button>
  </form>
</div>

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
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
