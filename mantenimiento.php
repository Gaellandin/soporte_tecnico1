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

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = $_POST['fecha'];
    $responsable = $_POST['responsable'];
    $equipo = $_POST['equipo'];
    $detalles = $_POST['detalles'];
    $motivo = $_POST['motivo'];
    $comentarios = $_POST['comentarios'];

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO limpieza_equipos (fecha, responsable, equipo, detalles, motivo, comentarios) 
            VALUES ('$fecha', '$responsable', '$equipo', '$detalles', '$motivo', '$comentarios')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Limpieza programada con éxito.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Programar Limpieza de Equipos</title>
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
    form {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 0 auto;
    }
    label {
      font-weight: 500;
      display: block;
      margin: 10px 0 5px;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    button {
      background-color: #3498db;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    button:hover {
      background-color: #2980b9;
    }
  </style>
</head>
<body>

<h2>🧹 Programar Limpieza de Equipos</h2>

<form method="POST" action="">
  <label for="fecha">Fecha programada:</label>
  <input type="date" name="fecha" required>

  <label for="responsable">Responsable de la limpieza:</label>
  <input type="text" name="responsable" required>

  <label for="equipo">Tipo de equipo:</label>
  <select name="equipo" required>
    <option value="Computadora de escritorio">Computadora de escritorio</option>
    <option value="Laptop">Laptop</option>
    <option value="Servidor">Servidor</option>
    <option value="Otro">Otro</option>
  </select>

  <label for="detalles">Numero de Equipo</label>
  <input type="text" name="detalles" required>

  <label for="motivo">Motivo de la limpieza:</label>
  <textarea name="motivo" rows="4" required></textarea>

  <label for="comentarios">Comentarios adicionales:</label>
  <textarea name="comentarios" rows="4"></textarea>

  <button type="submit">Programar limpieza</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
