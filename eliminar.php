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

// Eliminar el reporte
$sql = "DELETE FROM reportes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirigir después de eliminar
header("Location: ver_reportes.php");
exit();

$conn->close();
?>
