<?php
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

// Obtener el ID del reporte desde la URL
$id = $_GET['id'];

// Obtener el reporte actual
$sql = "SELECT estado FROM reportes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reporte = $result->fetch_assoc();


if ($reporte) {
    $nuevo_estado = ($reporte['estado'] == 'En proceso') ? 'Terminada' : 'En proceso';

    // Actualizar el estado en la base de datos
    $sql_update = "UPDATE reportes SET estado = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $nuevo_estado, $id);
    $stmt_update->execute();

    
    header("Location: ver_reportes.php");
    exit();
} else {
    echo "Reporte no encontrado.";
}

// Cerrar la conexión
$conn->close();
?>
