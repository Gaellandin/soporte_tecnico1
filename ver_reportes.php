<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Reportes</title>

  <a href="ver_solicitudes.php "> solicitudes Pendientes</a>
  
  <style>

    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    h2 {
      color: #333;
    }
    .reporte {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      background-color: #f9f9f9;
    }
    .titulo {
      font-weight: bold;
      font-size: 1.2em;
    }
    .fecha {
      color: #888;
      font-size: 0.9em;
    }
    .acciones {
      margin-top: 10px;
    }
    .acciones a {
      text-decoration: none;
      padding: 6px 12px;
      margin-right: 5px;
      border-radius: 4px;
      font-size: 0.9em;
    }
    .ver {
      background-color: #3498db;
      color: white;
    }
    .editar {
      background-color: #f1c40f;
      color: black;
    }
    .eliminar {
      background-color: #e74c3c;
      color: white;
    }
    .filtros {
      margin-bottom: 20px;
    }
    .filtros select, .filtros input {
      padding: 6px;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<h2>📋 Lista de Reportes</h2>

<!-- Filtros -->
<form class="filtros" method="GET" action="">
  <select name="estado">
    <option value="">Filtrar por Estado</option>
    <option value="En proceso" <?php if (isset($_GET['estado']) && $_GET['estado'] == 'En proceso') echo 'selected'; ?>>En proceso</option>
    <option value="Terminada" <?php if (isset($_GET['estado']) && $_GET['estado'] == 'Terminada') echo 'selected'; ?>>Terminada</option>
  </select>
  
  <select name="categoria">
    <option value="">Filtrar por Categoría</option>
    <option value="Técnico" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'Técnico') echo 'selected'; ?>>Técnico</option>
    <option value="Software" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'Software') echo 'selected'; ?>>Software</option>
    <option value="Hardware" <?php if (isset($_GET['categoria']) && $_GET['categoria'] == 'Hardware') echo 'selected'; ?>>Hardware</option>
  </select>
  
  <input type="date" name="fecha" value="<?php if (isset($_GET['fecha'])) echo $_GET['fecha']; ?>" placeholder="Filtrar por Fecha">
  
  <input type="submit" value="Filtrar">
</form>

<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = 'localhost';  
$usuario = 'root';    
$contrasena = '';     
$basededatos = 'soporte_clientes'; 

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error); // Si hay un error de conexión, muestra el mensaje
}

$sql = "SELECT id, categoria, descripcion, archivo, fecha, estado FROM reportes WHERE 1";

if (isset($_GET['estado']) && $_GET['estado'] != '') {
    $estado = $_GET['estado'];
    $sql .= " AND estado = '$estado'";
}

if (isset($_GET['categoria']) && $_GET['categoria'] != '') {
    $categoria = $_GET['categoria'];
    $sql .= " AND categoria = '$categoria'";
}

if (isset($_GET['fecha']) && $_GET['fecha'] != '') {
    $fecha = $_GET['fecha'];
    $sql .= " AND fecha = '$fecha'";
}

$sql .= " ORDER BY fecha DESC";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        echo "<div class='reporte'>";
        echo "<div class='titulo'>" . htmlspecialchars($fila["categoria"]) . ": " . htmlspecialchars($fila["descripcion"]) . "</div>";
        echo "<div class='fecha'>" . $fila["fecha"] . "</div>";
        
        // Mostrar el archivo si existe
        if ($fila['archivo']) {
            echo "<div><strong>Archivo adjunto:</strong> <a href='uploads/" . htmlspecialchars($fila["archivo"]) . "' download>Descargar</a></div>";
        }
        
        // Mostrar el estado
        echo "<div><strong>Estado:</strong> " . htmlspecialchars($fila["estado"]) . "</div>";
        
        echo "<div class='acciones'>
                <a class='ver' href='?id=" . $fila["id"] . "'>🔍 Ver</a>
                <a class='editar' href='editar.php?id=" . $fila["id"] . "'>✏️ Editar</a>
                <a class='eliminar' href='eliminar.php?id=" . $fila["id"] . "' onclick=\"return confirm('¿Seguro que deseas eliminar este reporte?');\">🗑️ Eliminar</a>
                <a class='cambiar-estado' href='cambiar_estado.php?id=" . $fila["id"] . "'>🔄 Cambiar Estado</a>
              </div>";
        echo "</div>";
    }
} else {
    echo "<p>No hay reportes disponibles.</p>";
}



// Cerrar la conexión
$conn->close();
?> 

</body>
</html>
