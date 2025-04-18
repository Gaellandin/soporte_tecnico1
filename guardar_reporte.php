<?php

$host = "localhost";
$usuario = "root";
$contrasena = "";
$basededatos = "soporte_clientes";


$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verifica la conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}


$categoria = $_POST['categoria'] ?? ''; 
$descripcion = $_POST['descripcion'] ?? ''; 


if (empty($categoria) || empty($descripcion)) {
  die("Error: Faltan datos en el formulario.");
}

// Inserta los datos en la base de datos
$sql = "INSERT INTO reportes (categoria, descripcion) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

// Verificar que la preparación del statement fue exitosa
if ($stmt === false) {
  die("Error en la preparación de la consulta: " . $conn->error);
}


$stmt->bind_param("ss", $categoria, $descripcion);

// Ejecuta la consulta
if ($stmt->execute()) {
    echo "Reporte guardado exitosamente ✅ .<br>";
} else {
    echo "Error al guardar el reporte: " . $stmt->error . "<br>";
}

// Cierra la conexión
$stmt->close();
$conn->close();


?>
