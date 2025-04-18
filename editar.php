<?php
// Conexión a la base de datos
$host = 'localhost';  
$usuario = 'root';    
$contrasena = '';     
$basededatos = 'soporte_clientes'; 

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del reporte desde la URL
$id = $_GET['id'];

// Obtener el reporte actual
$sql = "SELECT * FROM reportes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reporte = $result->fetch_assoc();

// Actualizar el reporte si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    
    // Aquí puedes manejar la carga de archivos si se requiere
    // Subir archivo (por ejemplo, a un directorio "uploads")
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $archivo = $_FILES['archivo']['name'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], "uploads/" . $archivo);
    } else {
        $archivo = $reporte['archivo']; // Mantener el archivo anterior si no se subió uno nuevo
    }

    // Actualizar el reporte en la base de datos
    $sql_update = "UPDATE reportes SET categoria = ?, descripcion = ?, fecha = ?, archivo = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $categoria, $descripcion, $fecha, $archivo, $id);
    $stmt_update->execute();

    // Redirigir a la página principal después de la actualización
    header("Location: ver_reportes.php");
    exit();
}
?>

<!-- Formulario para editar -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Reporte</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f7fa;
      padding: 20px;
    }

    h2 {
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 50%;
      margin: 0 auto;
    }

    label {
      font-size: 1rem;
      color: #333;
      margin-bottom: 5px;
      display: block;
    }

    input[type="text"],
    input[type="date"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    textarea {
      height: 100px;
      resize: vertical;
    }

    input[type="file"] {
      padding: 5px;
      margin-bottom: 15px;
    }

    input[type="submit"] {
      background-color: #3498db;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      color: #3498db;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Editar Reporte</h2>

  <form method="post" enctype="multipart/form-data">
      <label for="categoria">Categoría:</label>
      <input type="text" name="categoria" value="<?php echo htmlspecialchars($reporte['categoria']); ?>" required><br>
      
      <label for="descripcion">Descripción:</label>
      <textarea name="descripcion" required><?php echo htmlspecialchars($reporte['descripcion']); ?></textarea><br>
      
      <label for="fecha">Fecha:</label>
      <input type="date" name="fecha" value="<?php echo $reporte['fecha']; ?>" required><br>
      
      <label for="archivo">Archivo:</label>
      <input type="file" name="archivo"><br>
      
      <input type="submit" value="Actualizar Reporte">
  </form>

  <div class="back-link">
      <a href="ver_reportes.php">Volver a la lista de reportes</a>
  </div>
</div>

</body>
</html>
