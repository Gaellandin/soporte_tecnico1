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

// Recoge los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$departamento = $_POST['departamento'];
$recurso = $_POST['recurso'];
$cantidad = $_POST['cantidad'];
$fecha = $_POST['fecha'];
$motivo = $_POST['motivo'];

// Inserta los datos en la base
$sql = "INSERT INTO solicitudes (nombre, email, departamento, recurso, cantidad, fecha, motivo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssiss", $nombre, $email, $departamento, $recurso, $cantidad, $fecha, $motivo);

if ($stmt->execute()) {
    echo "✅ Solicitud enviada correctamente.";
} else {
    echo "❌ Error al enviar solicitud: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
